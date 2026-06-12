<?php

include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

if(!isset($_SESSION['admin_id']))
{
header("Location:../auth/login.php");
exit();
}

$sql="SELECT * FROM users ORDER BY id DESC";

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

    /* Stats Card */
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

    .table-card-header .user-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .table-card-body {
        padding: 0;
    }

    /* Search Bar */
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
        gap: 14px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
        text-transform: uppercase;
    }

    .user-avatar.gradient-1 {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .user-avatar.gradient-2 {
        background: linear-gradient(135deg, #ec4899, #f472b6);
    }

    .user-avatar.gradient-3 {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .user-avatar.gradient-4 {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .user-avatar.gradient-5 {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .user-info h4 {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 3px 0;
    }

    .user-info span {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Email Cell */
    .email-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #374151;
        font-size: 14px;
    }

    .email-cell i {
        color: #6366f1;
        font-size: 14px;
    }

    .email-cell a {
        color: #374151;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .email-cell a:hover {
        color: #6366f1;
    }

    /* Phone Cell */
    .phone-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #374151;
        font-size: 14px;
    }

    .phone-cell i {
        color: #10b981;
        font-size: 14px;
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

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #ef4444;
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
</style>

<div class="page-wrapper">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <h1 class="page-title">
                    <i class="fas fa-users"></i>
                    Manage Users
                </h1>
                <nav class="breadcrumb-nav">
                    <a href="dashboard.php">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Users</span>
                </nav>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-mini-card">
                <div class="stat-mini-icon purple">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php echo mysqli_num_rows($result); ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    All Users
                </h2>
                <span class="user-count">
                    <?php 
                    mysqli_data_seek($result, 0);
                    echo mysqli_num_rows($result); 
                    ?> Users
                </span>
            </div>

            <!-- Toolbar -->
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search users..." onkeyup="searchTable()">
                </div>
            </div>

            <div class="table-card-body">
                <?php 
                mysqli_data_seek($result, 0);
                if(mysqli_num_rows($result) > 0) { 
                ?>
                <table class="custom-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $gradient_classes = ['gradient-1', 'gradient-2', 'gradient-3', 'gradient-4', 'gradient-5'];
                        $counter = 0;
                        
                        while($row=mysqli_fetch_assoc($result)){ 
                            $date = new DateTime($row['created_at']);
                            $initials = strtoupper(substr($row['name'], 0, 2));
                            $gradient_class = $gradient_classes[$counter % 5];
                            $counter++;
                        ?>
                        <tr>
                            <td>
                                <span class="id-badge">#<?php echo $row['id']; ?></span>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar <?php echo $gradient_class; ?>">
                                        <?php echo $initials; ?>
                                    </div>
                                    <div class="user-info">
                                        <h4><?php echo $row['name']; ?></h4>
                                        <span>Customer</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="email-cell">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:<?php echo $row['email']; ?>">
                                        <?php echo $row['email']; ?>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="phone-cell">
                                    <i class="fas fa-phone"></i>
                                    <?php echo $row['phone']; ?>
                                </div>
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
                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No Users Found</h3>
                    <p>There are no registered users at the moment.</p>
                </div>
                <?php } ?>
            </div>

            <?php 
            mysqli_data_seek($result, 0);
            if(mysqli_num_rows($result) > 0) { 
            ?>
            <div class="table-footer">
                <span class="showing-text">
                    Showing <?php echo mysqli_num_rows($result); ?> users
                </span>
                <ul class="pagination">
                    <li><a href="#"><i class="fas fa-chevron-left"></i></a></li>
                    <li class="active"><a href="#">1</a></li>
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
        const table = document.getElementById('usersTable');
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
</script>

<?php include("../includes/footer.php"); ?>