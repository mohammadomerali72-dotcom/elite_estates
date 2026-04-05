<?php 
session_start();
include '../includes/db.php'; 

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add_property'])) {
    $u_id = $_SESSION['user_id']; // Current logged in user ID
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = $_POST['property_type'];
    
    // File Handling
    $image_main = time() . "_" . $_FILES['image_main']['name'];
    $image_360 = time() . "_" . $_FILES['image_360']['name'];
    
    move_uploaded_file($_FILES['image_main']['tmp_name'], "../uploads/property_main/" . $image_main);
    move_uploaded_file($_FILES['image_360']['tmp_name'], "../uploads/panoramas/" . $image_360);

    // SQL with user_id
    $sql = "INSERT INTO properties (title, price, location, description, image_main, image_360, property_type, user_id) 
            VALUES ('$title', '$price', '$location', '$description', '$image_main', '$image_360', '$type', '$u_id')";

    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Property Linked to your account!'); window.location='index.php';</script>";
    }
}
?>
<!-- ... (Baqi HTML code wahi rahega jo pehle tha) ... -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property | EliteAdmin</title>
    
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        :root {
            --admin-bg: #050810;
            --card-bg: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
            --accent: #d4af37;
        }

        body { background: var(--admin-bg); color: white; display: flex; min-height: 100vh; margin: 0; }
        
        /* Sidebar */
        .sidebar { width: 280px; background: var(--card-bg); border-right: 1px solid var(--border); padding: 40px 20px; position: fixed; height: 100vh; z-index: 100; }
        .sidebar h2 { color: var(--accent); font-size: 22px; margin-bottom: 50px; text-align: center; letter-spacing: 2px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li a { color: white; text-decoration: none; display: block; padding: 15px 25px; border-radius: 15px; margin-bottom: 10px; transition: 0.3s; font-weight: 500; }
        .sidebar ul li a:hover, .active { background: var(--accent); color: black !important; font-weight: 700; }

        /* Main Wrapper */
        .main-wrapper { flex: 1; margin-left: 280px; padding: 60px; }
        .form-container { background: var(--card-bg); border: 1px solid var(--border); border-radius: 40px; padding: 50px; max-width: 900px; margin: 0 auto; box-shadow: 0 30px 60px rgba(0,0,0,0.5); }
        
        /* Form Styling */
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 10px; color: var(--accent); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        
        input, textarea, select { 
            width: 100%; padding: 16px 20px; background: rgba(255,255,255,0.05); 
            border: 1px solid var(--border); border-radius: 15px; color: white; 
            font-size: 16px; outline: none; transition: 0.3s; 
        }
        
        input:focus, textarea:focus, select:focus { border-color: var(--accent); background: rgba(255,255,255,0.08); }
        option { background: #050810; color: white; }

        .file-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .input-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }

        .btn-submit { 
            background: var(--accent); color: black; padding: 20px 40px; border: none; 
            border-radius: 50px; font-weight: 800; font-size: 16px; cursor: pointer; 
            width: 100%; margin-top: 20px; transition: 0.4s; text-transform: uppercase;
        }
        .btn-submit:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }
        
        ::placeholder { color: rgba(255,255,255,0.3); }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>ELITE<span>ADMIN</span></h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="add_property.php" class="active">Add New Property</a></li>
            <li><a href="../index.php" target="_blank">View Website</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 36px; font-weight: 800; margin: 0;">List New Property</h1>
            <p style="opacity: 0.5;">Showcase your premium real estate in the 3rd dimension.</p>
        </div>

        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Property Title</label>
                    <input type="text" name="title" placeholder="e.g. Modern Azure Skyline Villa" required>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" name="price" placeholder="e.g. $1,250,000" required>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" placeholder="e.g. DHA Phase 8, Karachi" required>
                    </div>
                    <div class="form-group">
                        <label>Listing Type</label>
                        <select name="property_type" required>
                            <option value="Sale">For Sale</option>
                            <option value="Rent">For Rent</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Detailed Description</label>
                    <textarea name="description" rows="5" placeholder="Describe the luxury, amenities, and unique features..."></textarea>
                </div>

                <div class="file-grid">
                    <div class="form-group">
                        <label>Main Card Image</label>
                        <input type="file" name="image_main" accept="image/*" required>
                        <p style="font-size: 11px; opacity: 0.4; margin-top: 5px;">Best size: 800x600px (JPG/PNG)</p>
                    </div>
                    <div class="form-group">
                        <label>360° Virtual Tour Panorama</label>
                        <input type="file" name="image_360" accept="image/*" required>
                        <p style="font-size: 11px; opacity: 0.4; margin-top: 5px;">Requirement: Equirectangular 360 Photo</p>
                    </div>
                </div>

                <button type="submit" name="add_property" class="btn-submit">Publish Property & Activate 3D</button>
            </form>
        </div>
    </div>

</body>
</html>