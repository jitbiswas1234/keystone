<?php
date_default_timezone_set('Asia/Kolkata');
include("../config/database.php");

if(!isset($_GET['token'])){
    die("Invalid request");
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
$user = mysqli_fetch_assoc($sql);

if(!$user){
    die("Invalid token");
}

if(strtotime($user['token_expire']) < time()){
    die("Token expired");
}

$msg = "";
$success = false;

if(isset($_POST['reset'])){
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "UPDATE users SET 
        password='$password',
        reset_token=NULL,
        token_expire=NULL
        WHERE reset_token='$token'"
    );

    $msg = "Password updated successfully! Redirecting to login...";
    $success = true;
    
    // PHP Redirect after 3 seconds
    header("refresh:3;url=login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password | Event System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-primary { background: #ff3b7a; border: none; padding: 10px; font-weight: 600; }
        .btn-primary:hover { background: #e0326b; }
        .form-control:focus { border-color: #ff3b7a; box-shadow: 0 0 0 0.25 source rgba(255, 59, 122, 0.25); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="mb-4 text-center">Set New Password</h3>
                
                <?php if($msg != ""){ ?>
                    <div class="alert alert-success border-0 text-center">
                        <?php echo $msg; ?>
                    </div>
                <?php } ?>

                <?php if(!$success){ ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="••••••••" 
                                   required 
                                   minlength="6">
                        </div>
                        <button name="reset" class="btn btn-primary w-100">
                            Update Password
                        </button>
                    </form>
                <?php } else { ?>
                    <div class="text-center mt-3">
                        <div class="spinner-border text-pink" role="status" style="color: #ff3b7a;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2"><a href="login.php" class="text-decoration-none">Click here if not redirected</a></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>