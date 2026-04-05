<?php
include 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prop_id = $_POST['property_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO inquiries (property_id, name, email, message) VALUES ('$prop_id', '$name', '$email', '$message')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Thank you! Our agent will contact you soon.'); window.location='index.php';</script>";
    }
}
?>