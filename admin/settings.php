<?php

session_start();

include("../config/database.php");

if(!isset($_SESSION['admin_id'])){

header("Location: ../auth/login.php");

exit();

}

$admin_id=$_SESSION['admin_id'];

$success="";
$error="";

/* GET ADMIN */

$stmt=$conn->prepare(

"SELECT * FROM admins WHERE id=?"

);

$stmt->bind_param("i",$admin_id);

$stmt->execute();

$admin=$stmt->get_result()->fetch_assoc();


/* UPDATE USERNAME */

if(isset($_POST['update_profile'])){

$username=trim($_POST['username']);

$stmt=$conn->prepare(

"UPDATE admins SET username=? WHERE id=?"

);

$stmt->bind_param("si",$username,$admin_id);

if($stmt->execute()){

$success="Username updated";

}else{

$error="Update failed";

}

}


/* CHANGE PASSWORD */

if(isset($_POST['change_password'])){

$current=$_POST['current'];

$new=$_POST['new'];

if($current!=$admin['password']){

$error="Current password wrong";

}else{

$stmt=$conn->prepare(

"UPDATE admins SET password=? WHERE id=?"

);

$stmt->bind_param("si",$new,$admin_id);

$stmt->execute();

$success="Password updated";

}

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Admin Settings</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h3>Admin Settings</h3>

<?php if($success){ ?>

<div class="alert alert-success">

<?php echo $success; ?>

</div>

<?php } ?>

<?php if($error){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>


<div class="card p-4 mb-4">

<h5>Profile Info</h5>

<form method="POST">

<label>Username</label>

<input type="text"

name="username"

class="form-control mb-3"

value="<?php echo $admin['username'];?>"

required>

<button name="update_profile"

class="btn btn-primary">

Update

</button>

</form>

</div>


<div class="card p-4">

<h5>Change Password</h5>

<form method="POST">

<input type="password"

name="current"

placeholder="Current Password"

class="form-control mb-2"

required>

<input type="password"

name="new"

placeholder="New Password"

class="form-control mb-3"

required>

<button name="change_password"

class="btn btn-danger">

Change Password

</button>

</form>

</div>


<a href="dashboard.php"

class="btn btn-secondary mt-3">

Back to Dashboard

</a>

</div>

</body>

</html>