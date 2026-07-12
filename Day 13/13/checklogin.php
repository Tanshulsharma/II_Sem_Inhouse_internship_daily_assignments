<?php
include('db_connect.php');
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 1){
    $_SESSION['email'] = $email;
    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('Invalid email or password');window.location.href='login.php';</script>";
}
?>
