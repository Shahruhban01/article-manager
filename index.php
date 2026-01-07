<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

$error = "";

// 🔐 Hashed version of "abcdxyz"
$storedHash = '$2y$10$mwxutLPYLciIhaqg5YyoreXXFGrat50qFLIMUdNwKh9ULYt95vy7S';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = $_POST['code'] ?? '';

    if (password_verify($code, $storedHash)) {
        $_SESSION['logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid access code";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Secure Access Required</title>
    
    <style>
        :root {
            --bg-primary: #0a0e27;
            --bg-secondary: #151937;
            --bg-card: #1a1f3a;
            --text-primary: #e8eaf6;
            --text-secondary: #9fa8da;
            --text-muted: #7986cb;
            --accent: #ff1744;
            --accent-hover: #ff5983;
            --accent-glow: rgba(255, 23, 68, 0.3);
            --border: #283593;
            --border-light: #3949ab;
            --error: #ff1744;
            --success: #00e676;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, #0d1128 100%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.3; }
            50% { transform: scale(1.2) rotate(180deg); opacity: 0.6; }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 450px;
        }

        .access-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), 
                        0 0 100px rgba(255, 23, 68, 0.1);
            backdrop-filter: blur(20px);
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            border-radius: 50%;
            box-shadow: 0 10px 30px var(--accent-glow);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .lock-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, var(--accent-hover) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
        }

input[type="password"] {
    width: 100%;
    padding: 1rem 1.25rem;
    padding-right: 3.5rem;
    background: rgba(0, 0, 0, 0.4);
    border: 2px solid var(--border);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: 1.1rem;
    font-family: 'Courier New', monospace;
    letter-spacing: 0.1em;
    transition: all 0.3s ease;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

input[type="password"]:hover {
    border-color: var(--border-light);
    background: rgba(0, 0, 0, 0.5);
}

input[type="password"]:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px var(--accent-glow);
    background: rgba(0, 0, 0, 0.6);
}

input[type="password"]::placeholder {
    color: var(--text-muted);
    letter-spacing: 0.05em;
    font-family: inherit;
}

/* Fix Chrome/Edge autofill override */
input[type="password"]:-webkit-autofill,
input[type="password"]:-webkit-autofill:hover,
input[type="password"]:-webkit-autofill:focus,
input[type="password"]:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.4) inset !important;
    -webkit-text-fill-color: var(--text-primary) !important;
    border: 2px solid var(--border) !important;
    transition: background-color 5000s ease-in-out 0s;
}

/* Autofill hover state */
input[type="password"]:-webkit-autofill:hover {
    -webkit-box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5) inset !important;
    border-color: var(--border-light) !important;
}

/* Autofill focus state */
input[type="password"]:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.6) inset !important, 0 0 0 4px var(--accent-glow) !important;
    border-color: var(--accent) !important;
}


        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--accent);
        }

        button[type="submit"] {
            width: 100%;
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px var(--accent-glow);
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px var(--accent-glow);
        }

        button[type="submit"]:hover::before {
            left: 100%;
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(255, 23, 68, 0.15);
            border: 1px solid var(--error);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            color: var(--error);
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .error-message::before {
            content: '⚠';
            font-size: 1.5rem;
        }

        .security-notice {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .security-notice p {
            color: var(--text-muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .security-notice svg {
            width: 16px;
            height: 16px;
            fill: var(--text-muted);
            vertical-align: middle;
            margin-right: 0.5rem;
        }

        @media (max-width: 480px) {
            .access-card {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 0.9rem;
            }

            .lock-icon {
                width: 60px;
                height: 60px;
            }

            .lock-icon svg {
                width: 30px;
                height: 30px;
            }
        }

        /* Loading state */
        button[type="submit"].loading {
            pointer-events: none;
            opacity: 0.7;
        }

        button[type="submit"].loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        
    </style>
</head>
<body>

<div class="container">
    <div class="access-card">
        <div class="lock-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 1C8.676 1 6 3.676 6 7v2c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V11c0-1.1-.9-2-2-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
            </svg>
        </div>

        <h1>Access Required</h1>
        <p class="subtitle">Enter your secure access code to continue</p>

        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" id="accessForm">
            <div class="form-group">
                <label for="code">Access Code</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="code" 
                        id="code" 
                        placeholder="••••••••" 
                        required 
                        autocomplete="off"
                        autofocus
                    >
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" id="submitBtn">
                <span id="btnText">Verify Access</span>
            </button>
        </form>

        <div class="security-notice">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                Protected area. Unauthorized access is prohibited.
            </p>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('code');
    
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePassword.textContent = type === 'password' ? '👁️' : '🔒';
    });

    // Loading state on submit
    const form = document.getElementById('accessForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    form.addEventListener('submit', () => {
        submitBtn.classList.add('loading');
        btnText.style.visibility = 'hidden';
    });

    // Clear error message on input
    <?php if ($error): ?>
    passwordInput.addEventListener('input', () => {
        const errorMsg = document.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => errorMsg.remove(), 300);
        }
    });
    <?php endif; ?>
</script>

<style>
@keyframes slideOut {
    to {
        opacity: 0;
        transform: translateY(-10px);
        max-height: 0;
        padding: 0;
        margin: 0;
    }
}
</style>

</body>
</html>
