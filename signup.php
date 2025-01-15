<?php
// Database connection
include 'db_connection.php';

// Get form data
$userRole = $_POST['role'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

// Prepare and bind SQL statement
if ($userRole === 'admin') {
    $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
} else {
    $stmt = $conn->prepare("INSERT INTO customers (username, email, password) VALUES (?, ?, ?)");
}

$stmt->bind_param("sss", $username, $email, $password);

// Execute the statement
if ($stmt->execute()) {
    // Signup successful, redirect to login page
    header("Location: login.html");
    exit(); // Stop script execution after redirection
} else {
    // Output error message
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
