<?php 
session_start();
include 'includes/db.php'; 

$error = ""; // Error message handle karne ke liye

// Sirf tab chale jab "Sign In" ka button dabaya jaye
if(isset($_POST['login'])) {
    
    // Form fields ko variables mein lena (yahan 'email' aur 'password' name use kiye hain)
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 

    // Database mein user dhoondna
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Password verify karna
        if(password_verify($password, $user['password'])) {
            // Sessions set karna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['user_role'];

            // Role ke mutabiq redirect karna
            if($user['user_role'] == 'provider') {
                header("Location: provider_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "No user found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In | EliteEstates</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: #050810; display: flex; align-items: center; justify-content: center; height: 100vh; color: white; margin: 0; }
        .auth-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 50px; border-radius: 40px; width: 420px; backdrop-filter: blur(20px); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        h2 { color: var(--accent); margin-bottom: 30px; font-size: 28px; font-weight: 800; }
        input { width: 100%; padding: 15px 20px; background: #e2e8f0; border: none; border-radius: 15px; color: #1a202c; margin-bottom: 20px; outline: none; font-size: 16px; font-weight: 500; }
        .btn-auth { width: 100%; padding: 18px; background: var(--accent); border: none; border-radius: 50px; font-weight: 800; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-auth:hover { transform: scale(1.02); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); }
        .error-msg { background: rgba(255, 77, 77, 0.1); color: #ff4d4d; padding: 10px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Welcome Back</h2>
    
    <!-- Error dikhane ke liye -->
    <?php if($error != ""): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email Address" required>
        <!-- 'name' attribute must match PHP $_POST key -->
        <input type="password" name="password" placeholder="Password" required>
        
        <button type="submit" name="login" class="btn-auth">Sign In</button>
        
        <p style="text-align:center; margin-top:25px; font-size:14px; opacity:0.6;">
            New here? <a href="signup.php" style="color:var(--accent); text-decoration:none; font-weight:700;">Create Account</a>
        </p>
    </form>
</div>

</body>
</html>