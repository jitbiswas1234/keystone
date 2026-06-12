<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['user_id']))
{
header("Location: ../auth/login.php");
exit();
}

$user_id=$_SESSION['user_id'];

$booking=mysqli_query($conn,
"SELECT COUNT(*) as total FROM bookings WHERE user_id='$user_id'");

$booking_data=mysqli_fetch_assoc($booking);

?>

<!DOCTYPE html>

<html>

<head>

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">

<div class="container">

<span class="navbar-brand">

Dashboard

</span>

<a href="../auth/logout.php"
class="btn btn-danger">

Logout

</a>

</div>

</nav>

<div class="container mt-5">

<h2 class="mb-4">

Welcome <?php echo $_SESSION['name']; ?>

</h2>

<div class="row">

<div class="col-md-4">

<div class="card shadow text-center p-4">

<h3>
<?php echo $booking_data['total']; ?>
</h3>

<p>Total Bookings</p>

</div>

</div>

<div class="col-md-4">

<div class="card shadow text-center p-4">

<a href="events.php"
class="btn btn-primary">

Browse Events

</a>

</div>

</div>

<div class="col-md-4">

<div class="card shadow text-center p-4">

<a href="my_bookings.php"
class="btn btn-success">

My Bookings

</a>

</div>

</div>

</div>

</div>

</body>

</html>