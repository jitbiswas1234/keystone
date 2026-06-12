<?php

include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

if(!isset($_SESSION['admin_id']))
{
header("Location:../auth/login.php");
exit();
}

$message="";

if(isset($_POST['create']))
{

$title=$_POST['title'];
$description=$_POST['description'];
$date=$_POST['date'];
$time=$_POST['time'];
$location=$_POST['location'];
$category=$_POST['category'];
$price=$_POST['price'];
$seats=$_POST['seats'];

/* Image upload */

$image="";

if(!empty($_FILES['image']['name']))
{

$image=time()."_".$_FILES['image']['name'];

move_uploaded_file(

$_FILES['image']['tmp_name'],

"../assets/images/".$image

);

}

$sql="INSERT INTO events
(title,description,event_date,event_time,location,category,price,total_seats,available_seats,image)

VALUES
('$title','$description','$date','$time','$location','$category','$price','$seats','$seats','$image')";

mysqli_query($conn,$sql);

$message="Event Created Successfully";

}

?>

<div class="container mt-5">

<h2>Create Event</h2>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<input type="text"
name="title"
class="form-control mb-3"
placeholder="Event Title"
required>

<textarea name="description"
class="form-control mb-3"
placeholder="Description"
required></textarea>

<input type="date"
name="date"
class="form-control mb-3"
required>

<input type="time"
name="time"
class="form-control mb-3"
required>

<input type="text"
name="location"
class="form-control mb-3"
placeholder="Location"
required>

<label>Category</label>

<select name="category"
class="form-control mb-3"
required>

<option value="">Select Category</option>

<option value="Tech">Tech</option>

<option value="Music">Music</option>

<option value="Workshop">Workshop</option>

<option value="Sports">Sports</option>

<option value="Seminar">Seminar</option>

</select>

<input type="number"
name="price"
class="form-control mb-3"
placeholder="Price"
required>

<input type="number"
name="seats"
class="form-control mb-3"
placeholder="Total Seats"
required>

<label>Event Image</label>

<input type="file"
name="image"
class="form-control mb-3">

<button name="create"
class="btn btn-success">

Create Event

</button>

</form>

</div>

<?php include("../includes/footer.php"); ?>