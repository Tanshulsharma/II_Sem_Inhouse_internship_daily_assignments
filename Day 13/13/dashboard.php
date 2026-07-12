<?php
include("header.php");
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}
?>

<!-- Dashboard main content -->
<div style="text-align:center; margin-top:40px;">
    <h2>Welcome! <?php echo $_SESSION['email']; ?></h2>
    <p>Use the links below to update your password or register a new user.</p>

    <!-- Update Password link -->
    <a href="updatepassword.php" style="display:inline-block; margin-top:15px; padding:10px 20px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;">
        Update Password
    </a>
    <a href="updateprofile.php" style="display:inline-block; margin-top:15px; padding:10px 20px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;">Update Profile</a>
    <a href="logout.php" style="display:inline-block; margin-top:15px; padding:10px 20px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;">Logout</a>


</div>

<?php
include("footer.php"); // footer with 3 divs
?>
