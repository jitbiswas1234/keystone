<?php

include("../config/database.php");

$id=$_GET['id'];

$sql="SELECT * FROM bookings WHERE id='$id'";

$result=mysqli_query($conn,$sql);

$booking=mysqli_fetch_assoc($result);

$event_id=$booking['event_id'];

$tickets=$booking['tickets'];

mysqli_query($conn,

"UPDATE bookings 
SET payment_status='Cancelled'
WHERE id='$id'");

mysqli_query($conn,

"UPDATE events 
SET available_seats=available_seats+$tickets
WHERE id='$event_id'");

header("Location:manage_bookings.php");

?>