<?php
include("includes/header.php");
include("config/database.php");
include("includes/navbar.php");

$search = "";
$category = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

$sql = "SELECT * FROM events WHERE 1=1"; 

if ($search != "") {
    $sql .= " AND (title LIKE '%$search%' OR location LIKE '%$search%')";
}

if ($category != "") {
    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY event_date DESC LIMIT 6";
$result = mysqli_query($conn, $sql);

// Get stats
$total_events = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM events"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings"))['total'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventPro - Premium Event Experiences</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ========== GLOBAL STYLES ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0a0a1a;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* ========== HERO SECTION WITH VIDEO ========== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 120px 20px 80px;
        }

        /* Video Background */
        .hero-video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        /* Video Overlay */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            background: linear-gradient(
                135deg,
                rgba(58, 11, 85, 0.9) 0%,
                rgba(27, 0, 51, 0.92) 50%,
                rgba(10, 10, 26, 0.95) 100%
            );
        }

        /* Floating Particles */
        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 3;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 59, 122, 0.6);
            border-radius: 50%;
            animation: floatParticle 15s infinite linear;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 20s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 18s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 22s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 1s; animation-duration: 19s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 3s; animation-duration: 21s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; animation-duration: 17s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 2.5s; animation-duration: 23s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; animation-duration: 20s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 4.5s; animation-duration: 18s; }
        .particle:nth-child(10) { left: 15%; animation-delay: 3.5s; animation-duration: 22s; }
        .particle:nth-child(11) { left: 25%; animation-delay: 0.5s; animation-duration: 19s; }
        .particle:nth-child(12) { left: 75%; animation-delay: 2.2s; animation-duration: 21s; }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Grid Pattern */
        .hero-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 3;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* Hero Content */
        .hero-content {
            max-width: 900px;
            position: relative;
            z-index: 10;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 59, 122, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 59, 122, 0.3);
            color: #ff3b7a;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            letter-spacing: 1px;
            animation: fadeInUp 1s ease-out 0.1s backwards;
        }

        .hero-badge .live-dot {
            width: 10px;
            height: 10px;
            background: #ff3b7a;
            border-radius: 50%;
            animation: pulseDot 2s ease-in-out infinite;
            box-shadow: 0 0 10px #ff3b7a;
        }

        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }

        .hero h1 {
            font-size: 78px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(135deg, #ffffff 0%, #e0e0ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 1s ease-out 0.2s backwards;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .hero h1 span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #ff3b7a, transparent);
            border-radius: 2px;
        }

        .hero p {
            color: #c8c8e6;
            font-size: 20px;
            line-height: 1.8;
            margin-bottom: 45px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 1s ease-out 0.3s backwards;
        }

        .hero-btn {
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.4s backwards;
        }

        .btn-main {
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            border: none;
            padding: 18px 50px;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .btn-main::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.6s ease;
        }

        .btn-main:hover::before {
            left: 100%;
        }

        .btn-main:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 25px 60px rgba(255, 59, 122, 0.5);
            color: white;
        }

        .btn-outline {
            border: 2px solid rgba(255, 59, 122, 0.5);
            background: rgba(255, 59, 122, 0.08);
            backdrop-filter: blur(10px);
            color: #ff3b7a;
            padding: 16px 45px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .btn-outline:hover {
            background: #ff3b7a;
            border-color: #ff3b7a;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(255, 59, 122, 0.35);
        }

        /* Play Video Button */
        .btn-play-video {
            display: flex;
            align-items: center;
            gap: 18px;
            cursor: pointer;
            animation: fadeInUp 1s ease-out 0.5s backwards;
            margin-top: 40px;
            justify-content: center;
        }

        .play-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            transition: all 0.4s ease;
            position: relative;
        }

        .play-icon::before {
            content: '';
            position: absolute;
            inset: -10px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: playPulse 2s ease-in-out infinite;
        }

        @keyframes playPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0; }
        }

        .play-icon:hover {
            background: rgba(255, 59, 122, 0.9);
            border-color: #ff3b7a;
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.4);
        }

        .play-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(12px); }
        }

        .scroll-indicator span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
        }

        .scroll-indicator .mouse {
            width: 28px;
            height: 44px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            position: relative;
        }

        .scroll-indicator .mouse::before {
            content: '';
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 10px;
            background: #ff3b7a;
            border-radius: 3px;
            animation: scrollWheel 2s infinite;
        }

        @keyframes scrollWheel {
            0% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(14px); }
        }

        /* Video Modal */
        .video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }

        .video-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .video-modal-content {
            width: 90%;
            max-width: 1000px;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.4s ease;
        }

        .video-modal.active .video-modal-content {
            transform: scale(1);
        }

        .video-modal-close {
            position: absolute;
            top: -55px;
            right: 0;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-modal-close:hover {
            background: #ff3b7a;
            border-color: #ff3b7a;
            transform: rotate(90deg);
        }

        .video-modal iframe,
        .video-modal video {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 20px;
            border: none;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
        }

        /* ========== STATS SECTION ========== */
        .stats-section {
            background: linear-gradient(180deg, #0a0a1a 0%, #1a0b2e 100%);
            padding: 100px 20px;
            position: relative;
            border-top: 1px solid rgba(255, 59, 122, 0.1);
            border-bottom: 1px solid rgba(255, 59, 122, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item {
            text-align: center;
            position: relative;
            padding: 35px 25px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s ease;
        }

        .stat-item:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 59, 122, 0.4);
            background: rgba(255, 59, 122, 0.08);
            box-shadow: 0 20px 50px rgba(255, 59, 122, 0.15);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.2));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            font-size: 28px;
            color: #ff3b7a;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-label {
            font-size: 16px;
            color: #b8b8d1;
            font-weight: 500;
        }

        /* ========== BENTO GRID SECTION ========== */
        .bento-section {
            background: linear-gradient(180deg, #1a0b2e 0%, #0a0a1a 100%);
            padding: 100px 20px;
            position: relative;
            overflow: visible;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ff3b7a;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 18px;
        }

        .section-tag::before {
            content: '●';
            font-size: 10px;
        }

        .section-header h2 {
            font-size: 48px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 18px;
            line-height: 1.2;
        }

        .section-header p {
            color: #b8b8d1;
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .bento-container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: minmax(200px, auto);
            gap: 24px;
        }

        .bento-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 32px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
        }

        .bento-card:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 59, 122, 0.5);
            box-shadow: 0 30px 70px rgba(255, 59, 122, 0.2);
        }

        .bento-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff3b7a, #6c5cff);
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }

        .bento-card:hover::before {
            transform: scaleX(1);
        }

        /* Grid Spans */
        .bento-card-1 {
            grid-column: span 6;
            grid-row: span 2;
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.15), rgba(108, 92, 255, 0.15));
            border: 1px solid rgba(255, 59, 122, 0.3);
        }

        .bento-card-2 { grid-column: span 3; }
        .bento-card-3 { grid-column: span 3; }
        .bento-card-4 { grid-column: span 3; }
        .bento-card-5 { grid-column: span 3; }
        .bento-card-6 { grid-column: span 4; }
        .bento-card-7 { grid-column: span 4; }
        .bento-card-8 { grid-column: span 4; }

        .bento-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.2));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #ff3b7a;
            margin-bottom: 22px;
            flex-shrink: 0;
        }

        .bento-card-1 .bento-icon {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .bento-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .bento-card-1 .bento-title {
            font-size: 28px;
        }

        .bento-desc {
            font-size: 15px;
            color: #b8b8d1;
            line-height: 1.7;
            flex: 1;
        }

        .bento-card-1 .bento-desc {
            font-size: 17px;
        }

        .bento-stat {
            margin-top: auto;
            padding-top: 22px;
        }

        .bento-stat-number {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .bento-card-1 .bento-stat-number {
            font-size: 48px;
            background: linear-gradient(135deg, #ffffff, #e0e0ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bento-stat-label {
            font-size: 14px;
            color: #b8b8d1;
            margin-top: 5px;
        }

        /* ========== EVENTS SECTION ========== */
        .events-section {
            background: linear-gradient(180deg, #0a0a1a 0%, #1a0b2e 100%);
            padding: 100px 20px;
            position: relative;
        }

        .events-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100px;
            background: linear-gradient(180deg, transparent, #ff3b7a, transparent);
        }

        .view-all-btn {
            background: transparent;
            border: 2px solid rgba(255, 59, 122, 0.4);
            color: #ff3b7a;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .view-all-btn:hover {
            background: rgba(255, 59, 122, 0.15);
            border-color: #ff3b7a;
            transform: translateY(-3px);
            color: #ff3b7a;
            box-shadow: 0 10px 30px rgba(255, 59, 122, 0.2);
        }

        /* ========== EVENT CARDS ========== */
        .event-card-modern {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            position: relative;
        }

        .event-card-modern::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff3b7a, #6c5cff);
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }

        .event-card-modern:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 70px rgba(255, 59, 122, 0.25);
            border-color: rgba(255, 59, 122, 0.4);
        }

        .event-card-modern:hover::before {
            transform: scaleX(1);
        }

        .img-zoom-container {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: linear-gradient(135deg, #2a1a3e, #1a0b2e);
        }

        .modern-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            filter: brightness(0.9);
        }

        .event-card-modern:hover .modern-img {
            transform: scale(1.15) rotate(2deg);
            filter: brightness(1.1);
        }

        .price-glass {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(15px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            padding: 12px 20px;
            border-radius: 16px;
            color: #ffffff;
            font-weight: 800;
            font-size: 17px;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .event-favorite {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .event-favorite:hover {
            background: #ff3b7a;
            border-color: #ff3b7a;
            transform: scale(1.1);
        }

        .event-favorite.active {
            background: #ff3b7a;
            border-color: #ff3b7a;
        }

        .badge-modern {
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.2));
            border: 1px solid rgba(255, 59, 122, 0.3);
            color: #ff6b9d;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-body {
            padding: 28px !important;
        }

        .card-body h4 {
            color: #ffffff;
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .event-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .seats-available {
            color: #4ade80;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .seats-available::before {
            content: "●";
            font-size: 10px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .event-location {
            color: #b8b8d1;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-location i {
            color: #ff3b7a;
        }

        .btn-modern-primary {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            color: #ffffff;
            text-align: center;
            padding: 16px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-modern-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.5s ease;
        }

        .btn-modern-primary:hover::before {
            left: 100%;
        }

        .btn-modern-primary:hover {
            background: linear-gradient(135deg, #ff1f65, #ff3b7a);
            color: #ffffff;
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.4);
            transform: translateY(-3px);
        }

        .reveal-card {
            opacity: 0;
            animation: revealCard 0.8s ease forwards;
        }

        @keyframes revealCard {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .empty-state i {
            font-size: 60px;
            color: #4a4a6a;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #b8b8d1;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #7a7a9e;
        }

        /* ========== TESTIMONIALS SECTION ========== */
        .testimonials-section {
            background: linear-gradient(180deg, #1a0b2e 0%, #0a0a1a 100%);
            padding: 100px 20px;
            position: relative;
            overflow: hidden;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 35px;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 59, 122, 0.4);
            box-shadow: 0 25px 60px rgba(255, 59, 122, 0.15);
        }

        .testimonial-quote {
            font-size: 52px;
            background: linear-gradient(135deg, #ff3b7a, #6c5cff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 18px;
            line-height: 1;
        }

        .testimonial-text {
            font-size: 16px;
            color: #d6d6f2;
            line-height: 1.8;
            margin-bottom: 28px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .testimonial-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff3b7a, #6c5cff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .testimonial-author-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .testimonial-author-info p {
            font-size: 14px;
            color: #b8b8d1;
            margin: 0;
        }

        .testimonial-rating {
            margin-left: auto;
            display: flex;
            gap: 4px;
        }

        .testimonial-rating i {
            color: #fbbf24;
            font-size: 14px;
        }

        /* ========== PRICING SECTION ========== */
        .pricing-section {
            background: linear-gradient(180deg, #0a0a1a 0%, #1a0b2e 100%);
            padding: 100px 20px;
            position: relative;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1150px;
            margin: 0 auto;
            align-items: start;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            padding: 45px 35px;
            text-align: center;
            transition: all 0.5s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-12px);
            border-color: rgba(255, 59, 122, 0.4);
            box-shadow: 0 30px 70px rgba(255, 59, 122, 0.15);
        }

        .pricing-card.featured {
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.15), rgba(108, 92, 255, 0.15));
            border: 2px solid rgba(255, 59, 122, 0.5);
            transform: scale(1.05);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-12px);
        }

        .pricing-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            color: #ffffff;
            padding: 10px 28px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 30px rgba(255, 59, 122, 0.3);
        }

        .pricing-icon {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.2));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 30px;
            color: #ff3b7a;
        }

        .pricing-name {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
        }

        .pricing-price {
            margin-bottom: 28px;
        }

        .pricing-amount {
            font-size: 52px;
            font-weight: 900;
            background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        .pricing-amount span {
            font-size: 18px;
            font-weight: 500;
            color: #b8b8d1;
            -webkit-text-fill-color: #b8b8d1;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 32px;
            text-align: left;
            padding: 0;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            color: #d6d6f2;
            font-size: 15px;
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: #ff3b7a;
            font-size: 14px;
            width: 18px;
        }

        .pricing-features li i.fa-times {
            color: #64748b;
        }

        .pricing-btn {
            width: 100%;
            padding: 16px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pricing-card .pricing-btn {
            background: transparent;
            border: 2px solid rgba(255, 59, 122, 0.5);
            color: #ff3b7a;
        }

        .pricing-card .pricing-btn:hover {
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.3);
            transform: translateY(-3px);
        }

        .pricing-card.featured .pricing-btn {
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            border: none;
            color: #ffffff;
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.3);
        }

        .pricing-card.featured .pricing-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 50px rgba(255, 59, 122, 0.5);
        }

        /* ========== SPONSORS SECTION ========== */
        .sponsors-section {
            background: linear-gradient(180deg, #1a0b2e 0%, #0a0a1a 100%);
            padding: 90px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sponsors-title {
            text-align: center;
            font-size: 14px;
            color: #7a7a9e;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 45px;
            font-weight: 600;
        }

        .sponsors-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 55px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .sponsor-logo {
            font-size: 38px;
            color: #4a4a6a;
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .sponsor-logo:hover {
            color: #ff3b7a;
            transform: scale(1.2);
        }

        /* ========== CTA SECTION ========== */
        .cta-section {
            background: linear-gradient(180deg, #0a0a1a 0%, #1a0b2e 100%);
            padding: 130px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: "";
            position: absolute;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(255, 59, 122, 0.12), transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .cta-content {
            position: relative;
            z-index: 10;
            max-width: 750px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 48px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 22px;
            line-height: 1.2;
        }

        .cta-title span {
            background: linear-gradient(135deg, #ff3b7a, #ff6b9d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cta-subtitle {
            font-size: 18px;
            color: #b8b8d1;
            margin-bottom: 45px;
            line-height: 1.7;
        }

        .cta-form {
            display: flex;
            gap: 18px;
            max-width: 520px;
            margin: 0 auto;
        }

        .cta-input {
            flex: 1;
            padding: 20px 28px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            color: #ffffff;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .cta-input::placeholder {
            color: #7a7a9e;
        }

        .cta-input:focus {
            outline: none;
            border-color: rgba(255, 59, 122, 0.6);
            background: rgba(255, 59, 122, 0.08);
            box-shadow: 0 0 0 4px rgba(255, 59, 122, 0.1);
        }

        .cta-btn {
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            border: none;
            padding: 20px 45px;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.35);
            white-space: nowrap;
        }

        .cta-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 50px rgba(255, 59, 122, 0.5);
        }

        /* ========== FLOATING CHAT BUBBLE ========== */
        .chat-window{

position:fixed;

bottom:120px;

right:35px;

width:340px;

background:white;

border-radius:15px;

display:none;

box-shadow:0 20px 60px rgba(0,0,0,0.3);

overflow:hidden;

z-index:1000;

}

.chat-header{

background:#ff3b7a;

color:white;

padding:12px;

font-weight:bold;

display:flex;

justify-content:space-between;

}

.chat-body{

height:280px;

overflow:auto;

padding:10px;

background:#fafafa;

}

.bot-msg{

background:#eeeeee;

color:#111;   /* ADD THIS */

padding:10px;

margin:6px;

border-radius:10px;

font-size:14px;

max-width:80%;

}

.user-msg{

background:#ff3b7a;

color:white;

padding:10px;

margin:6px;

border-radius:10px;

text-align:right;

font-size:14px;

max-width:80%;

margin-left:auto;

}

.chat-footer{

display:flex;

border-top:1px solid #ddd;

}

.chat-footer input{

flex:1;

border:none;

padding:10px;

outline:none;

}

.chat-footer button{

border:none;

background:#ff3b7a;

color:white;

padding:10px 15px;

cursor:pointer;

}
        .chat-bubble {
            position: fixed;
            bottom: 35px;
            right: 35px;
            z-index: 1000;
        }

        .chat-btn {
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, #ff3b7a, #ff1f65);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 15px 40px rgba(255, 59, 122, 0.45);
            transition: all 0.4s ease;
            border: none;
            position: relative;
        }

        .chat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 20px 50px rgba(255, 59, 122, 0.55);
        }

        .chat-btn::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid rgba(255, 59, 122, 0.3);
            animation: chat-pulse 2s ease-in-out infinite;
        }

        @keyframes chat-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0; }
        }

        .chat-tooltip {
            position: absolute;
            right: 85px;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            color: #1a0b2e;
            padding: 14px 24px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .chat-tooltip::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border: 8px solid transparent;
            border-left-color: #ffffff;
        }

        .chat-bubble:hover .chat-tooltip {
            opacity: 1;
            transform: translateY(-50%) translateX(-12px);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .bento-card-1 {
                grid-column: span 12;
                grid-row: span 1;
            }

            .bento-card-2, .bento-card-3, .bento-card-4, .bento-card-5 {
                grid-column: span 6;
            }

            .bento-card-6, .bento-card-7, .bento-card-8 {
                grid-column: span 4;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 450px;
            }

            .pricing-card.featured {
                transform: none;
                order: -1;
            }

            .pricing-card.featured:hover {
                transform: translateY(-12px);
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
                max-width: 550px;
            }
        }

        @media (max-width: 992px) {
            .hero h1 {
                font-size: 56px;
            }

            .section-header h2 {
                font-size: 38px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .cta-title {
                font-size: 36px;
            }

            .bento-card-6, .bento-card-7, .bento-card-8 {
                grid-column: span 6;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 100px 20px 80px;
                min-height: 100vh;
            }

            .hero h1 {
                font-size: 38px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero-btn {
                flex-direction: column;
                gap: 18px;
            }

            .btn-main, .btn-outline {
                width: 100%;
                justify-content: center;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .bento-card-1, .bento-card-2, .bento-card-3, 
            .bento-card-4, .bento-card-5, .bento-card-6,
            .bento-card-7, .bento-card-8 {
                grid-column: span 12;
            }

            .events-section {
                padding: 80px 20px;
            }

            .img-zoom-container {
                height: 220px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cta-form {
                flex-direction: column;
            }

            .cta-btn {
                width: 100%;
            }

            .sponsors-grid {
                gap: 40px;
            }

            .sponsor-logo {
                font-size: 30px;
            }

            .stat-number {
                font-size: 40px;
            }

            .scroll-indicator {
                display: none;
            }

            .btn-play-video {
                flex-direction: column;
                gap: 12px;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 32px;
            }

            .price-glass {
                font-size: 15px;
                padding: 10px 16px;
            }

            .cta-title {
                font-size: 28px;
            }

            .bento-title {
                font-size: 18px;
            }

            .bento-card-1 .bento-title {
                font-size: 24px;
            }

            .hero-badge {
                font-size: 12px;
                padding: 10px 20px;
            }

            .chat-bubble {
                bottom: 20px;
                right: 20px;
            }

            .chat-btn {
                width: 58px;
                height: 58px;
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<!-- HERO SECTION WITH VIDEO BACKGROUND -->
<div class="hero">
    <!-- Video Background -->
    <div class="hero-video-container">
        <video class="hero-video" autoplay muted loop playsinline poster="assets/images/hero-poster.jpg">
            <source src="assets/videos/hero-video.mp4" type="video/mp4">
            <source src="assets/videos/hero-video.webm" type="video/webm">
        </video>
    </div>

    <!-- Gradient Overlay -->
    <div class="hero-overlay"></div>

    <!-- Floating Particles -->
    <div class="hero-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Grid Pattern -->
    <div class="hero-grid"></div>

    <!-- Hero Content -->
    <div class="hero-content">
        <div class="hero-badge">
            <span class="live-dot"></span>
            LIVE EVENTS HAPPENING NOW
        </div>

        <h1>
            Organization Company <span>Events</span>
        </h1>

        <p>
            Discover concerts, tech events and workshops happening near you.
            Book tickets instantly with KeyStone and make unforgettable memories.
        </p>

        <div class="hero-btn">
            <a href="user/events.php" class="btn-main">
                <i class="fas fa-ticket-alt"></i>
                Book Event Now
            </a>
            <a href="user/events.php" class="btn-outline">
                <i class="fas fa-compass"></i>
                Explore Events
            </a>
        </div>

        <!-- Play Video Button -->
        <div class="btn-play-video" onclick="openVideoModal()">
            <div class="play-icon">
                <i class="fas fa-play"></i>
            </div>
            <span class="play-text">Watch Our Story</span>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <span>Scroll</span>
        <div class="mouse"></div>
    </div>
</div>

<!-- Video Modal -->
<div class="video-modal" id="videoModal">
    <div class="video-modal-content">
        <button class="video-modal-close" onclick="closeVideoModal()">
            <i class="fas fa-times"></i>
        </button>
        <iframe id="promoVideo" 
                src="" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
        </iframe>
    </div>
</div>

<!-- STATS SECTION -->
<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number"><?php echo $total_events; ?>+</div>
            <div class="stat-label">Events Hosted</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-number"><?php echo $total_bookings; ?>+</div>
            <div class="stat-label">Tickets Booked</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?php echo $total_users; ?>+</div>
            <div class="stat-label">Happy Users</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-number">4.9</div>
            <div class="stat-label">User Rating</div>
        </div>
    </div>
</div>

<!-- BENTO GRID SECTION -->
<div class="bento-section">
    <div class="section-header">
        <span class="section-tag">WHY CHOOSE US</span>
        <h2>Experience The Difference</h2>
        <p>We craft extraordinary moments that transform ordinary events into unforgettable experiences.</p>
    </div>

    <div class="bento-container">
        <div class="bento-grid">
            <!-- Featured Large Card -->
            <div class="bento-card bento-card-1">
                <div class="bento-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3 class="bento-title">Premium Event Experience</h3>
                <p class="bento-desc">From intimate gatherings to large-scale conferences, we deliver world-class event management with attention to every detail. Our team ensures every moment is perfect.</p>
                <div class="bento-stat">
                    <div class="bento-stat-number">98%</div>
                    <div class="bento-stat-label">Customer Satisfaction</div>
                </div>
            </div>

            <!-- Networking Card -->
            <div class="bento-card bento-card-2">
                <div class="bento-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="bento-title">Networking</h3>
                <p class="bento-desc">Connect with industry leaders and like-minded professionals.</p>
                <div class="bento-stat">
                    <div class="bento-stat-number">10+</div>
                    <div class="bento-stat-label">Networking Zones</div>
                </div>
            </div>

            <!-- Expert Speakers Card -->
            <div class="bento-card bento-card-3">
                <div class="bento-icon">
                    <i class="fas fa-microphone"></i>
                </div>
                <h3 class="bento-title">Expert Speakers</h3>
                <p class="bento-desc">Learn from the best minds in the industry.</p>
                <div class="bento-stat">
                    <div class="bento-stat-number">50+</div>
                    <div class="bento-stat-label">Speakers</div>
                </div>
            </div>

            <!-- Secure Booking Card -->
            <div class="bento-card bento-card-4">
                <div class="bento-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="bento-title">Secure Booking</h3>
                <p class="bento-desc">100% secure payment with instant confirmation.</p>
                <div class="bento-stat">
                    <div class="bento-stat-number">256-bit</div>
                    <div class="bento-stat-label">SSL Encryption</div>
                </div>
            </div>

            <!-- 24/7 Support Card -->
            <div class="bento-card bento-card-5">
                <div class="bento-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="bento-title">24/7 Support</h3>
                <p class="bento-desc">Round-the-clock assistance for all your queries.</p>
            </div>

            <!-- Mobile App Card -->
            <div class="bento-card bento-card-6">
                <div class="bento-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="bento-title">Mobile App</h3>
                <p class="bento-desc">Book and manage events on the go with our mobile app.</p>
            </div>

            <!-- Easy Refunds Card -->
            <div class="bento-card bento-card-7">
                <div class="bento-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h3 class="bento-title">Easy Refunds</h3>
                <p class="bento-desc">Hassle-free cancellation with quick refund processing.</p>
            </div>

            <!-- Live Updates Card -->
            <div class="bento-card bento-card-8">
                <div class="bento-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="bento-title">Live Updates</h3>
                <p class="bento-desc">Real-time notifications for all event updates.</p>
            </div>
        </div>
    </div>
</div>

<!-- EVENTS SECTION -->
<div class="events-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">UPCOMING EVENTS</span>
            <h2>Discover Amazing Experiences</h2>
            <p>Book your spot at the most exciting events happening near you</p>
        </div>

        <div class="text-end mb-4">
            <a href="user/events.php" class="view-all-btn">
                View All Events <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0) { 
                $delay = 0;
                while ($event = mysqli_fetch_assoc($result)) { 
                    $delay += 0.15;
            ?>
                <div class="col-lg-4 col-md-6 reveal-card" style="animation-delay: <?php echo $delay; ?>s">
                    <div class="card event-card-modern border-0">
                        <div class="img-zoom-container">
                            <div class="price-glass">
                                ₹ <?php echo number_format($event['price']); ?>
                            </div>
                            <div class="event-favorite">
                                <i class="far fa-heart"></i>
                            </div>
                            <?php if(!empty($event['image'])): ?>
                            <img src="assets/images/<?php echo $event['image']; ?>" 
                                 class="modern-img" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>">
                            <?php else: ?>
                            <div class="modern-img" style="background: linear-gradient(135deg, #ff3b7a, #6c5cff);"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body">
                            <div class="event-meta">
                                <span class="badge-modern">
                                    <?php echo htmlspecialchars($event['category'] ?? 'Event'); ?>
                                </span>
                                <div class="seats-available">
                                    <?php echo $event['available_seats']; ?> Seats Left
                                </div>
                            </div>
                            
                            <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                            
                            <p class="event-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($event['location']); ?>
                            </p>

                            <a href="event_details.php?id=<?php echo $event['id']; ?>" 
                               class="btn-modern-primary">
                                View Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } 
            } else { ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h4>No Events Found</h4>
                        <p>Check back soon for exciting new events!</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- TESTIMONIALS SECTION -->
<div class="testimonials-section">
    <div class="section-header">
        <span class="section-tag">TESTIMONIALS</span>
        <h2>What People Say</h2>
        <p>Hear from our amazing community of event enthusiasts</p>
    </div>

    <div class="testimonials-grid">
        <div class="testimonial-card">
            <div class="testimonial-quote">"</div>
            <p class="testimonial-text">Absolutely incredible experience! The organization was flawless, and I made so many valuable connections. Can't wait for the next event!</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">RK</div>
                <div class="testimonial-author-info">
                    <h4>Rahul Kumar</h4>
                    <p>Software Engineer</p>
                </div>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-quote">"</div>
            <p class="testimonial-text">The speakers were world-class, and the venue was stunning. This platform makes booking events so seamless. Highly recommended!</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">PS</div>
                <div class="testimonial-author-info">
                    <h4>Priya Sharma</h4>
                    <p>Marketing Director</p>
                </div>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-quote">"</div>
            <p class="testimonial-text">Best event I've attended this year. The networking opportunities were unmatched, and I learned so much from the sessions.</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">AM</div>
                <div class="testimonial-author-info">
                    <h4>Amit Mehta</h4>
                    <p>Startup Founder</p>
                </div>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRICING SECTION -->
<div class="pricing-section">
    <div class="section-header">
        <span class="section-tag">PRICING PLANS</span>
        <h2>Choose Your Pass</h2>
        <p>Flexible pricing options to suit every attendee</p>
    </div>

    <div class="pricing-grid">
        <div class="pricing-card">
            <div class="pricing-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <h3 class="pricing-name">Basic Pass</h3>
            <div class="pricing-price">
                <span class="pricing-amount">₹999<span>/event</span></span>
            </div>
            <ul class="pricing-features">
                <li><i class="fas fa-check"></i> General Admission</li>
                <li><i class="fas fa-check"></i> Main Stage Access</li>
                <li><i class="fas fa-check"></i> Event Materials</li>
                <li><i class="fas fa-check"></i> Lunch Included</li>
                <li><i class="fas fa-times"></i> VIP Lounge Access</li>
                <li><i class="fas fa-times"></i> Speaker Meet & Greet</li>
            </ul>
            <a href="payment/payment.php?plan=basic&price=999" class="pricing-btn">
Get Basic Pass <i class="fas fa-arrow-right"></i>
</a>
        </div>

        <div class="pricing-card featured">
            <div class="pricing-badge">Most Popular</div>
            <div class="pricing-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h3 class="pricing-name">Premium Pass</h3>
            <div class="pricing-price">
                <span class="pricing-amount">₹2,499<span>/event</span></span>
            </div>
            <ul class="pricing-features">
                <li><i class="fas fa-check"></i> Priority Seating</li>
                <li><i class="fas fa-check"></i> All Stage Access</li>
                <li><i class="fas fa-check"></i> Premium Materials</li>
                <li><i class="fas fa-check"></i> Full Day Meals</li>
                <li><i class="fas fa-check"></i> VIP Lounge Access</li>
                <li><i class="fas fa-check"></i> Networking Session</li>
            </ul>
           <a href="payment/payment.php?plan=premium&price=2499" class="pricing-btn">
Get Premium Pass <i class="fas fa-arrow-right"></i>
</a>
        </div>

        <div class="pricing-card">
            <div class="pricing-icon">
                <i class="fas fa-gem"></i>
            </div>
            <h3 class="pricing-name">VIP Pass</h3>
            <div class="pricing-price">
                <span class="pricing-amount">₹4,999<span>/event</span></span>
            </div>
            <ul class="pricing-features">
                <li><i class="fas fa-check"></i> Front Row Seating</li>
                <li><i class="fas fa-check"></i> All Access Pass</li>
                <li><i class="fas fa-check"></i> Exclusive Swag Kit</li>
                <li><i class="fas fa-check"></i> Gourmet Dining</li>
                <li><i class="fas fa-check"></i> Private Lounge</li>
                <li><i class="fas fa-check"></i> Speaker Meet & Greet</li>
            </ul>
           <a href="payment/payment.php?plan=vip&price=4999" class="pricing-btn">
Get VIP Pass <i class="fas fa-arrow-right"></i>
</a>
        </div>
    </div>
</div>

<!-- SPONSORS SECTION -->
<div class="sponsors-section">
    <p class="sponsors-title">Trusted by Industry Leaders</p>
    <div class="sponsors-grid">
        <div class="sponsor-logo"><i class="fab fa-google"></i></div>
        <div class="sponsor-logo"><i class="fab fa-microsoft"></i></div>
        <div class="sponsor-logo"><i class="fab fa-amazon"></i></div>
        <div class="sponsor-logo"><i class="fab fa-apple"></i></div>
        <div class="sponsor-logo"><i class="fab fa-facebook"></i></div>
        <div class="sponsor-logo"><i class="fab fa-spotify"></i></div>
        <div class="sponsor-logo"><i class="fab fa-slack"></i></div>
        <div class="sponsor-logo"><i class="fab fa-stripe"></i></div>
    </div>
</div>

<!-- CTA SECTION -->
<div class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Ready to Experience<br><span>Something Extraordinary?</span></h2>
        <p class="cta-subtitle">Join our community and never miss an event. Get exclusive updates and early bird access to all upcoming events.</p>
        <form class="cta-form">
            <input type="email" class="cta-input" placeholder="Enter your email address" required>
            <button type="submit" class="cta-btn">Subscribe <i class="fas fa-arrow-right"></i></button>
        </form>
    </div>
</div>

<!-- FLOATING CHAT BUBBLE -->
<div class="chat-bubble">

<div class="chat-tooltip">
Need help? Chat with us!
</div>

<button class="chat-btn" id="chatToggle">

<i class="fas fa-comment-dots"></i>

</button>

</div>

<!-- CHAT WINDOW -->

<div class="chat-window" id="chatWindow">

<div class="chat-header">

AI Assistant

<span id="closeChat">✖</span>

</div>

<div class="chat-body" id="chatBody">

<div class="bot-msg">

Hello 👋  
I am your AI assistant.  
Ask about booking, payment or plans.

</div>

</div>

<div class="chat-footer">

<input type="text"

id="chatInput"

placeholder="Ask something...">

<button id="sendBtn">

Send

</button>

</div>

</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Video Modal Functions
    function openVideoModal() {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('promoVideo');
        
        // Replace YOUR_VIDEO_ID with your actual YouTube video ID
        video.src = "assets/videos/video1.mp4?autoplay=1&mute=1";
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const video = document.getElementById('promoVideo');
        
        video.src = "";
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close modal on overlay click
    document.getElementById('videoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVideoModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
        }
    });

    // Favorite button toggle
    document.querySelectorAll('.event-favorite').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            this.classList.toggle('active');
        });
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Pause video when tab is not visible
    document.addEventListener('visibilitychange', function() {
        const video = document.querySelector('.hero-video');
        if (video) {
            if (document.hidden) {
                video.pause();
            } else {
                video.play();
            }
        }
    });

    // Reveal cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-card').forEach(card => {
        observer.observe(card);
    });

    // Newsletter form
    document.querySelector('.cta-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input').value;
        alert('Thank you for subscribing! We will send updates to: ' + email);
        this.reset();
    });
</script>
<script>

let chat=document.getElementById("chatWindow");

document.getElementById("chatToggle")
.onclick=function(){

chat.style.display="block";

};

document.getElementById("closeChat")
.onclick=function(){

chat.style.display="none";

};

document.getElementById("sendBtn")
.onclick=sendMessage;

document.getElementById("chatInput")
.addEventListener("keypress",function(e){

if(e.key==="Enter"){

sendMessage();

}

});

function sendMessage(){

let input=document.getElementById(
"chatInput"
);

let msg=input.value.trim();

if(msg==="") return;

let body=document.getElementById(
"chatBody"
);

body.innerHTML+=
"<div class='user-msg'>"+msg+"</div>";

body.innerHTML+=
"<div class='bot-msg'>Typing...</div>";

body.scrollTop=
body.scrollHeight;

fetch("lib/ai_chatbot.php",{

method:"POST",

headers:{
"Content-Type":
"application/x-www-form-urlencoded"
},

body:"message="+encodeURIComponent(msg)

})

.then(res=>res.json())

.then(data=>{

let bots=
document.querySelectorAll(".bot-msg");

bots[bots.length-1].innerHTML=
data.reply;

body.scrollTop=
body.scrollHeight;

});

input.value="";

}

</script>

</body>
</html>

<?php include("includes/footer.php"); ?>