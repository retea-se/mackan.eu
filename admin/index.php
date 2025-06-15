<?php

session_start();

// Logga ut
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Inloggning
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = 'admin';
    $pass = 'byt-mig!'; // Byt till ditt riktiga lösenord!
    $input_user = $_POST['username'] ?? '';
    $input_pass = $_POST['password'] ?? '';
    if ($input_user === $user && $input_pass === $pass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = 'Felaktigt användarnamn eller lösenord.';
    }
}

// Om inte inloggad, visa formulär
if (empty($_SESSION['admin_logged_in'])):
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; margin: 2em; }
        form { background: #fff; padding: 2em; max-width: 300px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        input { width: 100%; margin-bottom: 1em; padding: 0.5em; }
        .error { color: red; }
    </style>
    <link rel="icon" href="data:,">
</head>
<body>
    <form method="post">
        <h2>Admin Login</h2>
        <?php if ($login_error): ?><div class="error"><?= htmlspecialchars($login_error) ?></div><?php endif; ?>
        <input type="text" name="username" placeholder="Användarnamn" required>
        <input type="password" name="password" placeholder="Lösenord" required>
        <button type="submit">Logga in</button>
    </form>
</body>
</html>
<?php
exit;
endif;
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Adminpanel</title>
    <style>
        body { font-family: sans-serif; background: #f8f8f8; margin: 2em; }
        .dash-container { max-width: 700px; margin: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 2em; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2em; }
        h1 { margin: 0; }
        .btn { display: inline-block; padding: 0.5em 1em; background: #0074d9; color: #fff; border-radius: 4px; text-decoration: none; margin-bottom: 0.5em; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 1em; }
        li .btn { width: 100%; text-align: left; font-size: 1.1em; }
        @media (max-width: 600px) {
            .dash-container { padding: 1em; }
            header { flex-direction: column; gap: 1em; }
        }
    </style>
</head>
<body>
<div class="dash-container">
    <header>
        <h1>Adminpanel</h1>
        <a href="?logout=1" class="btn">🚪 Logga ut</a>
    </header>
    <ul>
        <li><a href="dashboard.php" class="btn">📊 Dashboard</a></li>
        <li><a href="insight.php" class="btn">🔍 Insight</a></li>
        <li><a href="kortlank-stats.php" class="btn">🔗 Kortlänk-statistik</a></li>
        <li><a href="hantera-kortlankar.php" class="btn">🗑️ Hantera kortlänkar</a></li>
        <li><a href="visits.php" class="btn">📈 Besöksstatistik</a></li>
        <!-- Lägg till fler länkar här om du vill -->
    </ul>
</div>
</body>
</html>
