<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Properties | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 50px; background: rgba(255,255,255,0.05); border-radius: 20px; overflow: hidden; }
        th, td { padding: 20px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        th { background: var(--accent); color: var(--bg); }
        .btn-delete { color: #ff4d4d; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body style="padding: 50px;">
    <h1>Property Management Dashboard</h1>
    <a href="add_property.php" class="btn-admin" style="display:inline-block; margin-top:20px;">+ Add New Property</a>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM properties ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                    <td><img src='../uploads/property_main/{$row['image_main']}' width='80' style='border-radius:10px;'></td>
                    <td>{$row['title']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['location']}</td>
                    <td>
                        <a href='delete.php?id={$row['id']}' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>