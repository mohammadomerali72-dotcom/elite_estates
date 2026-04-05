<?php session_start(); ?>
<nav>
    <div class="logo"><h1>ELITE<span>ESTATES</span></h1></div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Agar user login hai -->
            <li style="color:var(--accent)">Welcome, <?php echo $_SESSION['user_name']; ?></li>
            <?php if($_SESSION['user_role'] == 'provider'): ?>
                <li><a href="provider_dashboard.php" class="btn-admin">My Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php" style="color:#ff4d4d;">Logout</a></li>
        <?php else: ?>
            <!-- Agar user login nahi hai -->
            <li><a href="login.php">Sign In</a></li>
            <li><a href="signup.php" class="btn-admin">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>