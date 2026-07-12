<?php
include("header.php");
session_start();
include('db_connect.php');

if(isset($_POST['update'])){
    $email = $_SESSION['email'];
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    if($new == $confirm){
        $query = "UPDATE users SET password='$new' WHERE email='$email' AND password='$current'";
        $result = mysqli_query($conn, $query);
        if($result){
            echo "<script>alert('Password updated successfully');window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating password');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        form {
            width: 300px;
            margin: 100px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Update Password</h2>
        <input type="password" name="current" placeholder="Current Password" required>
        <input type="password" name="new" placeholder="New Password" required>
        <input type="password" name="confirm" placeholder="Confirm New Password" required>
        <button type="submit" name="update">Update Password</button>
    </form>
</body>
</html>
