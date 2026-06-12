<?php
include("config/database.php");
include("includes/header.php");
include("includes/navbar.php");

$success = "";
$error = "";

if (isset($_POST['send'])) {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $message = $_POST['message'];

    // Using Prepared Statements for better security
    $stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $success = "Message sent successfully! We'll be in touch soon.";
    } else {
        $error = "Error sending message. Please try again later.";
    }
    $stmt->close();
}
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
        margin: 0;
    }

    /* ========== HERO HEADER ========== */
    .contact-hero {
        background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-dark) 50%, var(--dark-bg) 100%);
        padding: 100px 0 140px;
        position: relative;
        overflow: hidden;
        margin-bottom: -80px;
    }

    .contact-hero::before {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 59, 122, 0.15), transparent 70%);
        top: -200px;
        right: -150px;
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(20px, -20px) rotate(3deg); }
        66% { transform: translate(-15px, 15px) rotate(-3deg); }
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        animation: fadeInDown 1s ease;
    }

    .hero-badge {
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

    .hero-title {
        font-size: 56px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #ffffff, #e0e0ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-title .highlight {
        background: linear-gradient(135deg, var(--pink-primary), #ff6b9d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* ========== CONTACT SECTION ========== */
    .contact-section {
        position: relative;
        z-index: 100;
        padding: 0 0 100px;
    }

    .success-alert {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 15px;
        color: #4ade80;
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 45px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        color: var(--text-light);
        font-size: 13px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .form-group input, .form-group textarea {
        width: 100%;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 15px;
        border-radius: 12px;
        outline: none;
    }

    .form-group input:focus { border-color: var(--pink-primary); }

    .btn-submit {
        background: linear-gradient(135deg, var(--pink-primary), var(--pink-dark));
        border: none;
        color: white;
        padding: 16px 40px;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 59, 122, 0.4); }

    .info-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 45px;
        height: 100%;
    }

    .contact-info-item {
        display: flex;
        gap: 15px;
        background: rgba(255, 255, 255, 0.03);
        padding: 15px;
        border-radius: 15px;
        margin-bottom: 15px;
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 59, 122, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: var(--pink-primary);
    }

    .social-links { display: flex; gap: 10px; margin-top: 20px; }
    .social-link {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        border-radius: 10px; color: #fff; text-decoration: none;
    }

    .map-section {
        margin-top: 50px;
        background: rgba(255,255,255,0.05);
        padding: 30px;
        border-radius: 25px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .map-container { height: 400px; border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); }
</style>

<section class="contact-hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">● GET IN TOUCH</div>
            <h1 class="hero-title">Let's Start a <span class="highlight">Conversation</span></h1>
            <p class="hero-subtitle">Have questions? We're here to help you create unforgettable experiences.</p>
        </div>
    </div>
</section>

<div class="contact-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="form-card">
                    <h2>Send Us a Message</h2>
                    <p class="subtitle">Fill out the form below and we'll get back to you within 24 hours.</p>

                    <?php if($success != ""): ?>
                        <div class="success-alert">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $success; ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" name="name" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="john@example.com" required>
                        </div>

                        <div class="form-group">
                            <label>Your Message</label>
                            <textarea name="message" placeholder="Tell us how we can help you..." required></textarea>
                        </div>

                        <button type="submit" name="send" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="info-card">
                    <div class="logo-section" style="text-align: center; margin-bottom: 30px;">
                        <h4><span style="color: var(--pink-primary);">KeyStone</span> Events</h4>
                        <p class="tagline">Creating lasting memories through amazing events.</p>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <small style="color: var(--text-muted); display:block;">Email</small>
                            <strong>support@keystone.com</strong>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <small style="color: var(--text-muted); display:block;">Phone</small>
                            <strong>+91 8101688252</strong>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <small style="color: var(--text-muted); display:block;">Location</small>
                            <strong>Kolkata, West Bengal, India</strong>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="map-section">
            <h3 class="text-center mb-4"><i class="fas fa-map-marked-alt text-pink"></i> Find Us Here</h3>
            <div class="map-container">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=88.25,22.45,88.45,22.65&layer=mapnik&marker=22.5726,88.3639" style="border: 0"></iframe>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>