<?php
session_start();
include '../includes/db.php';

if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = $_POST['pass'];

    $res = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user'");
    $row = mysqli_fetch_assoc($res);

    if($row && password_verify($pass, $row['password'])) {
        $_SESSION['admin_user'] = $user;
        header("Location: index.php");
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login | EliteEstates</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; height:100vh; background:#050810;">
    <form method="POST" style="background:rgba(255,255,255,0.03); padding:40px; border-radius:30px; border:1px solid rgba(255,255,255,0.1); width:400px;">
        <h2 style="color:var(--accent); text-align:center; margin-bottom:30px;">EliteAdmin Login</h2>
        <?php if(isset($error)) echo "<p style='color:red; font-size:12px;'>$error</p>"; ?>
        <input type="text" name="user" placeholder="Username" required style="width:100%; padding:15px; margin-bottom:15px; background:transparent; border:1px solid rgba(255,255,255,0.1); color:white; border-radius:10px;">
        <input type="password" name="pass" placeholder="Password" required style="width:100%; padding:15px; margin-bottom:20px; background:transparent; border:1px solid rgba(255,255,255,0.1); color:white; border-radius:10px;">
        <button type="submit" name="login" class="btn-admin" style="width:100%; border:none; cursor:pointer;">Login to Dashboard</button>
    </form>
</body>
</html>