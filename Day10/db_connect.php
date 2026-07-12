<?php
$host     = "localhost";
$user     = "root";
$password = "";//yaha pahle password "12345" tha
$database = "skit";//industrial_training

$conn= mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

echo "Connection Successful!";
?>
