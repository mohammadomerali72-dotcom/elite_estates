<?php 
// 1. Error Reporting On (Debugging ke liye)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'includes/db.php'; 

// 2. Security Check: Agar user login nahi hai ya role Provider nahi hai
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'provider') {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// 3. Fetch Stats (Only for this seller)
$stats_query = "SELECT COUNT(*) as total FROM properties WHERE user_id = '$u_id'";
$stats_res = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_res);

// 4. Fetch Active Leads (Inquiries for this seller's properties)
$leads_query = "SELECT COUNT(*) as total_leads FROM inquiries 
                JOIN properties ON inquiries.property_id = properties.id 
                WHERE properties.user_id = '$u_id'";
$leads_res = mysqli_query($conn, $leads_query);
$leads_data = mysqli_fetch_assoc($leads_res);

// 5. Fetch Properties for Table
$res = mysqli_query($conn, "SELECT * FROM properties WHERE user_id = '$u_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Control Panel | EliteEstates</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: #050810; color: white; padding: 50px 8%; font-family: 'Plus Jakarta Sans'; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px; }
        
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; margin-bottom: 50px; }
        .stat-box { background: rgba(255,255,255,0.03); padding: 35px; border-radius: 35px; border: 1px solid rgba(255,255,255,0.1); }
        .stat-box h3 { font-size: 40px; color: var(--accent); margin: 0; }
        .stat-box p { opacity: 0.5; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px; }

        .table-container { background: rgba(255,255,255,0.02); border-radius: 40px; border: 1px solid rgba(255,255,255,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 20px; color: var(--accent); font-size: 12px; text-transform: uppercase; border-bottom: 2px solid rgba(255,255,255,0.1); }
        td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }

        .prop-thumb { width: 70px; height: 50px; border-radius: 12px; object-fit: cover; }
        .badge { padding: 6px 15px; border-radius: 50px; font-size: 10px; font-weight: 800; }
        .badge-sale { background: rgba(212, 175, 55, 0.2); color: var(--accent); }
        .badge-rent { background: rgba(52, 152, 219, 0.2); color: #3498db; }

        .btn-action { text-decoration: none; font-size: 12px; font-weight: 800; padding: 10px 18px; border-radius: 12px; transition: 0.3s; }
        .btn-edit { background: rgba(52, 152, 219, 0.1); color: #3498db; margin-right: 10px; }
        .btn-del { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
        
        .btn-add { background: var(--accent); color: black !important; padding: 12px 25px; border-radius: 50px; font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>

<div class="dashboard-header">
    <div>
        <h1 style="font-size: 34px; font-weight: 800;">Seller Dashboard</h1>
        <p style="opacity:0.5;">Active Session: <b><?php echo $_SESSION['user_name']; ?></b></p>
    </div>
    <div style="display:flex; gap:15px;">
        <a href="index.php" style="color:white; text-decoration:none; margin-top:10px;">View Site</a>
        <a href="admin/add_property.php" class="btn-add">+ List Property</a>
    </div>
</div>

<div class="stats-row">
    <div class="stat-box">
        <h3><?php echo $stats['total']; ?></h3>
        <p>Your Listings</p>
    </div>
    <div class="stat-box">
        <h3><?php echo $leads_data['total_leads']; ?></h3>
        <p>New Inquiries</p>
    </div>
    <div class="stat-box">
        <h3>4.2k</h3>
        <p>Profile Views</p>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Preview</th>
                <th>Property Title</th>
                <th>Type</th>
                <th>Price</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($res) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><img src="uploads/property_main/<?php echo $row['image_main']; ?>" class="prop-thumb"></td>
                    <td>
                        <div style="font-weight: 700;"><?php echo $row['title']; ?></div>
                        <div style="font-size: 11px; opacity: 0.5;">📍 <?php echo $row['location']; ?></div>
                    </td>
                    <td>
                        <span class="badge <?php echo ($row['property_type'] == 'Rent') ? 'badge-rent' : 'badge-sale'; ?>">
                            <?php echo $row['property_type']; ?>
                        </span>
                    </td>
                    <td style="color: var(--accent); font-weight: 800;"><?php echo $row['price']; ?></td>
                    <td>
                        <a href="admin/edit_property.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                        <a href="admin/delete.php?id=<?php echo $row['id']; ?>" class="btn-action btn-del" onclick="return confirm('Delete this listing?')">Remove</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding:50px; opacity:0.3;">No properties listed by you yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 50px; text-align: center;">
    <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: 700;">Secure Logout</a>
</div>

</body>
</html>