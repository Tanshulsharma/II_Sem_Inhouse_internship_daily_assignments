<?php
$error = "";

$name = "";
$email ="";
$password ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = mysqli_real_escape_string($conn, $_POST["fullname"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $password = mysqli_real_escape_string($conn,$_POST["password"]);

    if ($name == "" || $email == "" || $password == "") {
        $error = "All fields are required.";
        echo $error;
    }elseif($password != $password){
        $error = "Password does not match.";
        echo $error;
    } else {
        //insert
        $insertQuery = "Insert into users(name, email, password) values('$name','$email', '$password')";
        $result= mysqli_query($conn, $insertQuery);

        if($result){
           @header('Location: succes.php');
            exit();
        }else{
            echo "Error occurred while storing data";
            echo "Error: " . mysqli_error($conn);
        }
       
    }
}
?>