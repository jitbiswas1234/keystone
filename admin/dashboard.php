<?php

include("../includes/header.php");
include("../config/database.php");

if(!isset($_SESSION['admin_id']))
{
header("Location:../auth/login.php");
exit();
}

/* Total revenue */

$revenue=mysqli_query($conn,

"SELECT COALESCE(SUM(total_price),0) as total 
FROM bookings 
WHERE payment_status='Paid'");

$rev=mysqli_fetch_assoc($revenue);

/* Total bookings */

$bookings=mysqli_query($conn,

"SELECT COUNT(*) as total FROM bookings");

$book=mysqli_fetch_assoc($bookings);

/* Total users */

$users=mysqli_query($conn,

"SELECT COUNT(*) as total FROM users");

$user=mysqli_fetch_assoc($users);

/* Total events */

$events=mysqli_query($conn,

"SELECT COUNT(*) as total FROM events");

$event=mysqli_fetch_assoc($events);

/* Popular event */

$popular=mysqli_query($conn,

"SELECT events.title,COUNT(bookings.event_id) as total

FROM bookings

JOIN events ON bookings.event_id=events.id

GROUP BY bookings.event_id

ORDER BY total DESC

LIMIT 1");

$pop=mysqli_fetch_assoc($popular);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EventPro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            color: #1a1a2e;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.5);
        }

        .sidebar-logo-text {
            font-size: 22px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
        }

        .sidebar-nav {
            padding: 20px 16px;
        }

        .nav-section {
            margin-bottom: 28px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 12px;
            margin-bottom: 12px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.5);
        }

        .nav-link i {
            width: 20px;
            font-size: 16px;
            text-align: center;
        }

        .nav-link .badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .nav-link.active .badge {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 20px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, transparent, #1e1b4b);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .admin-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .admin-info h4 {
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-info p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }

        .admin-menu-btn {
            margin-left: auto;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .admin-menu-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            flex: 1;
            margin-left: 270px;
            min-height: 100vh;
        }

        /* ========== TOP HEADER ========== */
        .top-header {
            background: white;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-left h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .header-left p {
            color: #64748b;
            font-size: 14px;
        }

        .header-left p span {
            color: #6366f1;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            width: 280px;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .header-search input:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .header-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-icon-btn {
            width: 44px;
            height: 44px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .header-icon-btn:hover {
            background: #f8fafc;
            border-color: #6366f1;
            color: #6366f1;
        }

        .header-icon-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* ========== DASHBOARD CONTENT ========== */
        .dashboard-content {
            padding: 32px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.revenue::before {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .stat-card.bookings::before {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
        }

        .stat-card.users::before {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .stat-card.events::before {
            background: linear-gradient(90deg, #ec4899, #f472b6);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .stat-card-inner {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a2e;
            letter-spacing: -1px;
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .stat-change.positive {
            background: #ecfdf5;
            color: #059669;
        }

        .stat-change.neutral {
            background: #f1f5f9;
            color: #64748b;
        }

        .stat-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-icon-box.revenue {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(16, 185, 129, 0.5);
        }

        .stat-icon-box.bookings {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.5);
        }

        .stat-icon-box.users {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(245, 158, 11, 0.5);
        }

        .stat-icon-box.events {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(236, 72, 153, 0.5);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Card Base */
        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            font-size: 18px;
            color: #6366f1;
        }

        .card-body {
            padding: 24px;
        }

        /* Popular Event Card */
        .popular-event-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .popular-event-card::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .popular-event-card::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .popular-event-content {
            position: relative;
            z-index: 1;
            padding: 28px;
            color: white;
        }

        .popular-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .popular-badge i {
            color: #fbbf24;
        }

        .popular-event-title {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .popular-stats {
            display: flex;
            gap: 32px;
        }

        .popular-stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .popular-stat-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .popular-stat-info .number {
            font-size: 22px;
            font-weight: 800;
            display: block;
            line-height: 1;
        }

        .popular-stat-info .label {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 4px;
        }

        .no-popular-event {
            text-align: center;
            padding: 40px 20px;
        }

        .no-popular-event i {
            font-size: 48px;
            opacity: 0.3;
            margin-bottom: 16px;
        }

        .no-popular-event p {
            opacity: 0.7;
            font-size: 15px;
        }

        /* Quick Actions Card */
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #1a1a2e;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .quick-action-item:hover {
            background: white;
            border-color: #6366f1;
            transform: translateX(6px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .quick-action-icon.purple {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
        }

        .quick-action-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .quick-action-icon.orange {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .quick-action-text {
            flex: 1;
        }

        .quick-action-text h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 2px;
        }

        .quick-action-text p {
            font-size: 12px;
            color: #64748b;
        }

        .quick-action-arrow {
            color: #cbd5e1;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .quick-action-item:hover .quick-action-arrow {
            color: #6366f1;
            transform: translateX(4px);
        }

        /* Summary Row */
        .summary-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .summary-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
        }

        .summary-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .summary-icon.gradient-1 {
            background: linear-gradient(135deg, #ddd6fe, #c4b5fd);
            color: #7c3aed;
        }

        .summary-icon.gradient-2 {
            background: linear-gradient(135deg, #a7f3d0, #6ee7b7);
            color: #059669;
        }

        .summary-icon.gradient-3 {
            background: linear-gradient(135deg, #fde68a, #fcd34d);
            color: #d97706;
        }

        .summary-content h3 {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .summary-content p {
            font-size: 13px;
            color: #64748b;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .summary-row {
                grid-template-columns: 1fr;
            }

            .header-search {
                display: none;
            }
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: flex !important;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-content {
                padding: 20px;
            }

            .top-header {
                padding: 16px 20px;
            }

            .stat-value {
                font-size: 26px;
            }

            .popular-event-title {
                font-size: 20px;
            }

            .popular-stats {
                flex-direction: column;
                gap: 16px;
            }
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <div class="dashboard-layout">
        <!-- ========== SIDEBAR ========== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="sidebar-logo-text">EventPro</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Menu</div>
                    <ul class="nav-menu">
                        <li>
                            <a href="dashboard.php" class="nav-link active">
                                <i class="fas fa-th-large"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_events.php" class="nav-link">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Events</span>
                                <span class="badge"><?php echo $event['total']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_bookings.php" class="nav-link">
                                <i class="fas fa-ticket-alt"></i>
                                <span>Bookings</span>
                                <span class="badge"><?php echo $book['total']; ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_users.php" class="nav-link">
                                <i class="fas fa-users"></i>
                                <span>Users</span>
                                <span class="badge"><?php echo $user['total']; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <ul class="nav-menu">
                        <li>
                            <a href="create_event.php" class="nav-link">
                                <i class="fas fa-plus-circle"></i>
                                <span>Create Event</span>
                            </a>
                        </li>
                        <li>
                            <a href="contacts.php" class="nav-link">
                                <i class="fas fa-chart-bar"></i>
                                <span>Enquiries</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <ul class="nav-menu">
                        <li>
                            <a href="settings.php" class="nav-link">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="../auth/logout.php" class="nav-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

        </aside>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left" style="display: flex; align-items: center;">
                    <button class="mobile-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1>Dashboard</h1>
                        <p>Welcome back! Here's what's happening <span>today</span></p>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search anything...">
                    </div>
                    <button class="header-icon-btn">
                        <i class="far fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <button class="header-icon-btn">
                        <i class="far fa-envelope"></i>
                    </button>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card revenue">
                        <div class="stat-card-inner">
                            <div class="stat-content">
                                <div class="stat-label">Total Revenue</div>
                                <div class="stat-value">₹<?php echo number_format($rev['total']); ?></div>
                                <span class="stat-change positive">
                                    <i class="fas fa-check-circle"></i> Paid
                                </span>
                            </div>
                            <div class="stat-icon-box revenue">
                                <i class="fas fa-indian-rupee-sign"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bookings">
                        <div class="stat-card-inner">
                            <div class="stat-content">
                                <div class="stat-label">Total Bookings</div>
                                <div class="stat-value"><?php echo $book['total']; ?></div>
                                <span class="stat-change neutral">
                                    <i class="fas fa-ticket-alt"></i> All time
                                </span>
                            </div>
                            <div class="stat-icon-box bookings">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card users">
                        <div class="stat-card-inner">
                            <div class="stat-content">
                                <div class="stat-label">Total Users</div>
                                <div class="stat-value"><?php echo $user['total']; ?></div>
                                <span class="stat-change positive">
                                    <i class="fas fa-user-check"></i> Registered
                                </span>
                            </div>
                            <div class="stat-icon-box users">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card events">
                        <div class="stat-card-inner">
                            <div class="stat-content">
                                <div class="stat-label">Total Events</div>
                                <div class="stat-value"><?php echo $event['total']; ?></div>
                                <span class="stat-change neutral">
                                    <i class="fas fa-calendar-check"></i> Created
                                </span>
                            </div>
                            <div class="stat-icon-box events">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Popular Event -->
                    <div class="card popular-event-card">
                        <div class="popular-event-content">
                            <div class="popular-badge">
                                <i class="fas fa-trophy"></i>
                                Most Popular Event
                            </div>

                            <?php if($pop){ ?>
                                <h2 class="popular-event-title"><?php echo $pop['title']; ?></h2>
                                <div class="popular-stats">
                                    <div class="popular-stat-item">
                                        <div class="popular-stat-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="popular-stat-info">
                                            <span class="number"><?php echo $pop['total']; ?></span>
                                            <span class="label">Total Bookings</span>
                                        </div>
                                    </div>
                                    <div class="popular-stat-item">
                                        <div class="popular-stat-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="popular-stat-info">
                                            <span class="number">#1</span>
                                            <span class="label">Top Ranked</span>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="no-popular-event">
                                    <i class="fas fa-chart-bar"></i>
                                    <p>No bookings yet. Your most popular event will appear here.</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="quick-actions">
                                <a href="manage_events.php" class="quick-action-item">
                                    <div class="quick-action-icon purple">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="quick-action-text">
                                        <h4>Manage Events</h4>
                                        <p>View, create and edit events</p>
                                    </div>
                                    <i class="fas fa-chevron-right quick-action-arrow"></i>
                                </a>

                                <a href="manage_bookings.php" class="quick-action-item">
                                    <div class="quick-action-icon green">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="quick-action-text">
                                        <h4>Manage Bookings</h4>
                                        <p>View all reservations</p>
                                    </div>
                                    <i class="fas fa-chevron-right quick-action-arrow"></i>
                                </a>

                                <a href="manage_users.php" class="quick-action-item">
                                    <div class="quick-action-icon orange">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="quick-action-text">
                                        <h4>Manage Users</h4>
                                        <p>View registered users</p>
                                    </div>
                                    <i class="fas fa-chevron-right quick-action-arrow"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Row -->
                <div class="summary-row">
                    <div class="summary-card">
                        <div class="summary-icon gradient-1">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo $event['total']; ?> Events</h3>
                            <p>Total events created</p>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon gradient-2">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="summary-content">
                            <h3>₹<?php echo number_format($rev['total']); ?></h3>
                            <p>Revenue generated</p>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon gradient-3">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo $user['total']; ?> Users</h3>
                            <p>Registered customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    </script>

</body>
</html>

<?php include("../includes/footer.php"); ?>