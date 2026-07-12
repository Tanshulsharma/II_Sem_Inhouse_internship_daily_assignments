<?php
session_start();
if(!isset($_SESSION['user_name'])){
    header("location: login.php");
    exit();
}

include('index.php') //header with navigation
?>

<!--Dashboard main content-->

<div style="text-align:centre;margin-top:40px;">
    <h2>Welcome!Tanshul Sharma</h2>
    <p>Use the links above to update your password or register a new user.</p>
</div>

<?php
include("footer.php");// footer with 3 divs
?>                      