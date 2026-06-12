<?php
include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$user=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));

$plan_data=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT plan_name 
FROM bookings 
WHERE user_id='$user_id' 
AND booking_type='plan' 
ORDER BY booking_date DESC 
LIMIT 1
"));

$current_plan=$plan_data['plan_name'] ?? "Free Tier";

$stats=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
COUNT(id) total_bookings,
COALESCE(SUM(total_price),0) total_spent,
COALESCE(SUM(tickets),0) total_tickets
FROM bookings 
WHERE user_id='$user_id'
AND booking_type='event'
"));

$message="";
$error="";

/* UPDATE PROFILE */
if(isset($_POST['update'])){

$name=mysqli_real_escape_string($conn,$_POST['name']);
$phone=mysqli_real_escape_string($conn,$_POST['phone']);
$photo_sql="";

/* REMOVE PHOTO LOGIC */
if (isset($_POST['remove_photo'])) {
    if (!empty($user['profile_photo']) && file_exists("../assets/images/".$user['profile_photo'])) {
        unlink("../assets/images/".$user['profile_photo']);
    }
    $photo_sql = ", profile_photo = NULL";
}

/* PHOTO UPLOAD */
if(isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error']==0){

    $ext=strtolower(pathinfo($_FILES['profile_photo']['name'],PATHINFO_EXTENSION));
    $allowed=['jpg','jpeg','png','webp'];

    if(in_array($ext,$allowed)){
        $new_name="user_".$user_id."_".time().".".$ext;
        $path="../assets/images/".$new_name;

        if(move_uploaded_file($_FILES['profile_photo']['tmp_name'],$path)){
            $photo_sql .= ", profile_photo='$new_name'";
        }
    }
}

/* UPDATE DATABASE */
mysqli_query($conn,"
UPDATE users SET
name='$name',
phone='$phone'
$photo_sql
WHERE id='$user_id'
");

$_SESSION['name']=$name;

$message="Profile updated successfully!";

/* REFRESH USER INFO */
$user=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));

}

/* UPDATE PASSWORD */
if(isset($_POST['password'])){

$old=$_POST['old'];
$new=$_POST['new'];

if(password_verify($old,$user['password'])){

$newpass=password_hash($new,PASSWORD_DEFAULT);

mysqli_query($conn,"
UPDATE users 
SET password='$newpass'
WHERE id='$user_id'
");

$message="Password changed successfully!";

} else {
$error="Old password is incorrect!";
}

}
?>

<style>

:root {
    --pk: #ff3b7a;
    --pk-dk: #e0356c;
    --dk-bg: #0a0a1a;
    --glass: rgba(255, 255, 255, 0.05);
    --txt-muted: #7a7a9e;
}

body{
    background:linear-gradient(135deg,var(--dk-bg),#1b0033);
    color:#fff;
    font-family:'Poppins',sans-serif;
    min-height:100vh;
}

/* HERO */
.profile-hero{
    background:linear-gradient(135deg,#3a0b55,#0a0a1a);
    padding:100px 0 150px;
    text-align:center;
    margin-bottom:-100px;
}

.hero-badge{
    display:inline-block;
    background:rgba(255,59,122,0.1);
    border:1px solid var(--pk);
    color:var(--pk);
    padding:8px 20px;
    border-radius:50px;
    font-size:13px;
    font-weight:600;
    margin-bottom:15px;
}

/* GLASS CARD */
.g-card{
    background:var(--glass);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:25px;
    padding:35px;
    margin-bottom:30px;
    box-shadow:0 20px 50px rgba(0,0,0,0.3);
}

/* BADGES */
.m-badge{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:6px 16px;
    border-radius:12px;
    font-size:11px;
    font-weight:800;
    text-transform:uppercase;
    margin-bottom:15px;
}

.badge-free{
    background:rgba(255,255,255,0.1);
    color:#ccc;
    border:1px solid #444;
}

.badge-premium{
    background:rgba(255,59,122,0.2);
    color:var(--pk);
    border:1px solid var(--pk);
}

.badge-vip{
    background:rgba(251,191,36,0.2);
    color:#fbbf24;
    border:1px solid #fbbf24;
}

/* AVATAR */
.avatar{
    width:110px;
    height:110px;
    background:linear-gradient(135deg,var(--pk),#6c5cff);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:40px;
    font-weight:800;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(255,59,122,0.4);
}

.avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* STATS */
.stat-box{
    text-align:center;
    flex:1;
    border-top:1px solid rgba(255,255,255,0.1);
    padding-top:20px;
}

.stat-num{
    font-size:22px;
    font-weight:800;
    color:var(--pk);
    display:block;
}

/* INPUTS */
.f-input{
    width:100%;
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.1);
    color:#fff;
    padding:14px 20px;
    border-radius:12px;
    margin-top:8px;
}

.f-input:focus{
    outline:none;
    border-color:var(--pk);
}

/* BUTTON */
.btn-pk{
    background:linear-gradient(135deg,var(--pk),var(--pk-dk));
    border:none;
    color:#fff;
    padding:14px 30px;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

/* QUICK LINKS */
.q-link{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    background:rgba(255,255,255,0.03);
    border-radius:15px;
    text-decoration:none;
    color:#fff;
    margin-bottom:10px;
}

.q-link:hover{
    background:rgba(255,59,122,0.1);
    border-color:var(--pk);
    transform:translateX(5px);
}

/* REMOVE PHOTO CHECKBOX */
#remove_photo {
    width: 20px;
    height: 20px;
    background: var(--glass);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 4px;
    position: relative;
    appearance: none;
}

#remove_photo:checked {
    background: var(--pk);
    border-color: var(--pk);
}

#remove_photo:checked::after {
    content: '\2713';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
}

label[for="remove_photo"] {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--txt-muted);
}
</style>

<section class="profile-hero">
<div class="container">
<div class="hero-badge"><i class="fas fa-user-shield me-2"></i>ACCOUNT CENTER</div>
<h1 style="font-weight:800;font-size:42px;">Profile <span style="color:var(--pk)">Dashboard</span></h1>
</div>
</section>

<div class="container" style="padding-bottom:100px;">

<?php if($message): ?>
<div style="background:rgba(74,222,128,0.1);color:#4ade80;padding:15px;border-radius:12px;border:1px solid #4ade80;margin-bottom:20px;">
<i class="fas fa-check-circle me-2"></i><?= $message ?>
</div>
<?php endif; ?>

<?php if($error): ?>
<div style="background:rgba(248,113,113,0.1);color:#f87171;padding:15px;border-radius:12px;border:1px solid #f87171;margin-bottom:20px;">
<i class="fas fa-exclamation-circle me-2"></i><?= $error ?>
</div>
<?php endif; ?>


<div class="row g-4">

<!-- LEFT -->
<div class="col-lg-4">

<div class="g-card text-center">

<div class="avatar">
<?php if(!empty($user['profile_photo']) && file_exists("../assets/images/".$user['profile_photo'])): ?>
<img src="../assets/images/<?= $user['profile_photo'] ?>">
<?php else: ?>
<?= strtoupper(substr($user['name'],0,1)) ?>
<?php endif; ?>
</div>

<?php 
$b_type=strtolower($current_plan);
$b_class="badge-free"; $b_icon="fa-user";

if($b_type=='premium'){ $b_class="badge-premium"; $b_icon="fa-crown"; }
elseif($b_type=='vip'){ $b_class="badge-vip"; $b_icon="fa-gem"; }
?>

<div class="m-badge <?= $b_class ?>">
<i class="fas <?= $b_icon ?>"></i> <?= $current_plan ?> Member
</div>

<h3 style="font-weight:700;"><?= htmlspecialchars($user['name']) ?></h3>
<p style="color:var(--txt-muted);"><?= $user['email'] ?></p>

<div class="d-flex mt-4 gap-2">

<div class="stat-box"><span class="stat-num"><?= $stats['total_bookings'] ?></span><small>Bookings</small></div>

<div class="stat-box"><span class="stat-num"><?= $stats['total_tickets'] ?></span><small>Tickets</small></div>

<div class="stat-box"><span class="stat-num">₹<?= number_format($stats['total_spent']) ?></span><small>Spent</small></div>

</div>

</div>

<div class="g-card" style="padding:25px;">
<h5 class="mb-3" style="font-weight:700;">Quick Links</h5>

<a href="my_bookings.php" class="q-link">
<i class="fas fa-ticket-alt" style="color:var(--pk)"></i>
<div><b>My Bookings</b><br><small style="color:var(--txt-muted)">View history</small></div>
</a>

<a href="events.php" class="q-link">
<i class="fas fa-search" style="color:var(--pk)"></i>
<div><b>Browse Events</b><br><small style="color:var(--txt-muted)">Find more</small></div>
</a>

</div>

</div>

<!-- RIGHT -->
<div class="col-lg-8">

<div class="g-card">
<h4 class="mb-4"><i class="fas fa-user-edit me-2" style="color:var(--pk)"></i>Update Profile</h4>

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label class="small fw-bold text-uppercase">Full Name</label>
<input type="text" name="name" class="f-input" value="<?= htmlspecialchars($user['name']) ?>" required>
</div>

<div class="col-md-6 mb-3">
<label class="small fw-bold text-uppercase">Phone Number</label>
<input type="text" name="phone" class="f-input" value="<?= $user['phone'] ?? '' ?>">
</div>

<div class="col-md-12 mb-3">
<label class="small fw-bold text-uppercase">Profile Photo</label>
<input type="file" name="profile_photo" class="f-input" accept="image/*">
</div>

<div class="col-md-12 mb-3">
<label class="small fw-bold text-uppercase">Remove Profile Photo</label>
<input type="checkbox" name="remove_photo" id="remove_photo">
</div>

</div>

<button type="submit" name="update" class="btn-pk mt-2">Save Changes</button>

</form>
</div>


<div class="g-card">
<h4 class="mb-4"><i class="fas fa-lock me-2" style="color:#fbbf24"></i>Change Password</h4>

<form method="POST">
<div class="mb-3">
<label class="small fw-bold text-uppercase">Current Password</label>
<input type="password" name="old" class="f-input" required>
</div>

<div class="mb-3">
<label class="small fw-bold text-uppercase">New Password</label>
<input type="password" name="new" class="f-input" required>
</div>

<button type="submit" name="password" class="btn-pk" style="background:#fbbf24;color:#000;">Update Password</button>
</form>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>