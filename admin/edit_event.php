<?php

include("../includes/header.php");
include("../config/database.php");
include("../includes/navbar.php");

if(!isset($_SESSION['admin_id']))
{
header("Location:../auth/login.php");
exit();
}

if(!isset($_GET['id']))
{
header("Location:manage_events.php");
exit();
}

$id=$_GET['id'];

$sql="SELECT * FROM events WHERE id='$id'";

$result=mysqli_query($conn,$sql);

$event=mysqli_fetch_assoc($result);

$message="";

if(isset($_POST['update']))
{

$title=$_POST['title'];
$description=$_POST['description'];
$date=$_POST['date'];
$time=$_POST['time'];
$location=$_POST['location'];
$price=$_POST['price'];
$seats=$_POST['seats'];

$image=$event['image'];

if(!empty($_FILES['image']['name']))
{

$image=time()."_".$_FILES['image']['name'];

$tmp=$_FILES['image']['tmp_name'];

move_uploaded_file($tmp,
"../assets/images/".$image);

}

$update="UPDATE events SET

title='$title',
description='$description',
event_date='$date',
event_time='$time',
location='$location',
price='$price',
total_seats='$seats',
available_seats='$seats',
image='$image'

WHERE id='$id'";

mysqli_query($conn,$update);

$message="Event Updated Successfully";

}

?>

<style>
    .page-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title i {
        color: #6366f1;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #64748b;
    }

    .breadcrumb-nav a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-nav a:hover {
        text-decoration: underline;
    }

    .breadcrumb-nav i {
        font-size: 10px;
        color: #94a3b8;
    }

    /* Alert Styles */
    .alert-custom {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        font-size: 14px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1));
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #059669;
    }

    .alert-success i {
        font-size: 20px;
        color: #10b981;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .form-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 24px 32px;
        color: white;
    }

    .form-card-header h2 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-body {
        padding: 32px;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    /* Labels */
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-label i {
        margin-right: 6px;
        color: #6366f1;
        width: 16px;
    }

    /* Input Styles */
    .form-control-custom {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        font-family: inherit;
        background: #fafafa;
        color: #1e293b;
        transition: all 0.3s ease;
    }

    .form-control-custom:hover {
        border-color: #d1d5db;
        background: #f5f5f5;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #6366f1;
        background: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-control-custom::placeholder {
        color: #9ca3af;
    }

    textarea.form-control-custom {
        min-height: 140px;
        resize: vertical;
        line-height: 1.6;
    }

    /* Input with Icon */
    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .input-icon-wrapper .form-control-custom {
        padding-left: 48px;
    }

    .input-icon-wrapper:focus-within i {
        color: #6366f1;
    }

    /* Image Section */
    .image-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #f3f4f6;
    }

    .image-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .image-section-title i {
        color: #6366f1;
    }

    .current-image-card {
        display: flex;
        gap: 24px;
        padding: 24px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        border: 2px solid #e5e7eb;
    }

    .current-image-preview {
        flex-shrink: 0;
    }

    .current-image-preview img {
        width: 180px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .current-image-details {
        flex: 1;
    }

    .current-image-details h4 {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .current-image-details .filename {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 16px;
        word-break: break-all;
    }

    .file-upload-box {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-box:hover {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.02);
    }

    .file-upload-box input[type="file"] {
        display: none;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .file-upload-label i {
        font-size: 24px;
        color: #6366f1;
    }

    .file-upload-label span {
        font-size: 14px;
        color: #64748b;
    }

    .file-upload-label .browse {
        color: #6366f1;
        font-weight: 600;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #f3f4f6;
    }

    .btn-custom {
        padding: 14px 28px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
        text-decoration: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: white;
        color: #64748b;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary-custom:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-wrapper {
            padding: 20px 0;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: span 1;
        }

        .form-card-body {
            padding: 20px;
        }

        .current-image-card {
            flex-direction: column;
            text-align: center;
        }

        .current-image-preview img {
            width: 100%;
            max-width: 250px;
            height: auto;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-wrapper">
    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-edit"></i>
                Edit Event
            </h1>
            <nav class="breadcrumb-nav">
                <a href="dashboard.php">Dashboard</a>
                <i class="fas fa-chevron-right"></i>
                <a href="manage_events.php">Events</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Event</span>
            </nav>
        </div>

        <!-- Alert Message -->
        <?php if($message!=""){ ?>
        <div class="alert-custom alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $message; ?>
        </div>
        <?php } ?>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-card-header">
                <h2>
                    <i class="fas fa-calendar-edit"></i>
                    Event Details
                </h2>
            </div>

            <div class="form-card-body">
                <form method="POST" enctype="multipart/form-data">

                    <div class="form-grid">

                        <!-- Event Title -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-heading"></i>
                                Event Title <span class="required">*</span>
                            </label>
                            <input type="text"
                                name="title"
                                value="<?php echo $event['title']; ?>"
                                class="form-control-custom"
                                placeholder="Enter event title"
                                required>
                        </div>

                        <!-- Description -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Description <span class="required">*</span>
                            </label>
                            <textarea name="description"
                                class="form-control-custom"
                                placeholder="Enter event description"
                                required><?php echo $event['description']; ?></textarea>
                        </div>

                        <!-- Date -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i>
                                Date <span class="required">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date"
                                    name="date"
                                    value="<?php echo $event['event_date']; ?>"
                                    class="form-control-custom"
                                    required>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>
                                Time <span class="required">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-clock"></i>
                                <input type="time"
                                    name="time"
                                    value="<?php echo $event['event_time']; ?>"
                                    class="form-control-custom"
                                    required>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group full-width">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Location <span class="required">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text"
                                    name="location"
                                    value="<?php echo $event['location']; ?>"
                                    class="form-control-custom"
                                    placeholder="Enter event location"
                                    required>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-rupee-sign"></i>
                                Price (₹) <span class="required">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-rupee-sign"></i>
                                <input type="number"
                                    name="price"
                                    value="<?php echo $event['price']; ?>"
                                    class="form-control-custom"
                                    placeholder="0.00"
                                    required>
                            </div>
                        </div>

                        <!-- Total Seats -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-users"></i>
                                Total Seats <span class="required">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-chair"></i>
                                <input type="number"
                                    name="seats"
                                    value="<?php echo $event['total_seats']; ?>"
                                    class="form-control-custom"
                                    placeholder="Enter total seats"
                                    required>
                            </div>
                        </div>

                    </div>

                    <!-- Image Section -->
                    <div class="image-section">
                        <h3 class="image-section-title">
                            <i class="fas fa-image"></i>
                            Event Image
                        </h3>

                        <div class="current-image-card">
                            <div class="current-image-preview">
                                <img src="../assets/images/<?php echo $event['image']; ?>" 
                                    alt="Current Event Image">
                            </div>
                            <div class="current-image-details">
                                <h4>Current Image</h4>
                                <p class="filename"><?php echo $event['image']; ?></p>

                                <div class="file-upload-box">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Drop a new image here or <span class="browse">browse</span></span>
                                        <input type="file" name="image" accept="image/*">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" name="update" class="btn-custom btn-primary-custom">
                            <i class="fas fa-save"></i>
                            Update Event
                        </button>

                        <a href="manage_events.php" class="btn-custom btn-secondary-custom">
                            <i class="fas fa-arrow-left"></i>
                            Back to Events
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    // Show filename when file is selected
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            const label = this.closest('.file-upload-box').querySelector('span');
            label.innerHTML = '<strong>' + fileName + '</strong> selected';
        }
    });

    // Drag and drop functionality
    const uploadBox = document.querySelector('.file-upload-box');

    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = '#6366f1';
        uploadBox.style.background = 'rgba(99, 102, 241, 0.05)';
    });

    uploadBox.addEventListener('dragleave', () => {
        uploadBox.style.borderColor = '#d1d5db';
        uploadBox.style.background = 'white';
    });

    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = '#d1d5db';
        uploadBox.style.background = 'white';
        
        const files = e.dataTransfer.files;
        if (files.length) {
            document.querySelector('input[type="file"]').files = files;
            const label = uploadBox.querySelector('span');
            label.innerHTML = '<strong>' + files[0].name + '</strong> selected';
        }
    });
</script>

<?php include("../includes/footer.php"); ?>