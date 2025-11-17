<?php
// Secure GitHub webhook to auto-deploy without GitHub Actions billing
// Place this file in the repository root on the server (e.g., /home/mackaneu/public_html)
// Set a strong secret in the $webhookSecret variable or via environment WEBHOOK_SECRET.
// Then create a GitHub Webhook (Repository → Settings → Webhooks) pointing to this URL:
// - Payload URL: https://<your-domain>/deploy-webhook.php
// - Content type: application/json
// - Secret: the same value as $webhookSecret
// - Events: "Just the push event"

declare(strict_types=1);

// 1) Configuration
$webhookSecret = getenv('WEBHOOK_SECRET') ?: 'REPLACE_ME_WITH_RANDOM_SECRET';
$deployBranch  = getenv('WEBHOOK_BRANCH') ?: 'refs/heads/main';
$repoWorkTree  = realpath(__DIR__); // assumes this script resides in the repo root on the server

// 2) Basic hardening: only allow POST and GitHub signature header
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['ok' => false, 'error' => 'Method Not Allowed']);
	exit;
}

$sig256 = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
if (!$sig256 || !str_starts_with($sig256, 'sha256=')) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'Missing or invalid signature header']);
	exit;
}

// 3) Read payload and verify HMAC
$payload = file_get_contents('php://input');
$calcSig = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);
if (!hash_equals($calcSig, $sig256)) {
	http_response_code(401);
	echo json_encode(['ok' => false, 'error' => 'Signature verification failed']);
	exit;
}

// 4) Parse JSON
$data = json_decode($payload, true);
if (!is_array($data)) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'Invalid JSON payload']);
	exit;
}

// Only react to push to configured branch
$ref = $data['ref'] ?? '';
if ($ref !== $deployBranch) {
	echo json_encode(['ok' => true, 'skipped' => true, 'reason' => 'Not target branch', 'ref' => $ref]);
	exit;
}

// 5) Run deployment commands safely
chdir($repoWorkTree);

function run(string $cmd): array {
	$descriptorSpec = [
		1 => ['pipe', 'w'], // stdout
		2 => ['pipe', 'w'], // stderr
	];
	$proc = proc_open($cmd, $descriptorSpec, $pipes);
	if (!is_resource($proc)) {
		return ['ok' => false, 'cmd' => $cmd, 'out' => '', 'err' => 'Failed to start process', 'code' => -1];
	}
	$out = stream_get_contents($pipes[1]);
	$err = stream_get_contents($pipes[2]);
	foreach ($pipes as $p) {
		if (is_resource($p)) {
			fclose($p);
		}
	}
	$code = proc_close($proc);
	return ['ok' => $code === 0, 'cmd' => $cmd, 'out' => $out, 'err' => $err, 'code' => $code];
}

$steps = [];
$steps[] = run('git rev-parse --is-inside-work-tree');
if (!$steps[0]['ok']) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'Not a git repository at work tree', 'steps' => $steps], JSON_PRETTY_PRINT);
	exit;
}

$steps[] = run('git fetch origin main 2>&1');
$pull = run('git pull origin main 2>&1');
if (!$pull['ok']) {
	$steps[] = $pull;
	$steps[] = run('git reset --hard origin/main 2>&1');
} else {
	$steps[] = $pull;
}
$steps[] = run('git rev-parse --short HEAD');

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'ref' => $ref, 'steps' => $steps], JSON_PRETTY_PRINT);





