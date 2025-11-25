<?php
// auth.php - Magic link login page
session_start();

// If already logged in, redirect to dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = $_GET['error'] ?? '';
$errorMessages = [
    'invalid_token' => 'Ogiltig eller felaktig inloggningslänk.',
    'expired_token' => 'Inloggningslänken har gått ut. Begär en ny länk.',
    'token_used' => 'Denna inloggningslänk har redan använts. Begär en ny länk.',
    'email_mismatch' => 'E-postadressen matchar inte länken.'
];

$errorMessage = isset($errorMessages[$error]) ? $errorMessages[$error] : '';
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Magic Link</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2.5rem;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 1.75rem;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #fcc;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid #cfc;
            display: none;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 0.875rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            min-height: 44px; /* Touch-friendly */
        }

        button:hover {
            background: #5568d3;
        }

        button:active {
            background: #4457c2;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            text-align: center;
            color: #666;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .loading.active {
            display: block;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 Admin Dashboard</h1>
        <p class="subtitle">Ange din e-postadress för att få en inloggningslänk</p>

        <?php if ($errorMessage): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="success-message" id="successMessage">
            Om e-postadressen finns i systemet har en inloggningslänk skickats.
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">E-postadress</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="din@email.com"
                    required
                    autocomplete="email"
                    autofocus
                >
            </div>

            <button type="submit" id="submitBtn">
                Skicka inloggningslänk
            </button>
        </form>

        <div class="loading" id="loading">
            Skickar...
        </div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const successMessage = document.getElementById('successMessage');
        const errorMessage = document.querySelector('.error-message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;

            // Disable form
            submitBtn.disabled = true;
            loading.classList.add('active');
            successMessage.style.display = 'none';
            if (errorMessage) errorMessage.style.display = 'none';

            try {
                const response = await fetch('auth-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ email })
                });

                const data = await response.json();

                if (data.success) {
                    successMessage.style.display = 'block';
                    form.reset();
                } else {
                    if (errorMessage) {
                        errorMessage.textContent = data.message || 'Ett fel uppstod. Försök igen.';
                        errorMessage.style.display = 'block';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (errorMessage) {
                    errorMessage.textContent = 'Ett fel uppstod. Försök igen.';
                    errorMessage.style.display = 'block';
                }
            } finally {
                submitBtn.disabled = false;
                loading.classList.remove('active');
            }
        });
    </script>
</body>
</html>

