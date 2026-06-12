<?php

$message=strtolower($_POST['message'] ?? '');

$reply="Sorry I didn't understand.";

// AI responses

if(str_contains($message,"hello") ||
str_contains($message,"hi")){

$reply="Hello 👋 Need help with booking?";

}

elseif(str_contains($message,"booking")){

$reply="You can book events from Events page.";

}

elseif(str_contains($message,"payment")){

$reply="We support Razorpay (UPI/Card/NetBanking).";

}

elseif(str_contains($message,"plan")){

$reply="We have Basic, Premium and VIP plans.";

}

elseif(str_contains($message,"invoice")){

$reply="You can download invoice from My Bookings.";

}

elseif(str_contains($message,"refund")){

$reply="Refund depends on event cancellation policy.";

}

echo json_encode([

"reply"=>$reply

]);