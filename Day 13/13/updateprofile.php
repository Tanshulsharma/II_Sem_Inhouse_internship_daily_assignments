<?php
include("header.php");
session_start();
include('db_connect.php');

$updated = false;

if(isset($_POST['update'])){
    $email = $_SESSION['email']; // current logged in user

    $name   = $_POST['name'];
    $email  = $_POST['email'];
    

    // Update query
    $query = "UPDATE users SET name='$name', email='$email' WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if($result){
        $updated = true;
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        form {
            width: 350px; margin: 80px auto; background-color: white;
            padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea, button {
            width: 100%; padding: 10px; margin: 8px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background-color: #4CAF50; color: white; border: none; cursor: pointer;
        }
        button:hover { background-color: #45a049; }
        h2 { text-align: center; }
        .success {
            text-align: center; margin-top: 20px; color: green; font-weight: bold;
        }
        .link-btn {
            display:inline-block; margin-top:15px; padding:10px 20px;
            background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;
        }
    </style>
</head>
<body>
    <?php if($updated): ?>
        <div class="success">Profile updated successfully!</div>
        <div style="text-align:center;">
            <a href="dashboard.php" class="link-btn">Go to Dashboard</a>
            <a href="updateprofile.php" class="link-btn">Update Again</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <h2>Update Profile</h2>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="email" placeholder="email Id" required>
            <button type="submit" name="update">Update Profile</button>
        </form>
    <?php endif; ?>
    <?php include("footer.php") ?>
</body>
</html>
