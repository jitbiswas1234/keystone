<?php

session_start();

include("../config/database.php");

header("Content-Type: application/json");

$message=strtolower(trim($_POST['message'] ?? ''));

$user_id=$_SESSION['user_id'] ?? null;

$user_name=$_SESSION['name'] ?? 'User';

/* =========================
KEYWORD MATCH FUNCTION
========================= */

function intent($msg,$words){

foreach($words as $w){

if(strpos($msg,$w)!==false){

return true;

}

}

return false;

}

/* =========================
GREETING
========================= */

if(intent($message,["hi","hello","hey"])){

echo json_encode([
"reply"=>"Hello ".$user_name." 👋<br>
I can help with:<br>
• Plans<br>
• Bookings<br>
• Events<br>
• Payments<br><br>
Try: plans"
]);

exit();

}

/* =========================
PLANS
========================= */

if(intent($message,["plan","plans","price","pricing","pass"])){

echo json_encode([
"reply"=>"Available Plans:<br><br>

Basic → ₹999<br>
Premium → ₹2499<br>
VIP → ₹4999<br><br>

Type basic, premium or vip for details."
]);

exit();

}

/* =========================
BOOKING GUIDE
========================= */

if(intent($message,["book","booking","how to book"])){

echo json_encode([
"reply"=>"How to book event:<br><br>

1 Go to Events page<br>
2 Click View Details<br>
3 Select tickets<br>
4 Complete payment<br><br>

Need help? Type events"
]);

exit();

}

/* =========================
LOGIN REQUIRED CHECK
========================= */

function loginRequired(){

echo json_encode([
"reply"=>"Please login to access this information."
]);

exit();

}

/* =========================
MY BOOKINGS
========================= */

if(intent($message,["my booking","my bookings"])){

if(!$user_id){

loginRequired();

}

$sql=mysqli_query($conn,
"SELECT COUNT(*) total 
FROM bookings 
WHERE user_id='$user_id'"
);

$data=mysqli_fetch_assoc($sql);

echo json_encode([
"reply"=>"You have ".$data['total']." bookings."
]);

exit();

}

/* =========================
LATEST BOOKING
========================= */

if(intent($message,["latest","last booking"])){

if(!$user_id){

loginRequired();

}

$sql=mysqli_query($conn,
"SELECT events.title,
bookings.booking_code,
bookings.payment_status
FROM bookings
JOIN events ON bookings.event_id=events.id
WHERE bookings.user_id='$user_id'
ORDER BY bookings.id DESC
LIMIT 1"
);

if(mysqli_num_rows($sql)>0){

$row=mysqli_fetch_assoc($sql);

echo json_encode([
"reply"=>"Latest booking:<br><br>

Event: ".$row['title']."<br>
Booking ID: ".$row['booking_code']."<br>
Status: ".$row['payment_status']
]);

}else{

echo json_encode([
"reply"=>"No bookings found."
]);

}

exit();

}

/* =========================
PAYMENT
========================= */

if(intent($message,["payment","paid","transaction"])){

if(!$user_id){

echo json_encode([
"reply"=>"Payment methods:<br>

• UPI<br>
• Debit Card<br>
• Credit Card<br>
• Net Banking<br><br>

Login to see payment status."
]);

exit();

}

$sql=mysqli_query($conn,
"SELECT payment_status
FROM bookings
WHERE user_id='$user_id'
ORDER BY id DESC
LIMIT 1"
);

if(mysqli_num_rows($sql)>0){

$row=mysqli_fetch_assoc($sql);

echo json_encode([
"reply"=>"Your last payment status: ".$row['payment_status']
]);

}else{

echo json_encode([
"reply"=>"No payment history found."
]);

}

exit();

}

/* =========================
EVENTS
========================= */

if(intent($message,["event","events","upcoming"])){

$sql=mysqli_query($conn,
"SELECT title,event_date,price
FROM events
ORDER BY event_date ASC
LIMIT 3"
);

$reply="Upcoming Events:<br><br>";

while($row=mysqli_fetch_assoc($sql)){

$reply.=$row['title'].
" - ₹".$row['price'].
"<br>";

}

echo json_encode([
"reply"=>$reply
]);

exit();

}

/* =========================
TICKETS
========================= */

if(intent($message,["ticket","tickets"])){

if(!$user_id){

loginRequired();

}

$sql=mysqli_query($conn,
"SELECT SUM(tickets) total
FROM bookings
WHERE user_id='$user_id'"
);

$row=mysqli_fetch_assoc($sql);

$total=$row['total'] ?? 0;

echo json_encode([
"reply"=>"Total tickets booked: ".$total
]);

exit();

}

/* =========================
HELP MENU
========================= */

if(intent($message,["help","support"])){

echo json_encode([
"reply"=>"I can help with:<br><br>

plans<br>
my bookings<br>
latest booking<br>
events<br>
payment<br><br>

Example: my bookings"
]);

exit();

}

/* =========================
SMART FALLBACK
========================= */

echo json_encode([
"reply"=>"I didn't understand.<br><br>

Try:<br>
plans<br>
events<br>
my bookings<br>
payment"
]);

?>