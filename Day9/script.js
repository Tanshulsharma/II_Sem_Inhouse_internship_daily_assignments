<?php
// Database connection details
$servername = "localhost";
$username   = "root";      // default for XAMPP/WAMP
$password   = "";          // default is empty
$dbname     = "registration"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<h3 style='color:red;'>Connection failed: " . $conn->connect_error . "</h3>");
}

// Collect form data
$name     = $_POST['name'];
$dob      = $_POST['dob'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];
$password = $_POST['password'];
$confirm  = $_POST['confirm'];
$gender   = $_POST['gender'];
$department = $_POST['department'];
$address  = $_POST['address'];

// Validate password match
if ($password !== $confirm) {
    echo "<h3 style='color:red;'>Passwords do not match!</h3>";
    exit;
}

// Hash password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO users (name, dob, email, phone, password, gender, department, address)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $dob, $email, $phone, $hashedPassword, $gender, $department, $address);

// Execute and check result
if ($stmt->execute()) {
    echo "<h2 style='color:green;'>Registration Successful!</h2>";
    echo "<p>Welcome, <strong>$name</strong>. Your data has been saved.</p>";
} else {
    echo "<h3 style='color:red;'>Error: " . $stmt->error . "</h3>";
}

// Close connection
$stmt->close();
$conn->close();
?>