<?php
$servername = "localhost";
$username = "localhost_username"; // Replace with your actual username
$password = "localhost_password"; // Replace with your actual password
$dbname = "camp_bank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
