<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include("../config/database.php");

$message = "";

if (isset($_POST['send'])) {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(32));
    $expire = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiry

    $stmt = $conn->prepare("UPDATE users SET reset_token=?, token_expire=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expire, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $link = "http://localhost/project2/auth/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";

        // HTML Email Body
        $message_body = "
        <html>
        <head>
            <style>
                .wrapper { background-color: #f4f6fb; padding: 40px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                .header { background-color: #ff3b7a; padding: 30px; text-align: center; color: white; }
                .content { padding: 40px; text-align: center; line-height: 1.6; color: #333; }
                .button { display: inline-block; padding: 14px 30px; background-color: #ff3b7a; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 25px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #888; background: #fafafa; }
            </style>
        </head>
        <body>
            <div class='wrapper'>
                <div class='container'>
                    <div class='header'>
                        <h2 style='margin:0;'>Event Management System</h2>
                    </div>
                    <div class='content'>
                        <h3 style='margin-top:0;'>Password Reset</h3>
                        <p>We received a request to reset your password. No changes have been made to your account yet.</p>
                        <a href='$link' class='button'>Reset Password</a>
                        <p style='margin-top:30px; font-size: 13px; color: #666;'>
                            This link will expire in 1 hour.<br>
                            If you did not request this, please ignore this email.
                        </p>
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " Event Management System. All rights reserved.
                    </div>
                </div>
            </div>
        </body>
        </html>";

        // Headers for HTML Content
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@eventsystem.com" . "\r\n";

        if (mail($email, $subject, $message_body, $headers)) {
            $message = "Reset link sent. Check Mailpit.";
        } else {
            $message = "Mail sending failed";
        }
    } else {
        $message = "Email not found";
    }
}
?>

<!DOCTYPE html>

<html>

<head>

<title>Forgot Password</title>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#f4f6fb;

}

.card{

border-radius:15px;

box-shadow:0 10px 25px rgba(0,0,0,0.1);

}

.btn-primary{

background:#ff3b7a;

border:none;

}

.btn-primary:hover{

background:#e0326b;

}

</style>

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card p-4">

<h3 class="mb-3 text-center">Forgot Password</h3>

<?php if($message!=""){ ?>

<div class="alert alert-info">

<?php echo htmlspecialchars($message); ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Email Address</label>

<input type="email"

name="email"

class="form-control"

placeholder="Enter your email"

required>

</div>

<button name="send"

class="btn btn-primary w-100">

Send Reset Link

</button>

</form>

<div class="text-center mt-3">

<a href="login.php">

Back to Login

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>