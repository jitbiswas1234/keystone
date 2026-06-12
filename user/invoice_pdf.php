<?php
ob_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

include("../config/database.php");
require('../lib/fpdf186/fpdf.php');

if(!isset($_GET['id'])){
    die("Invalid request.");
}

$id = (int)$_GET['id'];

$sql = "SELECT b.*, e.title, e.location, e.event_date 
        FROM bookings b 
        JOIN events e ON b.event_id = e.id 
        WHERE b.id = $id";

$result = mysqli_query($conn,$sql);

if(!$result){
    die(mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Booking not found.");
}

/* Safe values */
$payment_method = ucfirst($data['payment_method'] ?? 'Razorpay');
$booking_code   = $data['booking_code'] ?? 'NA';
$title          = $data['title'] ?? 'Event';
$location       = $data['location'] ?? 'Venue not specified';
$event_date     = isset($data['event_date']) ? date('D, d M Y',strtotime($data['event_date'])) : 'NA';
$tickets        = $data['tickets'] ?? 1;
$total_price    = $data['total_price'] ?? 0;

/* QR Code */
$booking_info = "Booking ID : ".$booking_code;

$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data="
           . urlencode($booking_info);

/* PDF */
$pdf = new FPDF('P','mm','A4');

$pdf->AddPage();

$pdf->SetMargins(20,20,20);

/* Header */
$pdf->SetFillColor(33,37,41);

$pdf->Rect(0,0,210,35,'F');

$pdf->SetY(12);

$pdf->SetFont('Arial','B',18);

$pdf->SetTextColor(255,255,255);

$pdf->Cell(0,5,'CHEFSHARE EVENTS',0,1,'L');

$pdf->SetFont('Arial','',9);

$pdf->SetTextColor(200,200,200);

$pdf->Cell(0,10,'Official Booking Confirmation & Receipt',0,0,'L');

/* QR */
$pdf->Image($qr_url,155,10,35,35,'png');

/* Booking section */

$pdf->Ln(35);

$pdf->SetFont('Arial','B',14);

$pdf->SetTextColor(44,62,80);

$pdf->Cell(0,15,'INVOICE : #'.$booking_code,0,1);

$pdf->SetDrawColor(230,230,230);

$pdf->Line(20,$pdf->GetY(),190,$pdf->GetY());

$pdf->Ln(8);

/* Customer + Event */

$pdf->SetFont('Arial','B',9);

$pdf->SetTextColor(120,120,120);

$pdf->Cell(85,5,'CUSTOMER DETAILS',0,0);

$pdf->Cell(85,5,'EVENT DETAILS',0,1);

$pdf->SetFont('Arial','',10);

$pdf->SetTextColor(50,50,50);

$pdf->Cell(85,6,'User ID : #'.$data['user_id'],0,0);

$pdf->SetFont('Arial','B',10);

$pdf->Cell(85,6,$title,0,1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(85,6,'Payment : '.$payment_method,0,0);

$pdf->Cell(85,6,$event_date,0,1);

$pdf->Cell(85,6,'',0,0);

$pdf->SetTextColor(100,100,100);

$pdf->Cell(85,6,$location,0,1);

$pdf->Ln(15);

/* Table */

$pdf->SetFillColor(245,245,245);

$pdf->SetFont('Arial','B',10);

$pdf->SetTextColor(44,62,80);

$pdf->Cell(100,12,'DESCRIPTION',0,0,'L',true);

$pdf->Cell(30,12,'QTY',0,0,'C',true);

$pdf->Cell(40,12,'TOTAL',0,1,'R',true);

$pdf->SetFont('Arial','',11);

$pdf->Cell(100,20,'Ticket Access - '.$title,'B',0,'L');

$pdf->Cell(30,20,$tickets,'B',0,'C');

$pdf->SetFont('Arial','B',11);

$pdf->Cell(40,20,'Rs '.number_format($total_price,2),'B',1,'R');

/* Total */

$pdf->Ln(5);

$pdf->SetFillColor(255,215,0);

$pdf->SetX(120);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(30,12,'AMOUNT PAID',0,0,'L');

$pdf->Cell(40,12,'Rs '.number_format($total_price,2),0,1,'R');

/* Footer */

$pdf->SetY(-40);

$pdf->SetFont('Arial','B',9);

$pdf->SetTextColor(44,62,80);

$pdf->Cell(0,5,'ENTRY INSTRUCTIONS',0,1,'C');

$pdf->SetFont('Arial','',8);

$pdf->SetTextColor(120,120,120);

$pdf->MultiCell(
0,
4,
"Please carry this ticket on your phone or printed copy.\nQR code will be scanned at entry.\nNo refund within 24 hours of event.",
0,
'C'
);

/* Output */

ob_clean();

$pdf->Output(
'I',
'Invoice_'.$booking_code.'.pdf'
);

exit;
?>