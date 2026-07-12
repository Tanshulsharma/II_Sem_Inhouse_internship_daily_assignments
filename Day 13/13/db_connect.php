<?php
$host="localhost";
$user="root";
$password="";
$database="industrail_training";
$conn = mysqli_connect($host,$user,$password,$database);
if(!$conn){
    die("Connection Failed :".mqsoli_connect_error());
}
echo"Database Connected Successfully";
?>