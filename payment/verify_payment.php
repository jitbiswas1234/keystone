<?php

session_start();

include("../config/database.php");

// Basic Security
if(!isset($_GET['payment_id']) || !isset($_SESSION['user_id'])){

header("Location: ../index.php");

exit();

}

$payment_id=$_GET['payment_id'];

$user_id=$_SESSION['user_id'];

$type=$_GET['type'] ?? 'event';

$total=$_SESSION['total'] ?? 0;

// Generate transaction id
$transaction_id="TXN".time().rand(100,999);

if($type==="plan"){

$plan_name=$_GET['plan'];

$b_type='plan';

$stmt=$conn->prepare(

"INSERT INTO bookings
(user_id,plan_name,total_price,
razorpay_payment_id,transaction_id,
booking_type,payment_status)

VALUES (?,?,?,?,?,?, 'Paid')"

);

$stmt->bind_param("isdsss",

$user_id,
$plan_name,
$total,
$payment_id,
$transaction_id,
$b_type

);

if($stmt->execute()){

$booking_id=$conn->insert_id;

// Generate booking code
$booking_code="BK".date("ymd").
str_pad($booking_id,4,"0",STR_PAD_LEFT);

// Update booking code
$update=$conn->prepare(

"UPDATE bookings SET booking_code=? WHERE id=?"

);

$update->bind_param("si",

$booking_code,
$booking_id

);

$update->execute();

$_SESSION['success']="Plan activated successfully!";

header("Location: success.php?payment_id=$payment_id&booking_id=$booking_id");

exit();

}

}

else{

$event_id=$_SESSION['event_id'];

$tickets=$_SESSION['tickets'];

$b_type='event';

$stmt=$conn->prepare(

"INSERT INTO bookings
(user_id,event_id,tickets,total_price,
razorpay_payment_id,transaction_id,
booking_type,payment_status)

VALUES (?,?,?,?,?,?,?, 'Paid')"

);

$stmt->bind_param("iiiisss",

$user_id,
$event_id,
$tickets,
$total,
$payment_id,
$transaction_id,
$b_type

);

if($stmt->execute()){

$booking_id=$conn->insert_id;

// Generate booking code
$booking_code="BK".date("ymd").
str_pad($booking_id,4,"0",STR_PAD_LEFT);

// Update booking code
$update=$conn->prepare(

"UPDATE bookings SET booking_code=? WHERE id=?"

);

$update->bind_param("si",

$booking_code,
$booking_id

);

$update->execute();

// Update seats safely
$seat=$conn->prepare(

"UPDATE events 
SET available_seats=available_seats-?
WHERE id=?"

);

$seat->bind_param("ii",

$tickets,
$event_id

);

$seat->execute();

// Clean session
unset($_SESSION['event_id']);

unset($_SESSION['tickets']);

unset($_SESSION['total']);

header("Location: success.php?payment_id=$payment_id&booking_id=$booking_id");

exit();

}

}

?>