<?php

session_start();

include("../includes/header.php");

include("../config/database.php");

include("../includes/navbar.php");

if (!isset($_SESSION['user_id'])) {

    header("Location:../auth/login.php");

    exit();

}

$user_id = $_SESSION['user_id'];

// FIXED QUERY (LEFT JOIN for plans)

$sql = "SELECT 

bookings.*,

events.title,

events.event_date,

events.event_time,

events.location,

events.image

FROM bookings

LEFT JOIN events 
ON bookings.event_id=events.id

WHERE bookings.user_id=?

ORDER BY bookings.id DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

// user info

$user_query = $conn->prepare(

    "SELECT * FROM users WHERE id=?"

);

$user_query->bind_param("i", $user_id);

$user_query->execute();

$user = $user_query->get_result()->fetch_assoc();

?>

<style>
    /* ========== CSS VARIABLES ========== */
    :root {
        --pink-primary: #ff3b7a;
        --pink-dark: #e0356c;
        --dark-bg: #0a0a1a;
        --purple-dark: #1b0033;
        --purple-mid: #3a0b55;
        --text-light: #b8b8d1;
        --text-muted: #7a7a9e;
    }

    body {
        background: linear-gradient(135deg, var(--dark-bg) 0%, var(--purple-dark) 100%);
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
    }

    /* ========== HEADER SECTION ========== */
    .page-header {
        background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-dark) 50%, var(--dark-bg) 100%);
        padding: 100px 0 140px;
        position: relative;
        overflow: hidden;
        margin-bottom: -80px;
    }

    .page-header::before {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 59, 122, 0.12), transparent 70%);
        top: -200px;
        right: -150px;
        animation: float 8s ease-in-out infinite;
    }

    .page-header::after {
        content: "";
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(108, 92, 255, 0.1), transparent 70%);
        bottom: -150px;
        left: -100px;
        animation: float 10s ease-in-out infinite reverse;
    }

    @keyframes float {

        0%,
        100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(20px, -20px);
        }
    }

    .header-content {
        position: relative;
        z-index: 10;
        text-align: center;
        animation: fadeInDown 0.8s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header-badge {
        display: inline-block;
        background: rgba(255, 59, 122, 0.15);
        border: 1px solid rgba(255, 59, 122, 0.3);
        color: var(--pink-primary);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .header-title {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #ffffff, #e0e0ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-title .highlight {
        background: linear-gradient(135deg, var(--pink-primary), #ff6b9d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-subtitle {
        color: var(--text-light);
        font-size: 16px;
    }

    /* ========== STATS CARDS ========== */
    .stats-section {
        position: relative;
        z-index: 100;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 59, 122, 0.3);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.15));
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        color: var(--pink-primary);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 14px;
    }

    /* ========== BOOKINGS TABLE CARD ========== */
    .bookings-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 35px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease;
        overflow: hidden;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bookings-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .bookings-card .card-header h3 {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .bookings-count {
        background: rgba(255, 59, 122, 0.15);
        border: 1px solid rgba(255, 59, 122, 0.3);
        color: var(--pink-primary);
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
    }

    /* ========== TABLE STYLES ========== */
    .table-wrapper {
        overflow-x: auto;
    }

    .bookings-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .bookings-table thead th {
        background: transparent;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px;
        border: none;
    }

    .bookings-table tbody tr {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .bookings-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: scale(1.01);
    }

    .bookings-table tbody td {
        padding: 18px 15px;
        border: none;
        color: #ffffff;
        vertical-align: middle;
    }

    .bookings-table tbody tr td:first-child {
        border-radius: 15px 0 0 15px;
    }

    .bookings-table tbody tr td:last-child {
        border-radius: 0 15px 15px 0;
    }

    /* Booking Code Badge */
    .booking-code {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Courier New', monospace;
    }

    /* Event Title */
    .event-title-cell {
        font-weight: 600;
        font-size: 15px;
        max-width: 200px;
    }

    /* Tickets Count */
    .tickets-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(108, 92, 255, 0.15);
        border: 1px solid rgba(108, 92, 255, 0.3);
        color: #a78bfa;
        padding: 6px 14px;
        border-radius: 10px;
        font-weight: 600;
    }

    /* Price */
    .price-cell {
        font-size: 16px;
        font-weight: 700;
        color: #4ade80 !important;
    }

    /* Payment Method Badge */
    .payment-badge {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), rgba(14, 165, 233, 0.1));
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: #38bdf8;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-paid {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.15));
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #4ade80;
    }

    .status-pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.15));
        border: 1px solid rgba(245, 158, 11, 0.4);
        color: #fbbf24;
    }

    .status-failed {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.15));
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
    }

    /* Date Cell */
    .date-cell {
        color: var(--text-light);
        font-size: 14px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 59, 122, 0.4);
        color: white;
    }

    .btn-pdf {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.15));
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #4ade80;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-pdf:hover {
        background: #10b981;
        color: white;
        transform: translateY(-2px);
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 48px;
        color: rgba(255, 255, 255, 0.1);
    }

    .empty-state h4 {
        color: var(--text-light);
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 25px;
    }

    .btn-browse {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
        padding: 14px 35px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-browse:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 59, 122, 0.4);
        color: white;
    }

    /* ========== MODAL STYLES ========== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal-content {
        background: linear-gradient(135deg, #1a0b2e, #0a0a1a);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: slideUp 0.4s ease;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 50%;
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .modal-close:hover {
        background: var(--pink-primary);
        transform: rotate(90deg);
    }

    .modal-header-image {
        height: 200px;
        background-size: cover;
        background-position: center;
        border-radius: 30px 30px 0 0;
        position: relative;
    }

    .modal-header-image::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(transparent, #1a0b2e);
    }

    .modal-body {
        padding: 30px;
    }

    .modal-booking-code {
        display: inline-block;
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .modal-event-title {
        font-size: 28px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 25px;
    }

    .modal-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .modal-info-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 18px;
    }

    .modal-info-item .label {
        display: block;
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .modal-info-item .value {
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
    }

    .modal-info-item .value.price {
        color: #4ade80;
        font-size: 20px;
    }

    .modal-info-item .value.status-paid {
        color: #4ade80;
    }

    .modal-info-item .value.status-pending {
        color: #fbbf24;
    }

    .modal-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        margin: 25px 0;
    }

    .modal-footer {
        padding: 0 30px 30px;
        display: flex;
        gap: 15px;
    }

    .modal-btn {
        flex: 1;
        padding: 16px;
        border-radius: 15px;
        font-weight: 700;
        font-size: 15px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
    }

    .modal-btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 59, 122, 0.4);
        color: white;
    }

    .modal-btn-secondary {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .modal-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .header-title {
            font-size: 36px;
        }

        .bookings-card {
            padding: 25px;
        }

        .modal-info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 80px 0 120px;
            margin-bottom: -60px;
        }

        .header-title {
            font-size: 28px;
        }

        .stat-card {
            margin-bottom: 15px;
        }

        .bookings-table thead {
            display: none;
        }

        .bookings-table tbody tr {
            display: block;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 15px;
        }

        .bookings-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bookings-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--text-muted);
            font-size: 12px;
            text-transform: uppercase;
        }

        .bookings-table tbody tr td:first-child,
        .bookings-table tbody tr td:last-child {
            border-radius: 0;
        }

        .bookings-table tbody td:last-child {
            border-bottom: none;
            justify-content: flex-end;
            padding-top: 15px;
        }

        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }

        .modal-content {
            border-radius: 20px;
        }

        .modal-header-image {
            height: 150px;
            border-radius: 20px 20px 0 0;
        }

        .modal-event-title {
            font-size: 22px;
        }

        .modal-footer {
            flex-direction: column;
        }
    }
</style>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <div class="header-content">
            <div class="header-badge">
                <i class="fas fa-ticket-alt me-2"></i> MY DASHBOARD
            </div>
            <h1 class="header-title">
                My <span class="highlight">Bookings</span>
            </h1>
            <p class="header-subtitle">
                Track and manage all your event reservations in one place
            </p>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="container" style="position: relative; z-index: 100; padding-bottom: 80px;">

    <!-- STATS SECTION -->
    <?php
    // Calculate stats
    $total_bookings = mysqli_num_rows($result);
    mysqli_data_seek($result, 0); // Reset pointer
    
    $total_spent = 0;
    $total_tickets = 0;
    $pending_count = 0;

    while ($stat_row = mysqli_fetch_assoc($result)) {
        $total_spent += $stat_row['total_price'];
        $total_tickets += $stat_row['tickets'];
        if ($stat_row['payment_status'] != 'Paid') {
            $pending_count++;
        }
    }
    mysqli_data_seek($result, 0); // Reset pointer again
    ?>

    <div class="stats-section">
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_bookings; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_tickets; ?></div>
                    <div class="stat-label">Tickets Purchased</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-number">₹<?php echo number_format($total_spent); ?></div>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number"><?php echo $pending_count; ?></div>
                    <div class="stat-label">Pending Payments</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOKINGS TABLE -->
    <div class="bookings-card">
        <div class="card-header">
            <h3><i class="fas fa-list me-2" style="color: var(--pink-primary);"></i>Booking History</h3>
            <span class="bookings-count"><?php echo $total_bookings; ?> Bookings</span>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-wrapper">
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Event</th>
                            <th>Tickets</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)):
                            $status_class = '';
                            if ($row['payment_status'] == 'Paid')
                                $status_class = 'status-paid';
                            elseif ($row['payment_status'] == 'Pending')
                                $status_class = 'status-pending';
                            else
                                $status_class = 'status-failed';
                            ?>
                            <tr>

                                <td data-label="Booking ID">

                                    <span class="booking-code">

                                        <?= htmlspecialchars($row['booking_code'] ?? 'N/A'); ?>

                                    </span>

                                </td>

                                <td data-label="Event" class="event-title-cell">

                                    <?php

                                    if ($row['booking_type'] == "plan") {

                                        echo htmlspecialchars(

                                            ucfirst($row['plan_name']) . " Plan"

                                        );

                                    } else {

                                        echo htmlspecialchars(

                                            $row['title'] ?? 'N/A'

                                        );

                                    }

                                    ?>

                                </td>

                                <td data-label="Tickets">

                                    <span class="tickets-count">

                                        <i class="fas fa-ticket-alt"></i>

                                        <?php

                                        echo ($row['booking_type'] == "plan")

                                            ? "1 Pass"

                                            : ($row['tickets'] ?? 0);

                                        ?>

                                    </span>

                                </td>

                                <td data-label="Total" class="price-cell">

                                    ₹ <?= number_format($row['total_price'] ?? 0); ?>

                                </td>

                                <td data-label="Payment">

                                    <span class="payment-badge">

                                        <?= htmlspecialchars(

                                            $row['payment_method'] ?? 'Razorpay'

                                        ); ?>

                                    </span>

                                </td>

                                <td data-label="Status">

                                    <span class="status-badge <?= $status_class ?>">

                                        <?= htmlspecialchars(

                                            $row['payment_status'] ?? 'Pending'

                                        ); ?>

                                    </span>

                                </td>

                                <td data-label="Date" class="date-cell">

                                    <?php

                                    echo isset($row['booking_date'])

                                        ? date(
                                            "d M Y",

                                            strtotime($row['booking_date'])
                                        )

                                        : 'N/A';

                                    ?>

                                </td>

                                <td data-label="Actions">

                                    <div class="action-buttons">

                                        <button class="btn-view" onclick="openModal(

'<?= htmlspecialchars($row['booking_code'] ?? 'N/A'); ?>',

'<?= ($row['booking_type'] == "plan")

                    ? ucfirst($row['plan_name']) . " Plan"

                    : htmlspecialchars(addslashes($row['title'] ?? 'N/A')); ?>',

'<?= ($row['booking_type'] == "plan")

                    ? '1'

                    : ($row['tickets'] ?? 0); ?>',

'<?= number_format($row['total_price'] ?? 0); ?>',

'<?= htmlspecialchars(

                    $row['payment_method'] ?? 'Razorpay'

                ); ?>',

'<?= htmlspecialchars(

                    $row['payment_status'] ?? 'Paid'

                ); ?>',

'<?= isset($row['booking_date'])

                    ? date(
                        'd M Y',

                        strtotime($row['booking_date'])
                    )

                    : 'N/A'; ?>',

'<?= ($row['booking_type'] == "plan")

                    ? 'Membership'

                    : (isset($row['event_date'])

                        ? date(
                            'd M Y',

                            strtotime($row['event_date'])
                        )

                        : 'N/A'); ?>',

'<?= ($row['booking_type'] == "plan")

                    ? 'Active Plan'

                    : (isset($row['event_time'])

                        ? date(
                            'h:i A',

                            strtotime($row['event_time'])
                        )

                        : 'N/A'); ?>',

'<?= ($row['booking_type'] == "plan")

                    ? 'Online Access'

                    : (isset($row['location'])

                        ? htmlspecialchars(

                            addslashes($row['location'])

                        ) : 'N/A'); ?>',

'<?= !empty($row['image'])

                    ? $row['image']

                    : 'default.jpg'; ?>',

'<?= $row['id']; ?>'

)       ">

                                            <i class="fas fa-eye"></i>

                                            View

                                        </button>

                                        <a href="invoice_pdf.php?id=<?= $row['id']; ?>" class="btn-pdf">

                                            <i class="fas fa-file-pdf"></i>

                                            PDF

                                        </a>

                                    </div>

                                </td>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h4>No Bookings Yet</h4>
                <p>You haven't made any bookings. Explore our events and book your first experience!</p>
                <a href="events.php" class="btn-browse">
                    <i class="fas fa-search"></i> Browse Events
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- BOOKING DETAILS MODAL -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-header-image" id="modalImage"></div>

        <div class="modal-body">
            <span class="modal-booking-code" id="modalBookingCode"></span>
            <h2 class="modal-event-title" id="modalEventTitle"></h2>

            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <span class="label">Event Date</span>
                    <span class="value" id="modalEventDate">
                        <i class="far fa-calendar me-2" style="color: var(--pink-primary);"></i>
                        <span></span>
                    </span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Event Time</span>
                    <span class="value" id="modalEventTime">
                        <i class="far fa-clock me-2" style="color: var(--pink-primary);"></i>
                        <span></span>
                    </span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Venue</span>
                    <span class="value" id="modalLocation">
                        <i class="fas fa-map-marker-alt me-2" style="color: var(--pink-primary);"></i>
                        <span></span>
                    </span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Tickets</span>
                    <span class="value" id="modalTickets">
                        <i class="fas fa-ticket-alt me-2" style="color: var(--pink-primary);"></i>
                        <span></span>
                    </span>
                </div>
            </div>

            <div class="modal-divider"></div>

            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <span class="label">Total Amount</span>
                    <span class="value price" id="modalTotal"></span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Payment Method</span>
                    <span class="value" id="modalPaymentMethod"></span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Payment Status</span>
                    <span class="value" id="modalPaymentStatus"></span>
                </div>
                <div class="modal-info-item">
                    <span class="label">Booking Date</span>
                    <span class="value" id="modalBookingDate"></span>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <a href="#" class="modal-btn modal-btn-primary" id="modalInvoiceBtn">
                <i class="fas fa-file-invoice"></i> View Invoice
            </a>
            <a href="#" class="modal-btn modal-btn-secondary" id="modalPdfBtn">
                <i class="fas fa-download"></i> Download PDF
            </a>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function openModal(bookingCode, title, tickets, total, paymentMethod, paymentStatus, bookingDate, eventDate, eventTime, location, image, bookingId) {
        const modal = document.getElementById('bookingModal');

        // Set image
        document.getElementById('modalImage').style.backgroundImage = `url('../assets/images/${image}')`;

        // Set content
        document.getElementById('modalBookingCode').textContent = '#' + bookingCode;
        document.getElementById('modalEventTitle').textContent = title;

        // Event details
        document.querySelector('#modalEventDate span').textContent = eventDate;
        document.querySelector('#modalEventTime span').textContent = eventTime;
        document.querySelector('#modalLocation span').textContent = location;
        document.querySelector('#modalTickets span').textContent = tickets + ' Ticket(s)';

        // Payment details
        document.getElementById('modalTotal').textContent = '₹ ' + total;
        document.getElementById('modalPaymentMethod').textContent = paymentMethod;
        document.getElementById('modalBookingDate').textContent = bookingDate;

        // Status with color
        const statusEl = document.getElementById('modalPaymentStatus');
        statusEl.textContent = paymentStatus;
        statusEl.className = 'value';
        if (paymentStatus === 'Paid') {
            statusEl.classList.add('status-paid');
        } else {
            statusEl.classList.add('status-pending');
        }

        // Set button links
        document.getElementById('modalInvoiceBtn').href = 'invoice.php?id=' + bookingId;
        document.getElementById('modalPdfBtn').href = 'invoice_pdf.php?id=' + bookingId;

        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('bookingModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('bookingModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>

<?php include("../includes/footer.php"); ?>