<?php
session_start();

$admin_user = "admin";
$admin_pass = "password123"; // Change this to a secure password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    if ($userid === $admin_user && $password === $admin_pass) {
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid credentials. <a href='login.html'>Try again</a>";
    }
}
?>
