<?php 
session_start();
include '../includes/db.php'; 

// Handle New Listing & Notification Logic
if(isset($_POST['add_property'])) {
    // ... (Property add logic here) ...
    
    // Send Notification to all seekers
    $notif_msg = "New Property Added: " . $_POST['title'];
    mysqli_query($conn, "INSERT INTO notifications (user_id, msg) SELECT id, '$notif_msg' FROM users WHERE user_role = 'seeker'");
}

$res = mysqli_query($conn, "SELECT * FROM properties ORDER BY id DESC");
$stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM properties"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | EliteEstates</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { display: flex; background: #050810; }
        .sidebar { width: 280px; padding: 40px; border-right: 1px solid rgba(255,255,255,0.1); height: 100vh; position: fixed; }
        .main { margin-left: 280px; padding: 60px; width: 100%; color: white; }
        .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); }
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.02); border-radius: 20px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .action-btns { display: flex; gap: 10px; }
        .btn-call { background: #2ecc71; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="color:var(--accent)">ELITE ADMIN</h2><br><br>
    <a href="index.php" style="color:var(--accent); text-decoration:none; font-weight:800;">Dashboard</a><br><br>
    <a href="add_property.php" style="color:white; text-decoration:none;">Add Property</a><br><br>
    <a href="../index.php" style="color:white; text-decoration:none;">View Site</a><br><br>
    <a href="../logout.php" style="color:#ff4d4d; text-decoration:none;">Logout</a>
</div>

<div class="main">
    <h1>Portal Overview</h1><br>
    <div class="stat-grid">
        <div class="stat-card"><h3><?php echo $stats['total']; ?></h3><p>Active Properties</p></div>
        <div class="stat-card"><h3>8</h3><p>New Messages</p></div>
        <div class="stat-card"><h3>Live</h3><p>Video Support</p></div>
    </div>

    <h3>Inventory Management</h3><br>
    <table>
        <tr><th>Title</th><th>Status</th><th>Price</th><th>Contact Buyer</th></tr>
        <?php while($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?php echo $row['title']; ?></td>
            <td><span style="color:var(--accent)"><?php echo $row['property_type']; ?></span></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <div class="action-btns">
                    <a href="#" onclick="alert('Opening Chat...')" style="color:#3498db; text-decoration:none;">Message</a>
                    <a href="../index.php" class="btn-call">Video Call</a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>