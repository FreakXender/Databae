<?php
$servername = "localhost"; 
$username = "root";  // Change if using another user
$password = "0705";      // Change if you set a password
$database = "bincom_test"; // Ensure this database exists

// Create connection
$conn = new mysqli("localhost", "root", "0705", "bincom_test"); // Ensure 'bincom_test' is your database


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Do NOT close the connection here! Other files will use it.
?>
