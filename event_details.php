<?php
session_start();
include("config/database.php");

// ─── Redirect if no ID ───
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id']; // Cast to int for safety

// ─── Fetch Event ───
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header("Location: index.php");
    exit();
}

// ─── Handle Review Submission ───
if (isset($_POST['review']) && isset($_SESSION['user_id'])) {
    $rating  = (int) $_POST['rating'];
    $comment = trim($_POST['comment']);
    $user    = $_SESSION['user_id'];

    // Validate rating range
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, event_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user, $id, $rating, $comment);
        $stmt->execute();
    }

    header("Location: event_details.php?id=$id&status=success");
    exit();
}

// ─── Average Rating ───
$avg_query = $conn->prepare("
    SELECT COALESCE(AVG(rating), 0) AS avg_rating,
           COUNT(id) AS total_reviews
    FROM reviews WHERE event_id = ?
");
$avg_query->bind_param("i", $id);
$avg_query->execute();
$data = $avg_query->get_result()->fetch_assoc();

// ─── Fetch Reviews ───
$reviews_stmt = $conn->prepare("
    SELECT reviews.*, users.name 
    FROM reviews 
    JOIN users ON reviews.user_id = users.id 
    WHERE event_id = ? 
    ORDER BY reviews.id DESC
");
$reviews_stmt->bind_param("i", $id);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();

// ─── Helper values ───
$img         = !empty($event['image']) ? $event['image'] : 'default.jpg';
$avg_rating  = round($data['avg_rating'], 1);
$total_rev   = $data['total_reviews'];
$full_stars  = floor($avg_rating);
$seats       = $event['available_seats'];
$is_logged   = isset($_SESSION['user_id']);

// ─── Includes ───
include("includes/header.php");
include("includes/navbar.php");
?>

<!-- ══════════════════════════════════════════════
     STYLES
     ══════════════════════════════════════════════ -->
<style>
    /* ── CSS Variables ── */
    :root {
        --pink:       #ff3b7a;
        --pink-hover: #e0356c;
        --pink-light: rgba(255, 59, 122, 0.08);
        --dark:       #080712;
        --gray-50:    #f8f9fa;
        --gray-100:   #f1f3f5;
        --gray-400:   #adb5bd;
        --gray-600:   #6c757d;
        --radius-sm:  10px;
        --radius-md:  16px;
        --radius-lg:  20px;
        --radius-xl:  24px;
        --shadow-sm:  0 4px 12px rgba(0,0,0,0.04);
        --shadow-md:  0 10px 30px rgba(0,0,0,0.06);
        --shadow-lg:  0 20px 40px rgba(0,0,0,0.10);
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ── Hero Image ── */
    .hero-image-wrapper {
        position: relative;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .hero-image-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
        pointer-events: none;
    }

    .event-header-img {
        width: 100%;
        height: 460px;
        object-fit: cover;
        display: block;
        transition: transform 0.6s ease;
    }

    .hero-image-wrapper:hover .event-header-img {
        transform: scale(1.03);
    }

    /* ── Category Badge ── */
    .badge-category {
        background: var(--dark);
        color: #fff;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* ── Rating Badge ── */
    .rating-badge {
        background: #fff9e6;
        color: #d4a017;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 700;
        border: 1px solid #ffeeba;
        font-size: 0.9rem;
    }

    /* ── Detail Card (Sidebar) ── */
    .detail-card {
        background: #fff;
        border: none;
        border-radius: var(--radius-xl);
        padding: 32px;
        box-shadow: var(--shadow-md);
    }

    /* ── Info Icon ── */
    .info-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        background: var(--pink-light);
        color: var(--pink);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-sm);
        font-size: 1rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
    }

    .info-row + .info-row {
        border-top: 1px solid var(--gray-100);
    }

    /* ── Book Button ── */
    .btn-book {
        background: linear-gradient(135deg, var(--pink), #ff6b9d);
        color: #fff;
        border: none;
        padding: 16px;
        font-weight: 700;
        font-size: 1.05rem;
        border-radius: 14px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-book::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-book:hover {
        background: linear-gradient(135deg, var(--pink-hover), #ff5a8f);
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(255, 59, 122, 0.35);
        color: #fff;
    }

    .btn-book:hover::before {
        left: 100%;
    }

    /* ── Sticky Sidebar ── */
    .sticky-booking {
        position: sticky;
        top: 90px;
    }

    /* ── Review Card ── */
    .review-card {
        background: #fff;
        border: none;
        border-radius: var(--radius-md);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        border-left: 4px solid transparent;
    }

    .review-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-left-color: var(--pink);
    }

    /* ── Avatar ── */
    .user-avatar {
        width: 46px;
        height: 46px;
        min-width: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        font-size: 1rem;
    }

    /* ── Stats Card (dark) ── */
    .stats-card {
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        color: #fff;
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }

    .stats-card .display-rating {
        font-size: 3rem;
        font-weight: 800;
        color: var(--pink);
        line-height: 1;
    }

    /* ── Review Form ── */
    .review-form-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
    }

    .review-form-card textarea {
        border: 2px solid var(--gray-100);
        border-radius: var(--radius-sm);
        resize: none;
        transition: var(--transition);
    }

    .review-form-card textarea:focus {
        border-color: var(--pink);
        box-shadow: 0 0 0 3px var(--pink-light);
    }

    /* ── Star Radio Buttons ── */
    .star-select .btn-check:checked + .btn-outline-warning {
        background: #ffc107;
        color: #000;
        border-color: #ffc107;
        box-shadow: 0 4px 8px rgba(255,193,7,0.3);
    }

    .star-select .btn-outline-warning {
        border-radius: 50px !important;
        padding: 6px 16px;
        font-weight: 600;
        transition: var(--transition);
    }

    /* ── Section Divider ── */
    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(to right, var(--pink-light), transparent);
        margin: 3rem 0;
    }

    /* ── Help Card ── */
    .help-card {
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        background: #fff;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .help-card:hover {
        box-shadow: var(--shadow-md);
    }

    .help-card .icon-circle {
        width: 48px;
        height: 48px;
        background: var(--pink-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pink);
        font-size: 1.1rem;
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--gray-400);
        margin-bottom: 16px;
    }

    /* ── Success Alert ── */
    .alert-custom {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: none;
        border-radius: 14px;
        color: #155724;
        font-weight: 600;
        padding: 16px 24px;
        box-shadow: var(--shadow-sm);
    }

    /* ── About Section ── */
    .about-text {
        font-size: 1.05rem;
        line-height: 1.85;
        color: var(--gray-600);
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .event-header-img {
            height: 300px;
        }

        .sticky-booking {
            position: static;
        }

        .detail-card {
            padding: 24px;
        }
    }

    @media (max-width: 576px) {
        .event-header-img {
            height: 220px;
        }

        .detail-card {
            padding: 20px;
        }
    }
</style>

<!-- ══════════════════════════════════════════════
     MAIN CONTENT
     ══════════════════════════════════════════════ -->
<div class="container py-5">

    <!-- ── Success Message ── -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-custom d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            Your review has been submitted successfully!
        </div>
    <?php endif; ?>

    <!-- ── Breadcrumb ── -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item">
                <a href="index.php" class="text-decoration-none text-muted">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="user/events.php" class="text-decoration-none text-muted">Events</a>
            </li>
            <li class="breadcrumb-item active fw-semibold"><?php echo htmlspecialchars($event['title']); ?></li>
        </ol>
    </nav>

    <!-- ═══════════════════════════════════════════
         TWO COLUMN LAYOUT
         ═══════════════════════════════════════════ -->
    <div class="row g-5">

        <!-- ── LEFT COLUMN: Event Details ── -->
        <div class="col-lg-7">

            <!-- Hero Image -->
            <div class="hero-image-wrapper mb-4">
                <img 
                    src="assets/images/<?php echo htmlspecialchars($img); ?>" 
                    class="event-header-img" 
                    alt="<?php echo htmlspecialchars($event['title']); ?>"
                >
            </div>

            <!-- Badges -->
            <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                <span class="badge-category">
                    <?php echo htmlspecialchars($event['category']); ?>
                </span>
                <span class="rating-badge">
                    <i class="fas fa-star me-1"></i>
                    <?php echo $avg_rating; ?>
                    <small class="text-muted fw-normal ms-1">(<?php echo $total_rev; ?> Reviews)</small>
                </span>
            </div>

            <!-- Title -->
            <h1 class="display-5 fw-bold mb-4">
                <?php echo htmlspecialchars($event['title']); ?>
            </h1>

            <!-- About -->
            <div class="mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-muted me-2" style="font-size:0.9rem;"></i>
                    About This Event
                </h5>
                <p class="about-text">
                    <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                </p>
            </div>

            <hr class="section-divider">

            <!-- ═══════════════════════════════════
                 REVIEWS SECTION
                 ═══════════════════════════════════ -->
            <section id="reviews">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0">
                        <i class="fas fa-comments me-2" style="color:var(--pink);"></i>
                        Community Feedback
                    </h4>
                    <?php if (!$is_logged): ?>
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>Login to write a review
                        </small>
                    <?php endif; ?>
                </div>

                <div class="row g-4">

                    <!-- ── Left: Stats + Form ── -->
                    <div class="col-md-5">
                        <div class="sticky-top" style="top:100px; z-index:1;">

                            <!-- Rating Stats -->
                            <div class="stats-card mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="display-rating"><?php echo $avg_rating; ?></span>
                                    <div>
                                        <div class="text-warning mb-1">
                                            <?php
                                                echo str_repeat("★", $full_stars);
                                                echo str_repeat("☆", 5 - $full_stars);
                                            ?>
                                        </div>
                                        <small class="opacity-75">
                                            <?php echo $total_rev; ?> Verified Reviews
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Form / Login Prompt -->
                            <?php if ($is_logged): ?>
                                <div class="review-form-card">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-pen me-2" style="color:var(--pink);"></i>
                                        Rate Your Experience
                                    </h6>
                                    <form method="POST" action="">
                                        <div class="mb-3 star-select d-flex flex-wrap gap-2">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input 
                                                    type="radio" 
                                                    name="rating" 
                                                    value="<?php echo $i; ?>" 
                                                    id="star<?php echo $i; ?>" 
                                                    class="btn-check" 
                                                    <?php if ($i == 5) echo 'checked'; ?>
                                                >
                                                <label 
                                                    class="btn btn-outline-warning btn-sm" 
                                                    for="star<?php echo $i; ?>"
                                                >
                                                    <?php echo $i; ?> ★
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                        <textarea 
                                            name="comment" 
                                            class="form-control mb-3" 
                                            rows="3" 
                                            placeholder="What did you love about this event?" 
                                            required
                                        ></textarea>
                                        <button 
                                            name="review" 
                                            type="submit" 
                                            class="btn btn-dark w-100 rounded-pill fw-bold py-2"
                                        >
                                            <i class="fas fa-paper-plane me-2"></i>Submit Review
                                        </button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="review-form-card text-center py-4">
                                    <i class="fas fa-user-lock fs-2 text-muted mb-3 d-block"></i>
                                    <p class="small text-muted mb-3">Want to share your thoughts?</p>
                                    <a href="auth/login.php" class="btn btn-sm btn-outline-dark rounded-pill px-4">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login to Review
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ── Right: Review List ── -->
                    <div class="col-md-7">
                        <?php
                        $avatar_colors = ['#ff3b7a','#6610f2','#fd7e14','#20c997','#0dcaf0','#6f42c1','#e83e8c'];

                        if (mysqli_num_rows($reviews) > 0):
                            while ($r = mysqli_fetch_assoc($reviews)):
                                $bg      = $avatar_colors[array_rand($avatar_colors)];
                                $initial = strtoupper(substr($r['name'], 0, 1));
                        ?>
                            <div class="review-card mb-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="user-avatar me-3" style="background:<?php echo $bg; ?>;">
                                        <?php echo $initial; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0">
                                            <?php echo htmlspecialchars($r['name']); ?>
                                        </h6>
                                        <small class="text-muted" style="font-size:0.75rem;">
                                            <i class="far fa-clock me-1"></i>
                                            <?php echo date("M d, Y", strtotime($r['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="text-warning small">
                                        <?php
                                            echo str_repeat("★", $r['rating']);
                                            echo str_repeat("☆", 5 - $r['rating']);
                                        ?>
                                    </div>
                                </div>
                                <p class="text-secondary mb-0" style="line-height:1.7;">
                                    "<?php echo htmlspecialchars($r['comment']); ?>"
                                </p>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <div class="empty-state">
                                <i class="fas fa-comment-slash d-block"></i>
                                <h6 class="text-muted">No reviews yet</h6>
                                <p class="small text-muted">Be the first to share your experience!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>

        <!-- ── RIGHT COLUMN: Booking Sidebar ── -->
        <div class="col-lg-5">
            <div class="sticky-booking">

                <!-- Booking Card -->
                <div class="detail-card">

                    <!-- Price -->
                    <div class="mb-4 pb-3 border-bottom">
                        <small class="text-muted text-uppercase fw-semibold d-block mb-1" style="letter-spacing:1px;">
                            Ticket Price
                        </small>
                        <h2 class="fw-bold text-success m-0">
                            ₹ <?php echo number_format($event['price']); ?>
                            <span class="fs-6 text-muted fw-normal">/ person</span>
                        </h2>
                    </div>

                    <!-- Event Info -->
                    <div class="mb-4">
                        <div class="info-row">
                            <div class="info-icon"><i class="far fa-calendar-alt"></i></div>
                            <div>
                                <small class="text-muted d-block">Date</small>
                                <span class="fw-bold">
                                    <?php echo date("l, d M Y", strtotime($event['event_date'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon"><i class="far fa-clock"></i></div>
                            <div>
                                <small class="text-muted d-block">Time</small>
                                <span class="fw-bold">
                                    <?php echo date("h:i A", strtotime($event['event_time'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <small class="text-muted d-block">Venue</small>
                                <span class="fw-bold">
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon"><i class="fas fa-chair"></i></div>
                            <div>
                                <small class="text-muted d-block">Availability</small>
                                <span class="fw-bold <?php echo ($seats < 10) ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo $seats; ?> Seats Remaining
                                    <?php if ($seats < 10): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-2" style="font-size:0.7rem;">
                                            Filling Fast!
                                        </span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <?php if ($is_logged): ?>
                        <a href="payment/book_event.php?id=<?php echo $event['id']; ?>" class="btn btn-book w-100 mb-3">
                            <i class="fas fa-ticket-alt me-2"></i>Book Your Spot Now
                        </a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-warning w-100 fw-bold py-3 rounded-3 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Book Tickets
                        </a>
                    <?php endif; ?>

                    <!-- Trust Badge -->
                    <p class="text-center small text-muted mb-0">
                        <i class="fas fa-shield-alt me-1 text-success"></i>
                        Secure checkout powered by KeyStone
                    </p>
                </div>

                <!-- Help Card -->
                <div class="help-card mt-4 d-flex align-items-center gap-3">
                    <div class="icon-circle">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold small">Need help?</h6>
                        <small class="text-muted">Contact organizer for bulk bookings</small>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted small"></i>
                </div>
            </div>
        </div>

    </div><!-- /row -->
</div><!-- /container -->

<?php include("includes/footer.php"); ?>