<?php
include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : "";

$sql = "SELECT * FROM events WHERE 1";
if($search != "") {
    $sql .= " AND (title LIKE '%$search%' OR location LIKE '%$search%')";
}
if($category != "") {
    $sql .= " AND category='$category'";
}
$sql .= " ORDER BY event_date ASC";
$result = mysqli_query($conn, $sql);
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
    .header-gradient {
        background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-dark) 50%, var(--dark-bg) 100%);
        padding: 100px 0 140px;
        position: relative;
        overflow: hidden;
    }

    /* Animated Background Blobs */
    .header-gradient::before {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 59, 122, 0.12), transparent 70%);
        top: -200px;
        right: -150px;
        animation: float 8s ease-in-out infinite;
    }

    .header-gradient::after {
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
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(20px, -20px) rotate(3deg); }
        66% { transform: translate(-15px, 15px) rotate(-3deg); }
    }

    .header-content {
        position: relative;
        z-index: 10;
        text-align: center;
        animation: fadeInDown 1s ease;
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
        font-size: 56px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .header-title .highlight {
        background: linear-gradient(135deg, var(--pink-primary), #ff6b9d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-subtitle {
        font-size: 18px;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
    }

    /* ========== SEARCH WRAPPER ========== */
    .search-wrapper {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 25px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        margin-top: -80px;
        position: relative;
        z-index: 100;
        animation: slideUp 0.8s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .search-wrapper label {
        color: var(--text-light);
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 16px;
        z-index: 10;
    }

    .search-input-group input {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 15px 20px 15px 50px;
        border-radius: 15px;
        transition: all 0.3s ease;
        font-size: 15px;
    }

    .search-input-group input:focus {
        background: rgba(255, 255, 255, 0.12);
        border-color: var(--pink-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 59, 122, 0.15);
    }

    .search-input-group input::placeholder {
        color: var(--text-muted);
    }

    .search-wrapper select {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 15px 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
        font-size: 15px;
        cursor: pointer;
    }

    .search-wrapper select:focus {
        background: rgba(255, 255, 255, 0.12);
        border-color: var(--pink-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 59, 122, 0.15);
    }

    .search-wrapper select option {
        background: var(--purple-dark);
        color: #ffffff;
    }

    .btn-search {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
        padding: 15px 35px;
        border-radius: 15px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(255, 59, 122, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-search::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-search:hover::before {
        left: 100%;
    }

    .btn-search:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(255, 59, 122, 0.5);
    }

    /* ========== RESULTS HEADER ========== */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .results-count {
        color: var(--text-light);
        font-size: 16px;
    }

    .results-count strong {
        color: var(--pink-primary);
        font-size: 20px;
        font-weight: 800;
    }

    /* ========== EVENT CARDS ========== */
    .event-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 25px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        position: relative;
        animation: cardReveal 0.6s ease;
    }

    @keyframes cardReveal {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .event-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--pink-primary), #6c5cff);
        transform: scaleX(0);
        transition: transform 0.5s ease;
    }

    .event-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 25px 60px rgba(255, 59, 122, 0.25);
        border-color: rgba(255, 59, 122, 0.3);
    }

    .event-card:hover::before {
        transform: scaleX(1);
    }

    /* Image Container */
    .img-container {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, #2a1a3e, #1a0b2e);
    }

    .event-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        filter: brightness(0.85);
    }

    .event-card:hover img {
        transform: scale(1.15) rotate(2deg);
        filter: brightness(1);
    }

    /* Price Tag with Glassmorphism */
    .price-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        padding: 10px 18px;
        border-radius: 15px;
        color: #ffffff;
        font-weight: 800;
        font-size: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    /* Card Body */
    .event-card .card-body {
        padding: 25px;
    }

    /* Category Badge */
    .category-badge {
        background: linear-gradient(135deg, rgba(255, 59, 122, 0.2), rgba(108, 92, 255, 0.15));
        border: 1px solid rgba(255, 59, 122, 0.3);
        color: #ff6b9d;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Date Badge */
    .date-badge {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
    }

    .date-badge i {
        color: var(--pink-primary);
    }

    /* Event Title */
    .event-card h5 {
        color: #ffffff;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Location */
    .event-location {
        color: var(--text-light);
        font-size: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .event-location i {
        color: var(--pink-primary);
    }

    /* Seats Info */
    .seats-info {
        color: #4ade80;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .seats-info i {
        font-size: 16px;
    }

    .seats-info.limited {
        color: #fbbf24;
    }

    .seats-info.critical {
        color: #ef4444;
    }

    /* View Details Button */
    .btn-view-details {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-view-details::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-view-details:hover::before {
        left: 100%;
    }

    .btn-view-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 59, 122, 0.4);
        color: white;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .empty-state-icon {
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 64px;
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

    .btn-clear-filters {
        background: transparent;
        border: 2px solid rgba(255, 59, 122, 0.3);
        color: var(--pink-primary);
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-clear-filters:hover {
        background: var(--pink-primary);
        border-color: var(--pink-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .header-title {
            font-size: 42px;
        }

        .search-wrapper {
            padding: 30px;
            margin-top: -60px;
        }
    }

    @media (max-width: 768px) {
        .header-gradient {
            padding: 80px 0 120px;
        }

        .header-title {
            font-size: 34px;
        }

        .header-subtitle {
            font-size: 16px;
        }

        .search-wrapper {
            padding: 25px;
            margin-top: -50px;
        }

        .btn-search {
            width: 100%;
            margin-top: 10px;
        }

        .results-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .img-container {
            height: 200px;
        }
    }

    @media (max-width: 576px) {
        .header-title {
            font-size: 28px;
        }

        .price-tag {
            font-size: 14px;
            padding: 8px 14px;
        }

        .event-card h5 {
            font-size: 17px;
        }
    }

    /* ========== CARD STAGGER ANIMATION ========== */
    .event-card:nth-child(1) { animation-delay: 0.1s; }
    .event-card:nth-child(2) { animation-delay: 0.2s; }
    .event-card:nth-child(3) { animation-delay: 0.3s; }
    .event-card:nth-child(4) { animation-delay: 0.4s; }
    .event-card:nth-child(5) { animation-delay: 0.5s; }
    .event-card:nth-child(6) { animation-delay: 0.6s; }
</style>

<!-- HEADER SECTION -->
<section class="header-gradient">
    <div class="container">
        <div class="header-content">
            <div class="header-badge">
                ● DISCOVER EVENTS
            </div>
            <h1 class="header-title">
                Experience <span class="highlight">Unforgettable</span><br>Events
            </h1>
            <p class="header-subtitle">
                Find the best concerts, tech summits, workshops, and sports events happening near you.
            </p>
        </div>
    </div>
</section>

<!-- SEARCH & FILTER SECTION -->
<div class="container">
    <div class="search-wrapper mb-5">
        <form method="GET" class="row g-4">
            <div class="col-lg-5 col-md-6">
                <label>Search Events</label>
                <div class="search-input-group">
                    <i class="fas fa-search input-icon"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search by name or location..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <label>Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Tech" <?php if($category=="Tech") echo 'selected'; ?>>Tech</option>
                    <option value="Music" <?php if($category=="Music") echo 'selected'; ?>>Music</option>
                    <option value="Workshop" <?php if($category=="Workshop") echo 'selected'; ?>>Workshop</option>
                    <option value="Sports" <?php if($category=="Sports") echo 'selected'; ?>>Sports</option>
                </select>
            </div>
            
            <div class="col-lg-3 col-md-12 d-flex align-items-end">
                <button type="submit" class="btn btn-search w-100">
                    <i class="fas fa-filter me-2"></i>Find Events
                </button>
            </div>
        </form>
    </div>

    <!-- RESULTS HEADER -->
    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="results-header">
            <div class="results-count">
                Found <strong><?php echo mysqli_num_rows($result); ?></strong> 
                <?php echo (mysqli_num_rows($result) == 1) ? 'event' : 'events'; ?>
                <?php if($search || $category): ?>
                    matching your search
                <?php endif; ?>
            </div>
            <?php if($search || $category): ?>
                <a href="events.php" class="btn-clear-filters">
                    <i class="fas fa-times me-2"></i>Clear Filters
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- EVENTS GRID -->
    <div class="row g-4 mb-5">
        <?php if(mysqli_num_rows($result) > 0) { 
            while($event = mysqli_fetch_assoc($result)) { 
                $seats = $event['available_seats'];
                $seats_class = $seats > 50 ? '' : ($seats > 20 ? 'limited' : 'critical');
        ?>
            <div class="col-lg-4 col-md-6">
                <div class="card event-card border-0 shadow-sm">
                    <div class="img-container">
                        <div class="price-tag">
                            ₹ <?php echo number_format($event['price']); ?>
                        </div>
                        <?php $img = !empty($event['image']) ? $event['image'] : 'default.jpg'; ?>
                        <img src="../assets/images/<?php echo $img; ?>" 
                             alt="<?php echo htmlspecialchars($event['title']); ?>">
                    </div>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="category-badge">
                                <?php echo htmlspecialchars($event['category']); ?>
                            </span>
                            <div class="date-badge">
                                <i class="far fa-calendar-alt me-1"></i>
                                <?php echo date("M d, Y", strtotime($event['event_date'])); ?>
                            </div>
                        </div>
                        
                        <h5><?php echo htmlspecialchars($event['title']); ?></h5>
                        
                        <p class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($event['location']); ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="seats-info <?php echo $seats_class; ?>">
                                <i class="fas fa-users"></i>
                                <?php echo $seats; ?> Seats Left
                            </div>
                            <a href="../event_details.php?id=<?php echo $event['id']; ?>" 
                               class="btn btn-view-details">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } 
        } else { ?>
            <!-- EMPTY STATE -->
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>No Events Found</h4>
                    <p>We couldn't find any events matching your search criteria.<br>Try adjusting your filters or browse all events.</p>
                    <a href="events.php" class="btn-clear-filters">
                        <i class="fas fa-redo me-2"></i>View All Events
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include("../includes/footer.php"); ?>