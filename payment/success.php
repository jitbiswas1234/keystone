<?php

session_start();

include("../config/database.php");
include("../includes/header.php");
include("../includes/navbar.php");

$payment_id=$_GET['payment_id'] ?? 'N/A';
$booking_id=$_GET['booking_id'] ?? null;

/* Optional: verify booking exists */

if($booking_id){

$check=mysqli_fetch_assoc(

mysqli_query($conn,

"SELECT * FROM bookings WHERE id=$booking_id")

);

if(!$check){

header("Location: failed.php");

exit();

}

}

?>

<div class="container py-5 text-center">

<div class="card border-0 shadow-sm p-5 mx-auto"

style="max-width:600px;border-radius:24px;">

<div class="mb-4">

<i class="fas fa-check-circle text-success"

style="font-size:70px;"></i>

</div>

<h2 class="fw-bold">

Payment Successful

</h2>

<p class="text-muted">

Your booking is confirmed.

</p>

<div class="bg-light p-3 rounded-3 mb-4">

<small class="text-muted d-block">

Transaction ID

</small>

<span class="fw-bold text-dark">

<?php echo htmlspecialchars($payment_id); ?>

</span>

</div>

<?php if($booking_id){ ?>

<div class="bg-light p-3 rounded-3 mb-4">

<small class="text-muted d-block">

Booking ID

</small>

<span class="fw-bold text-dark">

<?php echo htmlspecialchars($booking_id); ?>

</span>

</div>

<?php } ?>

<a href="../user/my_bookings.php"

class="btn btn-primary w-100 py-3 fw-bold mb-3"

style="border-radius:12px;background:#ff3b7a;border:none;">

Go to My Bookings

</a>

<p class="small text-muted">

Redirecting in 5 seconds...

</p>

</div>

</div>

<script>

setTimeout(function(){

window.location.href="../user/my_bookings.php";

},5000);

</script>

<?php include("../includes/footer.php"); ?>