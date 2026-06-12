<?php

include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

$sql="SELECT * FROM events ORDER BY id DESC";

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

    /* Add Button */
    .btn-add {
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
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        color: white;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

    .table-card-header .event-count {
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
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: #f8fafc;
    }

    .custom-table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* Event Title Cell */
    .event-title-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .event-thumb {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #f1f5f9;
    }

    .event-title-info h4 {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 4px 0;
    }

    .event-title-info span {
        font-size: 12px;
        color: #94a3b8;
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
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    /* Location Cell */
    .location-cell {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 14px;
    }

    .location-cell i {
        color: #ef4444;
    }

    /* Price Cell */
    .price-badge {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1));
        color: #059669;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        display: inline-block;
    }

    /* Seats Cell */
    .seats-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .seats-progress {
        width: 60px;
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .seats-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        border-radius: 3px;
    }

    .seats-text {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
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

    .btn-edit {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .btn-edit:hover {
        background: #f59e0b;
        color: white;
        transform: translateY(-2px);
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

    .btn-view {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .btn-view:hover {
        background: #6366f1;
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

    /* Pagination */
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
    @media (max-width: 1024px) {
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
        }

        .stats-row {
            grid-template-columns: 1fr;
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
                    <i class="fas fa-calendar-alt"></i>
                    Manage Events
                </h1>
                <nav class="breadcrumb-nav">
                    <a href="dashboard.php">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <span>Events</span>
                </nav>
            </div>

            <a href="create_event.php" class="btn-add">
                <i class="fas fa-plus"></i>
                Add New Event
            </a>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-mini-card">
                <div class="stat-mini-icon purple">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php echo mysqli_num_rows($result); ?></h3>
                    <p>Total Events</p>
                </div>
            </div>

            <div class="stat-mini-card">
                <div class="stat-mini-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php 
                        $active = mysqli_query($conn, "SELECT COUNT(*) as total FROM events WHERE event_date >= CURDATE()");
                        $active_count = mysqli_fetch_assoc($active);
                        echo $active_count['total'];
                    ?></h3>
                    <p>Active Events</p>
                </div>
            </div>

            <div class="stat-mini-card">
                <div class="stat-mini-icon orange">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-mini-info">
                    <h3><?php 
                        $past = mysqli_query($conn, "SELECT COUNT(*) as total FROM events WHERE event_date < CURDATE()");
                        $past_count = mysqli_fetch_assoc($past);
                        echo $past_count['total'];
                    ?></h3>
                    <p>Past Events</p>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    All Events
                </h2>
                <span class="event-count">
                    <?php 
                    mysqli_data_seek($result, 0);
                    echo mysqli_num_rows($result); 
                    ?> Events
                </span>
            </div>

            <!-- Toolbar -->
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search events..." onkeyup="searchTable()">
                </div>

                <div class="filter-btns">
                    <button class="filter-btn active">
                        <i class="fas fa-th-list"></i>
                        All
                    </button>
                    <button class="filter-btn">
                        <i class="fas fa-clock"></i>
                        Upcoming
                    </button>
                    <button class="filter-btn">
                        <i class="fas fa-check"></i>
                        Completed
                    </button>
                </div>
            </div>

            <div class="table-card-body">
                <?php if(mysqli_num_rows($result) > 0) { ?>
                <table class="custom-table" id="eventsTable">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Seats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        mysqli_data_seek($result, 0);
                        while($row=mysqli_fetch_assoc($result)){ 
                            $date = new DateTime($row['event_date']);
                        ?>
                        <tr>
                            <td>
                                <div class="event-title-cell">
                                    <?php if(!empty($row['image'])){ ?>
                                    <img src="../assets/images/<?php echo $row['image']; ?>" alt="" class="event-thumb">
                                    <?php } else { ?>
                                    <div class="event-thumb" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #cbd5e1;"></i>
                                    </div>
                                    <?php } ?>
                                    <div class="event-title-info">
                                        <h4><?php echo $row['title']; ?></h4>
                                        <span>ID: #<?php echo $row['id']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-icon">
                                        <span class="day"><?php echo $date->format('d'); ?></span>
                                        <span><?php echo $date->format('M'); ?></span>
                                    </div>
                                    <span class="date-text"><?php echo $date->format('D, Y'); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="location-cell">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo $row['location']; ?>
                                </div>
                            </td>
                            <td>
                                <span class="price-badge">₹<?php echo $row['price']; ?></span>
                            </td>
                            <td>
                                <div class="seats-cell">
                                    <?php 
                                    $total = $row['total_seats'];
                                    $available = $row['available_seats'];
                                    $percentage = ($available / $total) * 100;
                                    ?>
                                    <div class="seats-progress">
                                        <div class="seats-progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <span class="seats-text"><?php echo $available; ?>/<?php echo $total; ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="view_event.php?id=<?php echo $row['id']; ?>" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this event?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Events Found</h3>
                    <p>You haven't created any events yet. Start by adding your first event!</p>
                    <a href="create_event.php" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Create First Event
                    </a>
                </div>
                <?php } ?>
            </div>

            <?php if(mysqli_num_rows($result) > 0) { ?>
            <div class="table-footer">
                <span class="showing-text">
                    Showing <?php mysqli_data_seek($result, 0); echo mysqli_num_rows($result); ?> events
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
        const table = document.getElementById('eventsTable');
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

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this event?')) {
                e.preventDefault();
            }
        });
    });
</script>

<?php include("../includes/footer.php"); ?>