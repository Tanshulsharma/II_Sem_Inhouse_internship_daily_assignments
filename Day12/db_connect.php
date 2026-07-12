<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$password = "";
$database = "industrial_training";

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!$conn->select_db($database)) {
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$database`")) {
        die("Unable to create database: " . $conn->error);
    }
    if (!$conn->select_db($database)) {
        die("Unable to select database: " . $conn->error);
    }
}

$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(150) DEFAULT '',
        profile_image VARCHAR(255) DEFAULT NULL,
        skills TEXT DEFAULT NULL,
        role ENUM('student','teacher','admin','super_admin') NOT NULL DEFAULT 'student',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");