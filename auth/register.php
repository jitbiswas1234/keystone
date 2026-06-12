<?php
session_start();
include("../config/database.php");

$error   = "";
$success = "";

// ─── Preserve old input on error ───
$old_name  = "";
$old_email = "";
$old_phone = "";

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Preserve old input
    $old_name  = $name;
    $old_email = $email;
    $old_phone = $phone;

    // ── Validation ──
    if (strlen($name) < 2) {
        $error = "Name must be at least 2 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // ── Check duplicate email ──
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "This email is already registered. Try logging in.";
        } else {
            // ── Insert User ──
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed);

            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — KeyStone</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════
           VARIABLES
           ══════════════════════════════════ */
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
            --green: #22c55e;
            --green-light: rgba(34, 197, 94, 0.08);
            --radius: 14px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ══════════════════════════════════
           LEFT — BRAND PANEL
           ══════════════════════════════════ */
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

        .brand-panel::before,
        .brand-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            animation: floatBlob 8s ease-in-out infinite;
        }

        .brand-panel::before {
            width: 350px;
            height: 350px;
            background: var(--pink);
            top: -80px;
            right: -80px;
        }

        .brand-panel::after {
            width: 300px;
            height: 300px;
            background: #6c63ff;
            bottom: -60px;
            left: -60px;
            animation-delay: 4s;
        }

        @keyframes floatBlob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.08); }
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
        }

        .brand-logo {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }

        .brand-logo span {
            color: var(--pink);
        }

        .brand-tagline {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.55);
            max-width: 320px;
            line-height: 1.7;
            margin: 0 auto;
        }

        /* Illustration / Stats */
        .brand-stats {
            display: flex;
            gap: 32px;
            margin-top: 48px;
            position: relative;
            z-index: 2;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--pink);
            display: block;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Steps */
        .steps-list {
            list-style: none;
            padding: 0;
            margin-top: 48px;
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .steps-list li {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.92rem;
        }

        .step-num {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: rgba(255, 59, 122, 0.2);
            color: var(--pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        /* ══════════════════════════════════
           RIGHT — REGISTER FORM
           ══════════════════════════════════ */
        .register-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #fafbfc;
            position: relative;
            overflow-y: auto;
        }

        .register-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .register-header {
            margin-bottom: 32px;
        }

        .register-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .register-header p {
            color: var(--gray-600);
            font-size: 0.95rem;
        }

        /* ── Input Groups ── */
        .input-group-custom {
            position: relative;
            margin-bottom: 18px;
        }

        .input-group-custom .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.88rem;
            transition: var(--transition);
            z-index: 2;
        }

        .input-group-custom input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.93rem;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: var(--transition);
            background: #fff;
            color: var(--dark);
        }

        .input-group-custom input::placeholder {
            color: var(--gray-400);
            font-weight: 400;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: var(--pink);
            box-shadow: 0 0 0 4px var(--pink-light);
        }

        .input-group-custom input:focus ~ .form-icon {
            color: var(--pink);
        }

        /* ── Valid / Invalid States ── */
        .input-group-custom input.is-valid-custom {
            border-color: var(--green);
        }

        .input-group-custom input.is-valid-custom:focus {
            box-shadow: 0 0 0 4px var(--green-light);
        }

        .input-group-custom input.is-valid-custom ~ .form-icon {
            color: var(--green);
        }

        /* ── Toggle Password ── */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 0.9rem;
            z-index: 2;
            transition: var(--transition);
        }

        .toggle-password:hover {
            color: var(--pink);
        }

        /* ── Password Strength ── */
        .password-strength {
            margin-top: -10px;
            margin-bottom: 18px;
            padding: 0 4px;
        }

        .strength-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 6px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            border-radius: 4px;
            background: var(--gray-200);
            transition: var(--transition);
        }

        .strength-text {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--gray-400);
            transition: var(--transition);
        }

        /* Strength levels */
        .strength-weak .strength-bar:nth-child(1) { background: #ef4444; }
        .strength-weak .strength-text { color: #ef4444; }

        .strength-fair .strength-bar:nth-child(1),
        .strength-fair .strength-bar:nth-child(2) { background: #f59e0b; }
        .strength-fair .strength-text { color: #f59e0b; }

        .strength-good .strength-bar:nth-child(1),
        .strength-good .strength-bar:nth-child(2),
        .strength-good .strength-bar:nth-child(3) { background: #3b82f6; }
        .strength-good .strength-text { color: #3b82f6; }

        .strength-strong .strength-bar { background: var(--green); }
        .strength-strong .strength-text { color: var(--green); }

        /* ── Submit Button ── */
        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--pink), #ff6b9d);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px var(--pink-glow);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 22px 0;
            color: var(--gray-400);
            font-size: 0.8rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        /* ── Login Link ── */
        .login-link {
            text-align: center;
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        .login-link a {
            color: var(--pink);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--pink-hover);
            text-decoration: underline;
        }

        /* ── Error Alert ── */
        .alert-error {
            background: #fff5f5;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shakeX 0.5s ease;
        }

        @keyframes shakeX {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        /* ── Terms Text ── */
        .terms-text {
            font-size: 0.78rem;
            color: var(--gray-400);
            text-align: center;
            margin-top: 16px;
            line-height: 1.6;
        }

        .terms-text a {
            color: var(--gray-600);
            text-decoration: underline;
        }

        /* ── Back to Home ── */
        .back-home {
            position: absolute;
            top: 30px;
            right: 30px;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid var(--gray-200);
        }

        .back-home:hover {
            color: var(--pink);
            border-color: var(--pink);
            background: var(--pink-light);
        }

        /* ── Password Match Indicator ── */
        .match-indicator {
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: -12px;
            margin-bottom: 16px;
            padding-left: 4px;
            transition: var(--transition);
        }

        .match-yes { color: var(--green); }
        .match-no  { color: #ef4444; }

        /* ══════════════════════════════════
           RESPONSIVE
           ══════════════════════════════════ */
        @media (max-width: 991px) {
            .brand-panel {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .register-panel {
                padding: 24px;
            }

            .register-header h2 {
                font-size: 1.5rem;
            }

            .back-home {
                top: 16px;
                right: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- ══════════════════════════════════
         LEFT — BRAND PANEL
         ══════════════════════════════════ -->
    <div class="brand-panel d-none d-lg-flex">
        <div class="brand-content">
            <div class="brand-logo">Key<span>Stone</span></div>
            <p class="brand-tagline">
                Join thousands of event enthusiasts. Create your account and start exploring.
            </p>

            <!-- Stats -->
            <div class="brand-stats">
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Users</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Events</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">4.8</span>
                    <span class="stat-label">Rating</span>
                </div>
            </div>

            <!-- Steps -->
            <ul class="steps-list">
                <li>
                    <span class="step-num">1</span>
                    Create your free account
                </li>
                <li>
                    <span class="step-num">2</span>
                    Browse & discover events near you
                </li>
                <li>
                    <span class="step-num">3</span>
                    Book tickets instantly & securely
                </li>
                <li>
                    <span class="step-num">4</span>
                    Enjoy unforgettable experiences
                </li>
            </ul>
        </div>
    </div>

    <!-- ══════════════════════════════════
         RIGHT — REGISTER FORM
         ══════════════════════════════════ -->
    <div class="register-panel">

        <a href="../index.php" class="back-home">
            <i class="fas fa-arrow-left"></i> Home
        </a>

        <div class="register-wrapper">

            <!-- Header -->
            <div class="register-header">
                <h2>Create Account ✨</h2>
                <p>Fill in your details to get started</p>
            </div>

            <!-- Error -->
            <?php if (!empty($error)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="" id="registerForm">

                <!-- Full Name -->
                <div class="input-group-custom">
                    <i class="fas fa-user form-icon"></i>
                    <input
                        type="text"
                        name="name"
                        id="nameInput"
                        placeholder="Full name"
                        value="<?php echo htmlspecialchars($old_name); ?>"
                        required
                        autocomplete="name"
                    >
                </div>

                <!-- Email -->
                <div class="input-group-custom">
                    <i class="fas fa-envelope form-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="emailInput"
                        placeholder="Email address"
                        value="<?php echo htmlspecialchars($old_email); ?>"
                        required
                        autocomplete="email"
                    >
                </div>

                <!-- Phone -->
                <div class="input-group-custom">
                    <i class="fas fa-phone form-icon"></i>
                    <input
                        type="tel"
                        name="phone"
                        id="phoneInput"
                        placeholder="Phone number (optional)"
                        value="<?php echo htmlspecialchars($old_phone); ?>"
                        autocomplete="tel"
                    >
                </div>

                <!-- Password -->
                <div class="input-group-custom">
                    <i class="fas fa-lock form-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        placeholder="Create password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword('passwordInput', 'eyeIcon1')">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>

                <!-- Password Strength -->
                <div class="password-strength" id="strengthWrapper" style="display:none;">
                    <div class="strength-bars">
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                    </div>
                    <span class="strength-text" id="strengthText"></span>
                </div>

                <!-- Confirm Password -->
                <div class="input-group-custom">
                    <i class="fas fa-lock form-icon"></i>
                    <input
                        type="password"
                        name="confirm_password"
                        id="confirmInput"
                        placeholder="Confirm password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword('confirmInput', 'eyeIcon2')">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>

                <!-- Match Indicator -->
                <div class="match-indicator" id="matchIndicator" style="display:none;"></div>

                <!-- Submit -->
                <button type="submit" name="register" class="btn-register" id="submitBtn">
                    <i class="fas fa-user-plus me-2"></i>
                    Create Account
                </button>
            </form>

            <!-- Terms -->
            <p class="terms-text">
                By creating an account, you agree to our
                <a href="#">Terms of Service</a> and
                <a href="#">Privacy Policy</a>.
            </p>

            <!-- Divider -->
            <div class="divider">or</div>

            <!-- Login Link -->
            <p class="login-link">
                Already have an account?
                <a href="login.php">Sign in here</a>
            </p>
        </div>
    </div>

    <!-- ══════════════════════════════════
         JAVASCRIPT
         ══════════════════════════════════ -->
    <script>
        // ── Toggle Password Visibility ──
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ── Password Strength Meter ──
        const passwordInput  = document.getElementById('passwordInput');
        const strengthWrap   = document.getElementById('strengthWrapper');
        const strengthText   = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function () {
            const val = this.value;

            if (val.length === 0) {
                strengthWrap.style.display = 'none';
                return;
            }

            strengthWrap.style.display = 'block';

            let score = 0;
            if (val.length >= 6)                score++;
            if (val.length >= 10)               score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val))              score++;
            if (/[^A-Za-z0-9]/.test(val))       score++;

            // Normalize to 4 levels
            let level, text;
            if (score <= 1)      { level = 'weak';   text = 'Weak — add more characters'; }
            else if (score <= 2) { level = 'fair';   text = 'Fair — try adding numbers'; }
            else if (score <= 3) { level = 'good';   text = 'Good — almost there'; }
            else                 { level = 'strong'; text = 'Strong — excellent!'; }

            // Remove old classes
            strengthWrap.className = 'password-strength strength-' + level;
            strengthText.textContent = text;

            // Also check match
            checkMatch();
        });

        // ── Password Match Check ──
        const confirmInput    = document.getElementById('confirmInput');
        const matchIndicator  = document.getElementById('matchIndicator');

        confirmInput.addEventListener('input', checkMatch);

        function checkMatch() {
            const pw  = passwordInput.value;
            const cpw = confirmInput.value;

            if (cpw.length === 0) {
                matchIndicator.style.display = 'none';
                return;
            }

            matchIndicator.style.display = 'block';

            if (pw === cpw) {
                matchIndicator.className = 'match-indicator match-yes';
                matchIndicator.innerHTML = '<i class="fas fa-check-circle me-1"></i> Passwords match';
            } else {
                matchIndicator.className = 'match-indicator match-no';
                matchIndicator.innerHTML = '<i class="fas fa-times-circle me-1"></i> Passwords do not match';
            }
        }

        // ── Live Validation Feedback (optional visual) ──
        const nameInput  = document.getElementById('nameInput');
        const emailInput = document.getElementById('emailInput');

        nameInput.addEventListener('blur', function () {
            if (this.value.trim().length >= 2) {
                this.classList.add('is-valid-custom');
            } else {
                this.classList.remove('is-valid-custom');
            }
        });

        emailInput.addEventListener('blur', function () {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value.trim())) {
                this.classList.add('is-valid-custom');
            } else {
                this.classList.remove('is-valid-custom');
            }
        });
    </script>

</body>
</html>