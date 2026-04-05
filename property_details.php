<?php 
include 'includes/db.php'; 

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM properties WHERE id = '$id'");
    $property = mysqli_fetch_assoc($res);
    if(!$property) { die("Property not found!"); }
} else {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $property['title']; ?> | 360 Virtual Tour</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- Pannellum (360 Viewer) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

    <style>
        #panorama { width: 100%; height: 70vh; background: #000; border-bottom: 2px solid var(--accent); }
        .details-wrapper { padding: 60px 8%; background: var(--bg); position: relative; z-index: 10; }
        .info-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 50px; }
        .contact-form { background: rgba(255,255,255,0.02); border: 1px solid var(--border); padding: 40px; border-radius: 30px; }
        .back-link { position: absolute; top: 30px; left: 30px; z-index: 100; color: white; text-decoration: none; background: rgba(0,0,0,0.5); padding: 10px 20px; border-radius: 50px; font-weight: 600; }
    </style>
</head>
<body>

<a href="index.php" class="back-link">← Back to Listings</a>

<!-- 360 Viewer Section -->
<div id="panorama"></div>

<div class="details-wrapper">
    <div class="info-grid">
        <!-- Details Column -->
        <div>
            <span style="color: var(--accent); font-weight: 600; letter-spacing: 2px;">LUXURY PROPERTY</span>
            <h1 style="font-size: 50px; font-weight: 800; margin: 10px 0;"><?php echo $property['title']; ?></h1>
            <p style="font-size: 20px; opacity: 0.7;">📍 <?php echo $property['location']; ?></p>
            <h2 style="color: var(--accent); font-size: 35px; margin-top: 20px;"><?php echo $property['price']; ?></h2>
            
            <hr style="margin: 40px 0; opacity: 0.1;">
            
            <h3>Description</h3>
            <p style="color: #888; margin-top: 15px; line-height: 1.8; font-size: 17px;"><?php echo $property['description']; ?></p>
        </div>

        <!-- Inquiry Form Column -->
        <div class="contact-form">
            <h3 style="margin-bottom: 25px;">Interested? Contact Agent</h3>
            <form action="send_inquiry.php" method="POST">
                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                <input type="text" name="name" placeholder="Full Name" required style="width:100%; padding:15px; margin-bottom:15px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:white; border-radius:12px;">
                <input type="email" name="email" placeholder="Email Address" required style="width:100%; padding:15px; margin-bottom:15px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:white; border-radius:12px;">
                <textarea name="message" placeholder="Message" rows="4" style="width:100%; padding:15px; margin-bottom:15px; background:rgba(255,255,255,0.05); border:1px solid var(--border); color:white; border-radius:12px;"></textarea>
                <button type="submit" class="btn-admin" style="width:100%; border:none; cursor:pointer; padding:18px;">Send Inquiry</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Pannellum
    pannellum.viewer('panorama', {
        "type": "equirectangular",
        "panorama": "uploads/panoramas/<?php echo $property['image_360']; ?>",
        "autoLoad": true,
        "autoRotate": -2
    });
</script>

</body>
</html>