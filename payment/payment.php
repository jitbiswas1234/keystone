<?php

session_start();

include("../config/database.php");

/* LOGIN CHECK FIRST */

if (!isset($_SESSION['user_id'])) {

header("Location: ../auth/login.php");

exit();

}

/* PLAN CHECK */

if (isset($_GET['plan'])) {

$type = "plan";

$plan = $_GET['plan'];

$total_amount = $_GET['price'] ?? 0;

$_SESSION['total']=$total_amount;

$title=ucfirst($plan)." Pass";

$tickets=1;

}

/* EVENT CHECK */

elseif(isset($_SESSION['event_id'])){

$type="event";

$event_id=$_SESSION['event_id'];

$tickets=$_SESSION['tickets'] ?? 1;

$total_amount=$_SESSION['total'] ?? 0;

$stmt=$conn->prepare(

"SELECT title FROM events WHERE id=?"

);

$stmt->bind_param("i",$event_id);

$stmt->execute();

$title=$stmt->get_result()->fetch_assoc()['title'] 
?? "Unknown Event";

}

else{

header("Location: ../index.php");

exit();

}

$razorpay_key="rzp_test_STui2tERIeLURC";

?>

<?php include("../includes/header.php"); ?>

<?php include("../includes/navbar.php"); ?>


<style>

:root{

--pk:#ff3b7a;

--dk:#080712;

}

.pay-card{

border-radius:20px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,0.1);

border:none;

}

.pay-header{

background:var(--dk);

color:#fff;

padding:30px;

text-align:center;

}

.row-item{

display:flex;

justify-content:space-between;

padding:12px 0;

border-bottom:1px solid #f0f0f0;

color:#555;

}

.btn-pay{

background:var(--pk);

color:#fff;

border:none;

padding:16px;

border-radius:12px;

font-weight:700;

width:100%;

transition:.3s;

}

.btn-pay:hover{

opacity:.9;

transform:translateY(-2px);

}

</style>


<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-5 col-md-8">

<div class="card pay-card">

<div class="pay-header">

<h4>Review & Pay</h4>

<p class="small opacity-75">

Secure Razorpay Checkout

</p>

</div>

<div class="card-body p-4">

<?php 

$details=[

'Type'=>ucfirst($type),

'Item'=>$title,

'Quantity'=>$tickets,

'Total'=>"<b>₹".number_format($total_amount)."</b>"

];

foreach($details as $label=>$val){

?>

<div class="row-item">

<span><?= $label ?></span>

<span><?= $val ?></span>

</div>

<?php } ?>

<button 

id="rzp-button"

class="btn-pay mt-4"

>

Pay ₹<?= number_format($total_amount) ?>

</button>

</div>

</div>

</div>

</div>

</div>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

const options={

key:"<?= $razorpay_key ?>",

amount:"<?= $total_amount*100 ?>",

currency:"INR",

name:"KeyStone Events",

description:"Payment for <?= $title ?>",

handler:function(res){

window.location.href=

`verify_payment.php?payment_id=${res.razorpay_payment_id}&plan=<?= $plan ?? '' ?>&type=<?= $type ?>`;

},

theme:{color:"#ff3b7a"}

};

const rzp=new Razorpay(options);

document.getElementById(

'rzp-button'

).onclick=(e)=>{

rzp.open();

e.preventDefault();

}

</script>


<?php include("../includes/footer.php"); ?>