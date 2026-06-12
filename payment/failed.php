<?php

session_start();

?>

<!DOCTYPE html>

<html>

<head>

<title>Payment Failed</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow text-center p-5">

<h2 class="text-danger">

Payment Failed

</h2>

<p class="text-muted">

Your transaction could not be completed.

</p>

<hr>

<div class="mt-4">

<a href="../user/events.php"

class="btn btn-primary">

Browse Events

</a>

<a href="payment.php"

class="btn btn-warning">

Retry Payment

</a>

</div>

</div>

</div>

</body>

</html>