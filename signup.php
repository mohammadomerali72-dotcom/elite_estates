<?php 
include 'includes/db.php'; 
if(isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    mysqli_query($conn, "INSERT INTO users (full_name, email, password, user_role) VALUES ('$name', '$email', '$pass', '$role')");
    header("Location: login.php");
}
?>
<!-- HTML Form with same styling as before -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join EliteEstates | Create Account</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { background: #050810; display: flex; align-items: center; justify-content: center; min-height: 100vh; color: white; }
        .auth-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 50px; border-radius: 40px; width: 450px; backdrop-filter: blur(20px); }
        .auth-card h2 { color: var(--accent); margin-bottom: 10px; font-size: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-size: 13px; opacity: 0.7; }
        input, select { width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; color: white; outline: none; }
        .role-selector { display: flex; gap: 10px; margin-top: 10px; }
        .role-option { flex: 1; text-align: center; padding: 15px; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; transition: 0.3s; font-size: 13px; }
        input[type="radio"] { display: none; }
        input[type="radio"]:checked + .role-label { background: var(--accent); color: black; font-weight: 700; border-color: var(--accent); }
        .role-label { display: block; width: 100%; height: 100%; border-radius: 12px; padding: 10px; border: 1px solid rgba(255,255,255,0.1); }
        .btn-auth { width: 100%; padding: 18px; background: var(--accent); border: none; border-radius: 50px; font-weight: 800; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-auth:hover { transform: scale(1.02); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Create Account</h2>
    <p style="opacity:0.5; margin-bottom:30px;">Join the next dimension of real estate.</p>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" placeholder="John Doe" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="name@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        
        <label>I want to:</label>
        <div class="role-selector">
            <div style="flex:1">
                <input type="radio" name="user_role" value="seeker" id="buy" checked>
                <label for="buy" class="role-label">Buy / Rent</label>
            </div>
            <div style="flex:1">
                <input type="radio" name="user_role" value="provider" id="sell">
                <label for="sell" class="role-label">Sell / Lease</label>
            </div>
        </div>

        <button type="submit" name="signup" class="btn-auth">Create Account</button>
        <p style="text-align:center; margin-top:20px; font-size:14px; opacity:0.6;">
            Already have an account? <a href="login.php" style="color:var(--accent); text-decoration:none;">Sign In</a>
        </p>
    </form>
</div>

</body>
</html>