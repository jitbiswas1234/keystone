<?php

session_start();
include("../config/database.php");

if(!isset($_SESSION['user_id'])){
header("Location:../auth/login.php");
exit();
}

if(!isset($_GET['id'])){
header("Location:../index.php");
exit();
}

$event_id=(int)$_GET['id'];

$stmt=$conn->prepare("SELECT * FROM events WHERE id=?");
$stmt->bind_param("i",$event_id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){
header("Location:../index.php");
exit();
}

$event=$result->fetch_assoc();

$message="";

if(isset($_POST['book'])){

$tickets=(int)$_POST['tickets'];

if($tickets < 1){

$message="Select at least 1 ticket";

}

elseif($tickets > $event['available_seats']){

$message="Only ".$event['available_seats']." seats available";

}

else{

$total=$tickets*$event['price'];

$_SESSION['event_id']=$event_id;
$_SESSION['tickets']=$tickets;
$_SESSION['total']=$total;

header("Location: payment.php");

exit();

}

}

include("../includes/header.php");
include("../includes/navbar.php");

?>

<style>

:root{
--pink:#ff3b7a;
}

body{
background:#f4f7f6;
}

.booking-card{
border:none;
border-radius:20px;
overflow:hidden;
box-shadow:0 15px 30px rgba(0,0,0,0.1);
}

.event-banner{

height:160px;

background:
linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),
url('../assets/images/<?php echo !empty($event['image']) ? htmlspecialchars($event['image']) : 'default.jpg';?>');

background-size:cover;

display:flex;

align-items:center;

padding:20px;

color:white;

}

.input-ticket{

border-radius:10px;

text-align:center;

font-weight:bold;

}

.total-box{

background:#f8f9fa;

border-radius:15px;

padding:20px;

margin-top:20px;

}

.pay-btn{

background:var(--pink);

color:white;

border:none;

padding:15px;

border-radius:12px;

font-weight:bold;

}

.pay-btn:hover{

background:#e0356c;

}

</style>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card booking-card">

<div class="event-banner">

<div>

<h4>

<?php echo htmlspecialchars($event['title']);?>

</h4>

<p>

Available Seats :

<?php echo htmlspecialchars($event['available_seats']);?>

</p>

</div>

</div>

<div class="card-body p-4">

<?php if($message!=""){ ?>

<div class="alert alert-danger">

<?php echo $message;?>

</div>

<?php } ?>

<form method="POST">

<label class="fw-bold">

Tickets

</label>

<input

type="number"

name="tickets"

id="tickets"

class="form-control input-ticket"

value="1"

min="1"

max="<?php echo htmlspecialchars($event['available_seats']);?>"

required

>

<div class="total-box">

<div class="d-flex justify-content-between">

<span>Price</span>

<span>

₹ <span id="price">

<?php echo htmlspecialchars($event['price']);?>

</span>

</span>

</div>

<div class="d-flex justify-content-between mt-2">

<b>Total</b>

<b>

₹ <span id="total">

<?php echo htmlspecialchars($event['price']);?>

</span>

</b>

</div>

</div>

<button

name="book"

class="btn pay-btn w-100 mt-3"

>

Confirm & Pay

</button>

</form>

<div class="text-center mt-3">

<a

href="../user/event_details.php?id=<?php echo $event_id;?>"

class="text-muted"

>

Cancel

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script>

const ticket=document.getElementById("tickets");

const price=parseFloat(

document.getElementById("price").innerText

);

const total=document.getElementById("total");

ticket.addEventListener("input",function(){

let qty=parseInt(this.value);

if(isNaN(qty) || qty<1){

qty=0;

}

total.innerText=(qty*price).toLocaleString('en-IN');

});

</script>

<?php include("../includes/footer.php"); ?>