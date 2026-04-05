<?php 
include '../includes/db.php'; 

// 1. URL se ID lena aur purana data fetch karna
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM properties WHERE id = '$id'");
    $data = mysqli_fetch_assoc($res);
}

// 2. Update Logic
if(isset($_POST['update_property'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = $_POST['property_type'];

    // Update Query
    $sql = "UPDATE properties SET title='$title', price='$price', location='$location', description='$description', property_type='$type' WHERE id='$id'";

    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Details Updated!'); window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Property | EliteAdmin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: #050810; color: white; padding: 60px; font-family: 'Plus Jakarta Sans'; }
        .edit-container { max-width: 800px; margin: 0 auto; background: rgba(255,255,255,0.03); padding: 40px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 10px; color: var(--accent); font-weight: 600; }
        input, textarea, select { width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; outline: none; }
        .btn-update { background: var(--accent); color: black; padding: 15px 40px; border: none; border-radius: 50px; font-weight: 800; cursor: pointer; width: 100%; margin-top: 20px; }
    </style>
</head>
<body>

<div class="edit-container">
    <h1>Edit Property Details</h1>
    <p style="opacity: 0.5; margin-bottom: 30px;">ID: #<?php echo $data['id']; ?></p>

    <form method="POST">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $data['title']; ?>" required>
        </div>

        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Price</label>
                <input type="text" name="price" value="<?php echo $data['price']; ?>" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Property Type</label>
                <select name="property_type">
                    <option value="Sale" <?php if($data['property_type'] == 'Sale') echo 'selected'; ?>>For Sale</option>
                    <option value="Rent" <?php if($data['property_type'] == 'Rent') echo 'selected'; ?>>For Rent</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Location</label>
            <input type="text" name="location" value="<?php echo $data['location']; ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5"><?php echo $data['description']; ?></textarea>
        </div>

        <button type="submit" name="update_property" class="btn-update">Save Changes</button>
        <a href="index.php" style="display:block; text-align:center; margin-top:20px; color:white; opacity:0.5; text-decoration:none;">Cancel</a>
    </form>
</div>

</body>
</html>