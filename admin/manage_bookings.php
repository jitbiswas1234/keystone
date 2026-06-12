<?php

include("../config/database.php");
include("../includes/header.php");
include("../includes/navbar.php");

if(!isset($_SESSION['admin_id']))
{
header("Location:../auth/login.php");
exit();
}

$sql="SELECT bookings.*,users.name,events.title

FROM bookings

JOIN users ON bookings.user_id=users.id

JOIN events ON bookings.event_id=events.id

ORDER BY bookings.id DESC";

$result=mysqli_query($conn,$sql);

?>

<style>
    .page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-header-left {
        flex: 1;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: #6366f1;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #64748b;
    }

    .breadcrumb-nav a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }

    .breadcrumb-nav i {
        font-size: 10px;
        color: #94a3b8;
    }

    /* Export Button */
    .btn-export {
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
        text-decoration: none;
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-export:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-mini-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-mini-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-mini-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-mini-icon.purple {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .stat-mini-icon.green {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-mini-icon.orange {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stat-mini-icon.blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-mini-info h3 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .stat-mini-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 24px 32px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-card-header h2 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-card-header .booking-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .table-card-body {
        padding: 0;
    }

    /* Search & Filter Bar */
    .table-toolbar {
        padding: 20px 32px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 350px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        background: white;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .filter-btns {
        display: flex;
        gap: 10px;
    }

    .filter-btn {
        padding: 10px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        background: white;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn:hover {
        border-color: #6366f1;
        color: #6366f1;
    }

    .filter-btn.active {
        background: #6366f1;
        border-color: #6366f1;
        color: white;
    }

    /* Table Styles */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead {
        background: #f8fafc;
    }

    .custom-table thead th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: #f8fafc;
    }

    .custom-table tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* ID Badge */
    .id-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    /* User Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .user-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 2px 0;
    }

    .user-info span {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Event Cell */
    .event-cell {
        max-width: 200px;
    }

    .event-cell h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Tickets Badge */
    .tickets-badge {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tickets-badge i {
        font-size: 12px;
    }

    /* Price Badge */
    .price-badge {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1));
        color: #059669;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
    }

    /* Payment Method Badge */
    .payment-method-badge {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .payment-method-badge.online {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .payment-method-badge.cash {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .payment-method-badge.card {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .payment-method-badge.upi {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* Status Badge */
    .status-badge {
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-badge.paid {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-badge.paid::before {
        background: #10b981;
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-badge.pending::before {
        background: #f59e0b;
    }

    .status-badge.cancelled {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-badge.cancelled::before {
        background: #ef4444;
    }

    /* Date Cell */
    .date-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .date-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(99, 102, 241, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #6366f1;
    }

    .date-icon .day {
        font-size: 14px;
        font-weight: 700;
        line-height: 1;
    }

    .date-text {
        font-size: 13px;
        color: #374151;
        font-weight: 500;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-view {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .btn-view:hover {
        background: #6366f1;
        color: white;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .btn-cancel:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    .btn-approve {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .btn-approve:hover {
        background: #10b981;
        color: white;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 60px;
        color: #e5e7eb;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Table Footer */
    .table-footer {
        padding: 20px 32px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e5e7eb;
    }

    .showing-text {
        font-size: 14px;
        color: #64748b;
    }

    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination li a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: white;
        border: 1px solid #e5e7eb;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination li a:hover,
    .pagination li.active a {
        background: #6366f1;
        border-color: #6366f1;
        color: white;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .custom-table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 768px) {
        .page-wrapper {
            padding: 20px 0;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .table-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: 100%;
        }

        .filter-btns {
            justify-content: center;
            flex-wrap: wrap;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-card-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .table-footer {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-wrapper">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1 class="page-title">
                    <i class="fas fa-ticket-alt"></i>
                    Manage Bookings
                </h1>
                <nav class="breadcrumb-nav">
                    <a href="dashboard.php">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Bookings</span>
                </nav>
            </div>

           
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-mini-card">
                <div class="stat-mini-icon purple">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php echo mysqli_num_rows($result); ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>

            <div class="stat-mini-card">
                <div class="stat-mini-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php 
                        $paid = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE payment_status='Paid'");
                        $paid_count = mysqli_fetch_assoc($paid);
                        echo $paid_count['total'];
                    ?></h3>
                    <p>Paid Bookings</p>
                </div>
            </div>

            <div class="stat-mini-card">
                <div class="stat-mini-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php 
                        $pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE payment_status='Pending'");
                        $pending_count = mysqli_fetch_assoc($pending);
                        echo $pending_count['total'];
                    ?></h3>
                    <p>Pending</p>
                </div>
            </div>

            <div class="stat-mini-card">
                <div class="stat-mini-icon blue">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="stat-mini-info">
                    <h3>₹<?php 
                        $revenue = mysqli_query($conn, "SELECT COALESCE(SUM(total_price),0) as total FROM bookings WHERE payment_status='Paid'");
                        $revenue_total = mysqli_fetch_assoc($revenue);
                        echo number_format($revenue_total['total']);
                    ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    All Bookings
                </h2>
                <span class="booking-count">
                    <?php 
                    mysqli_data_seek($result, 0);
                    echo mysqli_num_rows($result); 
                    ?> Bookings
                </span>
            </div>

            <!-- Toolbar -->
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search bookings..." onkeyup="searchTable()">
                </div>

                <div class="filter-btns">
                    <button class="filter-btn active" onclick="filterTable('all')">
                        <i class="fas fa-th-list"></i>
                        All
                    </button>
                    <button class="filter-btn" onclick="filterTable('paid')">
                        <i class="fas fa-check"></i>
                        Paid
                    </button>
                    <button class="filter-btn" onclick="filterTable('pending')">
                        <i class="fas fa-clock"></i>
                        Pending
                    </button>
                </div>
            </div>

            <div class="table-card-body">
                <?php 
                mysqli_data_seek($result, 0);
                if(mysqli_num_rows($result) > 0) { 
                ?>
                <table class="custom-table" id="bookingsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Tickets</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
<?php 
while($row=mysqli_fetch_assoc($result)){ 

$date = new DateTime($row['booking_date']);

$initials = strtoupper(substr($row['name'] ?? 'U', 0, 2));

/* PAYMENT METHOD SAFE */

$payment_method = strtolower($row['payment_method'] ?? 'online');

$payment_class = 'online';

if(strpos($payment_method,'cash') !== false) 
$payment_class='cash';

elseif(strpos($payment_method,'card') !== false) 
$payment_class='card';

elseif(strpos($payment_method,'upi') !== false) 
$payment_class='upi';

/* STATUS SAFE */

$status_lower = strtolower($row['payment_status'] ?? 'paid');

$status_class='paid';

if(strpos($status_lower,'pending') !== false) 
$status_class='pending';

elseif(strpos($status_lower,'cancel') !== false) 
$status_class='cancelled';

?>
                        <tr data-status="<?php echo $status_class; ?>">
                            <td>
                                <span class="id-badge">#<?php echo $row['id']; ?></span>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar"><?php echo $initials; ?></div>
                                    <div class="user-info">
                                        <h4><?php echo $row['name']; ?></h4>
                                        <span>Customer</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="event-cell">
                                    <h4><?php echo $row['title']; ?></h4>
                                </div>
                            </td>
                            <td>
                                <span class="tickets-badge">
                                    <i class="fas fa-ticket-alt"></i>
                                    <?php echo $row['tickets']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="price-badge">₹<?php echo number_format($row['total_price']); ?></span>
                            </td>
                            <td>
                                <span class="payment-method-badge <?php echo $payment_class; ?>">
                                    <i class="fas fa-credit-card"></i>
                                    <?php echo $row['payment_method']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $row['payment_status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-icon">
                                        <span class="day"><?php echo $date->format('d'); ?></span>
                                        <span><?php echo $date->format('M'); ?></span>
                                    </div>
                                    <span class="date-text"><?php echo $date->format('Y'); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="view_booking.php?id=<?php echo $row['id']; ?>" class="btn-action btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if($status_class == 'pending') { ?>
                                    <a href="approve_booking.php?id=<?php echo $row['id']; ?>" class="btn-action btn-approve" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php } ?>
                                    <a href="cancel_booking.php?id=<?php echo $row['id']; ?>" class="btn-action btn-cancel" title="Cancel" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <h3>No Bookings Found</h3>
                    <p>There are no bookings to display at the moment.</p>
                </div>
                <?php } ?>
            </div>

            <?php 
            mysqli_data_seek($result, 0);
            if(mysqli_num_rows($result) > 0) { 
            ?>
            <div class="table-footer">
                <span class="showing-text">
                    Showing <?php echo mysqli_num_rows($result); ?> bookings
                </span>
                <ul class="pagination">
                    <li><a href="#"><i class="fas fa-chevron-left"></i></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i></a></li>
                </ul>
            </div>
            <?php } ?>
        </div>

    </div>
</div>

<script>
    // Search functionality
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('bookingsTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            rows[i].style.display = found ? '' : 'none';
        }
    }

    // Filter functionality
    function filterTable(status) {
        const table = document.getElementById('bookingsTable');
        const rows = table.getElementsByTagName('tr');

        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        event.target.closest('.filter-btn').classList.add('active');

        for (let i = 1; i < rows.length; i++) {
            const rowStatus = rows[i].getAttribute('data-status');
            
            if (status === 'all') {
                rows[i].style.display = '';
            } else if (rowStatus === status) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }

    // Export functionality (basic)
    function exportTable() {
        alert('Export functionality - You can integrate with libraries like SheetJS or generate CSV/PDF');
    }

    // Cancel confirmation
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                e.preventDefault();
            }
        });
    });
</script>

<?php include("../includes/footer.php"); ?>