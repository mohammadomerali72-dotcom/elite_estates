<?php
session_start();
session_destroy(); // Saara login data khatam kar dena
header("Location: login.php"); // Wapas login page par bhej dena
exit();
?>