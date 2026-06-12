<?php
session_start();
include("../config/database.php");

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == "user") {
        // Use prepared statements to prevent SQL Injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = "user";
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Invalid User Login";
        }
    }

    if ($role == "admin") {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Note: Change 'md5' to 'password_verify' once you hash admin passwords!
        if ($admin && md5($password) == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['role'] = "admin";
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            $error = "Invalid Admin Login";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KeyStone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #ff3b7a;
            --pink-hover: #e63570;
            --pink-light: rgba(255, 59, 122, 0.08);
            --pink-glow: rgba(255, 59, 122, 0.35);
            --dark: #080712;
            --gray-100: #f1f3f5;
            --gray-200: #e9ecef;
            --gray-400: #adb5bd;
            --gray-600: #6c757d;
            --radius: 14px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* LEFT — BRAND PANEL (Copied from Register) */
        .brand-panel {
            flex: 1;
            background: linear-gradient(160deg, #0a0a1a 0%, #1a1035 40%, #2d1b4e 70%, #0a0a1a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before, .brand-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            animation: floatBlob 8s ease-in-out infinite;
        }

        .brand-panel::before {
            width: 350px; height: 350px;
            background: var(--pink);
            top: -80px; right: -80px;
        }

        .brand-panel::after {
            width: 300px; height: 300px;
            background: #6c63ff;
            bottom: -60px; left: -60px;
            animation-delay: 4s;
        }

        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.08); }
        }

        .brand-content { position: relative; z-index: 2; text-align: center; color: #fff; }
        .brand-logo { font-size: 3rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
        .brand-logo span { color: var(--pink); }
        .brand-tagline { font-size: 1.05rem; color: rgba(255, 255, 255, 0.55); max-width: 320px; line-height: 1.7; margin: 0 auto; }

        .steps-list { list-style: none; padding: 0; margin-top: 48px; text-align: left; position: relative; z-index: 2; }
        .steps-list li { display: flex; align-items: center; gap: 16px; padding: 12px 0; color: rgba(255, 255, 255, 0.7); font-size: 0.92rem; }
        .step-num { width: 32px; height: 32px; min-width: 32px; background: rgba(255, 59, 122, 0.2); color: var(--pink); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; }

        /* RIGHT — LOGIN PANEL */
        .login-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fafbfc;
            position: relative;
        }

        .login-wrapper { width: 100%; max-width: 420px; }
        .login-header { margin-bottom: 32px; }
        .login-header h2 { font-size: 1.8rem; font-weight: 800; color: var(--dark); margin-bottom: 8px; }
        .login-header p { color: var(--gray-600); font-size: 0.95rem; }

        /* ROLE TABS */
        .role-tabs { display: flex; background: var(--gray-100); border-radius: 14px; padding: 5px; margin-bottom: 28px; }
        .role-tab { flex: 1; text-align: center; padding: 12px; border-radius: 11px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: var(--transition); color: var(--gray-600); border: none; background: transparent; }
        .role-tab.active { background: #fff; color: var(--dark); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }

        /* INPUT GROUPS */
        .input-group-custom { position: relative; margin-bottom: 18px; }
        .input-group-custom .form-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 0.88rem; z-index: 2; transition: var(--transition); }
        .input-group-custom input { width: 100%; padding: 14px 16px 14px 46px; border: 2px solid var(--gray-200); border-radius: var(--radius); font-size: 0.93rem; font-weight: 500; transition: var(--transition); background: #fff; color: var(--dark); }
        .input-group-custom input:focus { outline: none; border-color: var(--pink); box-shadow: 0 0 0 4px var(--pink-light); }
        .input-group-custom input:focus ~ .form-icon { color: var(--pink); }

        .toggle-password { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--gray-400); cursor: pointer; z-index: 2; }

        /* BUTTON */
        .btn-login {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--pink), #ff6b9d);
            color: #fff; border: none; border-radius: var(--radius);
            font-weight: 700; font-size: 1rem; cursor: pointer; transition: var(--transition);
            position: relative; overflow: hidden;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 24px var(--pink-glow); }

        .divider { display: flex; align-items: center; gap: 16px; margin: 22px 0; color: var(--gray-400); font-size: 0.8rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--gray-200); }

        .register-link { text-align: center; font-size: 0.9rem; color: var(--gray-600); }
        .register-link a { color: var(--pink); font-weight: 600; text-decoration: none; }

        .alert-error { background: #fff5f5; border: 1px solid #fecaca; color: #dc2626; border-radius: 12px; padding: 14px 18px; font-size: 0.88rem; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; animation: shakeX 0.5s ease; }

        .back-home { position: absolute; top: 30px; right: 30px; color: var(--gray-600); text-decoration: none; font-size: 0.85rem; padding: 8px 16px; border-radius: 50px; border: 1px solid var(--gray-200); transition: var(--transition); }
        .back-home:hover { color: var(--pink); border-color: var(--pink); background: var(--pink-light); }

        @keyframes shakeX { 0%, 100% { transform: translateX(0); } 20%, 60% { transform: translateX(-8px); } 40%, 80% { transform: translateX(8px); } }
        @media (max-width: 991px) { .brand-panel { display: none; } }
    </style>
</head>

<body>

    <div class="brand-panel d-none d-lg-flex">
        <div class="brand-content">
            <div class="brand-logo">Key<span>Stone</span></div>
            <p class="brand-tagline">Welcome back to KeyStone. Access your tickets, manage events, and stay updated.</p>
            <ul class="steps-list">
                <li><span class="step-num"><i class="fas fa-check"></i></span> Log in to your secure account</li>
                <li><span class="step-num"><i class="fas fa-ticket-alt"></i></span> View your purchased tickets</li>
                <li><span class="step-num"><i class="fas fa-heart"></i></span> Access your favorite events</li>
            </ul>
        </div>
    </div>

    <div class="login-panel">
        <a href="../index.php" class="back-home"><i class="fas fa-arrow-left"></i> Home</a>

        <div class="login-wrapper">
            <div class="login-header">
                <h2>Welcome Back 👋</h2>
                <p>Sign in to your account to continue</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="role" id="roleInput" value="user">

                <div class="role-tabs">
                    <button type="button" class="role-tab active" data-role="user" onclick="switchRole('user', this)"><i class="fas fa-user"></i> User</button>
                    <button type="button" class="role-tab" data-role="admin" onclick="switchRole('admin', this)"><i class="fas fa-user-shield"></i> Admin</button>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-envelope form-icon" id="emailIcon"></i>
                    <input type="text" name="email" id="emailInput" placeholder="Email address" required>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-lock form-icon"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label style="cursor:pointer; font-size:0.85rem; color:var(--gray-600);"><input type="checkbox" class="form-check-input me-2"> Remember me</label>
                    <a href="forgot_password.php">Forgot password?</a>
                </div>

                <button type="submit" name="login" class="btn-login"><i class="fas fa-sign-in-alt me-2"></i> Sign In</button>
            </form>

            <div class="divider">or</div>
            <p class="register-link">Don't have an account? <a href="register.php">Create one for free</a></p>
        </div>
    </div>

    <script>
        function switchRole(role, btn) {
            document.getElementById('roleInput').value = role;
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');

            const emailInput = document.getElementById('emailInput');
            const emailIcon  = document.getElementById('emailIcon');

            if (role === 'admin') {
                emailInput.placeholder = 'Admin username';
                emailIcon.className = 'fas fa-user-cog form-icon';
            } else {
                emailInput.placeholder = 'Email address';
                emailIcon.className = 'fas fa-envelope form-icon';
            }
        }

        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>