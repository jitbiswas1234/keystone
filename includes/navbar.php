<?php

if(!isset($base_url)){

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
? "https://" 
: "http://";

$host = $_SERVER['HTTP_HOST'];

/* project folder detection */
$project_folder = explode('/', trim($_SERVER['SCRIPT_NAME'],'/'))[0];

$base_url = $protocol.$host.'/'.$project_folder.'/';

}

?>
<style>
    /* ========== NAVBAR STYLES ========== */
    .ks-navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        padding: 15px 0;
        transition: all 0.4s ease;
        background: transparent;
    }

    .ks-navbar.scrolled {
        background: rgba(10, 10, 26, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 10px 0;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .ks-navbar .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ========== BRAND LOGO ========== */
    .ks-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        position: relative;
        z-index: 10001;
    }

    .ks-brand-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #ff3b7a, #e0356c);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        color: white;
        box-shadow: 0 5px 15px rgba(255, 59, 122, 0.3);
        transition: transform 0.3s ease;
    }

    .ks-brand:hover .ks-brand-icon {
        transform: rotate(10deg) scale(1.1);
    }

    .ks-brand-text {
        font-size: 24px;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.5px;
    }

    .ks-brand-text span {
        background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ========== NAV LINKS ========== */
    .ks-nav-links {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .ks-nav-item {
        position: relative;
    }

    .ks-nav-link {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        padding: 10px 18px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .ks-nav-link:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
    }

    .ks-nav-link.active {
        color: #ff3b7a;
        background: rgba(255, 59, 122, 0.1);
    }

    .ks-nav-link i {
        font-size: 16px;
    }

    /* Active Indicator Dot */
    .ks-nav-link.active::after {
        content: "";
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: #ff3b7a;
        border-radius: 50%;
    }

    /* ========== NOTIFICATION BADGE ========== */
    .ks-notification-dot {
        width: 8px;
        height: 8px;
        background: #ff3b7a;
        border-radius: 50%;
        position: absolute;
        top: 8px;
        right: 12px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.3); }
    }

    /* ========== AUTH BUTTONS ========== */
    .ks-btn-login {
        background: transparent;
        border: 2px solid rgba(255, 59, 122, 0.5);
        color: #ff3b7a;
        padding: 10px 28px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .ks-btn-login:hover {
        background: rgba(255, 59, 122, 0.1);
        border-color: #ff3b7a;
        color: #ff3b7a;
        transform: translateY(-2px);
    }

    .ks-btn-register {
        background: linear-gradient(135deg, #ff3b7a, #e0356c);
        border: none;
        color: white;
        padding: 10px 28px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255, 59, 122, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .ks-btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 59, 122, 0.5);
        color: white;
    }

    .ks-btn-logout {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #f87171;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .ks-btn-logout:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    /* ========== DIVIDER ========== */
    .ks-nav-divider {
        width: 1px;
        height: 25px;
        background: rgba(255, 255, 255, 0.1);
        margin: 0 8px;
    }

    /* ========== HAMBURGER ========== */
    .ks-hamburger {
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        cursor: pointer;
        gap: 5px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 10001;
    }

    .ks-hamburger:hover {
        background: rgba(255, 59, 122, 0.1);
        border-color: rgba(255, 59, 122, 0.3);
    }

    .ks-hamburger span {
        width: 20px;
        height: 2px;
        background: #ffffff;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .ks-hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .ks-hamburger.active span:nth-child(2) {
        opacity: 0;
        transform: translateX(10px);
    }

    .ks-hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    /* ========== MOBILE MENU ========== */
    @media (max-width: 992px) {
        .ks-hamburger {
            display: flex;
        }

        .ks-nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 320px;
            height: 100vh;
            background: linear-gradient(135deg, #1a0b2e 0%, #0a0a1a 100%);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            padding: 100px 30px 30px;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10000;
            overflow-y: auto;
            box-shadow: -20px 0 60px rgba(0, 0, 0, 0.5);
        }

        .ks-nav-menu.active {
            right: 0;
        }

        .ks-nav-links {
            flex-direction: column;
            align-items: stretch;
            gap: 5px;
        }

        .ks-nav-link {
            padding: 14px 18px;
            font-size: 16px;
            border-radius: 15px;
        }

        .ks-nav-link.active::after {
            display: none;
        }

        .ks-nav-divider {
            width: 100%;
            height: 1px;
            margin: 15px 0;
        }

        .ks-btn-login,
        .ks-btn-register,
        .ks-btn-logout {
            width: 100%;
            justify-content: center;
            padding: 14px 28px;
            font-size: 15px;
        }

        /* Mobile Overlay */
        .ks-mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
        }

        .ks-mobile-overlay.active {
            display: block;
        }

        /* Mobile User Card */
        .ks-mobile-user {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .ks-mobile-user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ff3b7a, #e0356c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            color: white;
        }

        .ks-mobile-user-info h6 {
            color: #ffffff;
            font-weight: 600;
            margin: 0 0 3px;
        }

        .ks-mobile-user-info small {
            color: #7a7a9e;
            font-size: 13px;
        }
    }

    @media (min-width: 993px) {
        .ks-mobile-user {
            display: none !important;
        }
    }
</style>

<!-- NAVBAR -->
<nav class="ks-navbar" id="ksNavbar">
    <div class="container">
        <!-- Brand Logo -->
        <a href="<?php echo $base_url; ?>index.php" class="ks-brand">
            <div class="ks-brand-icon">K</div>
            <div class="ks-brand-text">Key<span>Stone</span></div>
        </a>

        <!-- Hamburger Menu (Mobile) -->
        <div class="ks-hamburger" id="ksHamburger" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Navigation Menu -->
        <div class="ks-nav-menu" id="ksNavMenu">

            <?php if(isset($_SESSION['admin_id'])): ?>
                <!-- ======= ADMIN MENU ======= -->
                <div class="ks-mobile-user">
                    <div class="ks-mobile-user-avatar">A</div>
                    <div class="ks-mobile-user-info">
                        <h6>Admin Panel</h6>
                        <small><i class="fas fa-shield-alt me-1"></i>Administrator</small>
                    </div>
                </div>

                <ul class="ks-nav-links">
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>admin/dashboard.php" class="ks-nav-link">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>admin/manage_events.php" class="ks-nav-link">
                            <i class="fas fa-calendar-alt"></i> Manage Events
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>admin/manage_bookings.php" class="ks-nav-link">
                            <i class="fas fa-ticket-alt"></i> Bookings
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>admin/manage_users.php" class="ks-nav-link">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <div class="ks-nav-divider"></div>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>auth/logout.php" class="ks-btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>

            <?php elseif(isset($_SESSION['user_id'])): ?>
                <!-- ======= USER MENU ======= -->
                <div class="ks-mobile-user">
                    <div class="ks-mobile-user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                    </div>
                    <div class="ks-mobile-user-info">
                        <h6><?php echo $_SESSION['user_name'] ?? 'User'; ?></h6>
                        <small><i class="fas fa-circle text-success me-1" style="font-size:8px;"></i> Online</small>
                    </div>
                </div>

                <ul class="ks-nav-links">
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>index.php" class="ks-nav-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>user/events.php" class="ks-nav-link">
                            <i class="fas fa-calendar-alt"></i> Events
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>user/my_bookings.php" class="ks-nav-link">
                            <i class="fas fa-ticket-alt"></i> My Bookings
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>user/profile.php" class="ks-nav-link">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>contact.php" class="ks-nav-link">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                    <div class="ks-nav-divider"></div>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>auth/logout.php" class="ks-btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>

            <?php else: ?>
                <!-- ======= GUEST MENU ======= -->
                <ul class="ks-nav-links">
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>index.php" class="ks-nav-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>user/events.php" class="ks-nav-link">
                            <i class="fas fa-calendar-alt"></i> Events
                        </a>
                    </li>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>contact.php" class="ks-nav-link">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>
                    <div class="ks-nav-divider"></div>
                    <li class="ks-nav-item">
                        <a href="<?php echo $base_url; ?>auth/login.php" class="ks-btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="ks-nav-item" style="margin-top: 5px;">
                        <a href="<?php echo $base_url; ?>auth/register.php" class="ks-btn-register">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="ks-mobile-overlay" id="ksMobileOverlay" onclick="closeMobileMenu()"></div>
</nav>

<!-- Navbar Spacer -->
<div style="height: 75px;"></div>

<script>
    // ========== SCROLL EFFECT ==========
    const navbar = document.getElementById('ksNavbar');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Trigger on load
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    }

    // ========== MOBILE MENU ==========
    function toggleMobileMenu() {
        const hamburger = document.getElementById('ksHamburger');
        const navMenu = document.getElementById('ksNavMenu');
        const overlay = document.getElementById('ksMobileOverlay');

        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        overlay.classList.toggle('active');

        if (navMenu.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    function closeMobileMenu() {
        const hamburger = document.getElementById('ksHamburger');
        const navMenu = document.getElementById('ksNavMenu');
        const overlay = document.getElementById('ksMobileOverlay');

        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close menu on link click (mobile)
    document.querySelectorAll('.ks-nav-link, .ks-btn-login, .ks-btn-register, .ks-btn-logout').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                closeMobileMenu();
            }
        });
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // ========== ACTIVE LINK HIGHLIGHTING ==========
    const currentPath = window.location.pathname;
    document.querySelectorAll('.ks-nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.split('/').pop().replace('.php', ''))) {
            link.classList.add('active');
        }
    });
</script>