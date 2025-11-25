<?php
// admin/index.php - Consolidated Admin Dashboard
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: auth.php");
    exit;
}

// Handle magic link token validation (from auth-handler.php redirect)
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    $tokenFile = __DIR__ . '/auth-tokens.json';
    if (file_exists($tokenFile)) {
        $tokens = json_decode(file_get_contents($tokenFile), true) ?: [];

        if (isset($tokens[$token])) {
            $tokenData = $tokens[$token];

            // Check if token is expired (1 hour)
            $isExpired = (time() - $tokenData['created']) > 3600;
            $isUsed = !empty($tokenData['used']);

            if ($isExpired) {
                unset($tokens[$token]);
                file_put_contents($tokenFile, json_encode($tokens));
                header('Location: auth.php?error=expired_token');
                exit;
            }

            if ($isUsed) {
                header('Location: auth.php?error=token_used');
                exit;
            }

            // Check if email matches
            if (strtolower($tokenData['email']) === strtolower($email)) {
                // Mark token as used BEFORE creating session
                $tokens[$token]['used'] = true;
                file_put_contents($tokenFile, json_encode($tokens));

                // Create session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_email'] = $email;
                $_SESSION['admin_login_time'] = time();

                // Redirect to remove token from URL
                header('Location: index.php');
                exit;
            } else {
                header('Location: auth.php?error=email_mismatch');
                exit;
            }
        }
    }

    // Invalid token - redirect to login
    header('Location: auth.php?error=invalid_token');
    exit;
}

// Check authentication - redirect to login if not logged in
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: auth.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="sv" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard-theme.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="icon" href="data:,">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
</head>
<body>
    <div class="dash-container">
        <!-- Header -->
        <header class="dashboard-header">
            <h1>📊 Admin Dashboard</h1>
            <div class="header-actions">
                <button id="themeToggle" class="theme-toggle" aria-label="Växla tema">🌙</button>
                <a href="?logout=1" class="btn btn-logout">🚪 Logga ut</a>
            </div>
        </header>

        <!-- Overview Stats Cards -->
        <section class="stats-overview">
            <div class="stat-card stat-live" id="stat-live">
                <div class="stat-icon">🔴</div>
                <div class="stat-content">
                    <div class="stat-label">Live Besökare</div>
                    <div class="stat-value" id="live-count">-</div>
                    <div class="stat-subtitle">Senaste 5 min</div>
                </div>
            </div>

            <div class="stat-card stat-visits">
                <div class="stat-icon">📈</div>
                <div class="stat-content">
                    <div class="stat-label">Totalt Besök</div>
                    <div class="stat-value" id="stat-visits-total">-</div>
                    <div class="stat-subtitle" id="stat-visits-subtitle">-</div>
                </div>
            </div>

            <div class="stat-card stat-kortlank">
                <div class="stat-icon">🔗</div>
                <div class="stat-content">
                    <div class="stat-label">Kortlänkar</div>
                    <div class="stat-value" id="stat-kortlank-total">-</div>
                    <div class="stat-subtitle" id="stat-kortlank-subtitle">-</div>
                </div>
            </div>

            <div class="stat-card stat-skyddad">
                <div class="stat-icon">🛡️</div>
                <div class="stat-content">
                    <div class="stat-label">Skyddad</div>
                    <div class="stat-value" id="stat-skyddad-total">-</div>
                    <div class="stat-subtitle" id="stat-skyddad-subtitle">-</div>
                </div>
            </div>
        </section>

        <!-- Detailed Sections (Accordion on mobile) -->
        <section class="dashboard-sections">
            <!-- Visits Section -->
            <div class="accordion-section">
                <div class="accordion-header" id="visits-header">
                    <h2>📈 Besöksstatistik</h2>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content" id="visits-content">
                    <div class="section-stats">
                        <div class="mini-stat">
                            <span class="mini-stat-label">Unika IP:</span>
                            <span class="mini-stat-value" id="visits-unique">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Människor:</span>
                            <span class="mini-stat-value" id="visits-humans">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Botar:</span>
                            <span class="mini-stat-value" id="visits-bots">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Idag:</span>
                            <span class="mini-stat-value" id="visits-today">-</span>
                        </div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-container" data-lazy data-chart-id="hourly-visits">
                            <h3>Besök per timme (24h)</h3>
                            <div id="chart-hourly-visits" class="chart"></div>
                        </div>
                        <div class="chart-container" data-lazy data-chart-id="top-pages">
                            <h3>Topp sidor</h3>
                            <div id="chart-top-pages" class="chart"></div>
                        </div>
                    </div>

                    <!-- Referrers -->
                    <div class="data-table-container">
                        <h3>Topp referrers (30 dagar)</h3>
                        <div class="table-wrapper">
                            <table id="referrers-table">
                                <thead>
                                    <tr>
                                        <th>Referrer</th>
                                        <th>Besök</th>
                                    </tr>
                                </thead>
                                <tbody id="referrers-tbody">
                                    <tr><td colspan="2">Laddar...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Search Terms -->
                    <div class="data-table-container">
                        <h3>Sökord (30 dagar)</h3>
                        <div class="table-wrapper">
                            <table id="search-terms-table">
                                <thead>
                                    <tr>
                                        <th>Sökord</th>
                                        <th>Antal</th>
                                    </tr>
                                </thead>
                                <tbody id="search-terms-tbody">
                                    <tr><td colspan="2">Laddar...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Click Events -->
                    <div class="data-table-container">
                        <h3>Vad användare klickar på (30 dagar)</h3>
                        <div class="table-wrapper">
                            <table id="click-events-table">
                                <thead>
                                    <tr>
                                        <th>Element</th>
                                        <th>Klick</th>
                                    </tr>
                                </thead>
                                <tbody id="click-events-tbody">
                                    <tr><td colspan="2">Laddar...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Languages & Devices -->
                    <div class="stats-grid-2">
                        <div class="data-table-container">
                            <h3>Språk (30 dagar)</h3>
                            <div class="table-wrapper">
                                <table id="languages-table">
                                    <thead>
                                        <tr>
                                            <th>Språk</th>
                                            <th>Besök</th>
                                        </tr>
                                    </thead>
                                    <tbody id="languages-tbody">
                                        <tr><td colspan="2">Laddar...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="data-table-container">
                            <h3>Enhetstyper (30 dagar)</h3>
                            <div class="table-wrapper">
                                <table id="device-types-table">
                                    <thead>
                                        <tr>
                                            <th>Enhet</th>
                                            <th>Besök</th>
                                        </tr>
                                    </thead>
                                    <tbody id="device-types-tbody">
                                        <tr><td colspan="2">Laddar...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kortlank Section -->
            <div class="accordion-section">
                <div class="accordion-header" id="kortlank-header">
                    <h2>🔗 Kortlänkar</h2>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content" id="kortlank-content">
                    <div class="section-stats">
                        <div class="mini-stat">
                            <span class="mini-stat-label">Aktiva:</span>
                            <span class="mini-stat-value" id="kortlank-active">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Totalt klick:</span>
                            <span class="mini-stat-value" id="kortlank-clicks">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Med lösenord:</span>
                            <span class="mini-stat-value" id="kortlank-password">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Utgångna:</span>
                            <span class="mini-stat-value" id="kortlank-expired">-</span>
                        </div>
                    </div>

                    <div class="data-table-container">
                        <h3>Mest klickade länkar</h3>
                        <div class="table-wrapper">
                            <table id="kortlank-top-table">
                                <thead>
                                    <tr>
                                        <th>Länk</th>
                                        <th>Klick</th>
                                        <th>Skapad</th>
                                    </tr>
                                </thead>
                                <tbody id="kortlank-top-tbody">
                                    <tr><td colspan="3">Laddar...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skyddad Section -->
            <div class="accordion-section">
                <div class="accordion-header" id="skyddad-header">
                    <h2>🛡️ Skyddad</h2>
                    <span class="accordion-icon">▼</span>
                </div>
                <div class="accordion-content" id="skyddad-content">
                    <div class="section-stats">
                        <div class="mini-stat">
                            <span class="mini-stat-label">Skapade:</span>
                            <span class="mini-stat-value" id="skyddad-created">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Visade:</span>
                            <span class="mini-stat-value" id="skyddad-viewed">-</span>
                        </div>
                        <div class="mini-stat">
                            <span class="mini-stat-label">Cron-jobb:</span>
                            <span class="mini-stat-value" id="skyddad-cron">-</span>
                        </div>
                    </div>

                    <div class="data-table-container">
                        <h3>Senaste events</h3>
                        <div class="table-wrapper">
                            <table id="skyddad-events-table">
                                <thead>
                                    <tr>
                                        <th>Tidpunkt</th>
                                        <th>Typ</th>
                                        <th>Secret ID</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody id="skyddad-events-tbody">
                                    <tr><td colspan="4">Laddar...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="dashboard-mobile.js"></script>
    <script src="dashboard-main.js"></script>
</body>
</html>
