<?php
session_start();
include 'db_connection.php'; // Include the database connection

// Retrieve posted data
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Database query based on user role
if ($role == 'admin') {
    $query = "SELECT * FROM admins WHERE username='$username'";
} else {
    $query = "SELECT * FROM customers WHERE username='$username'";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Fetch user data
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    // If the user exists and the password is correct, set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $role;
    

    // Set customer name in session for customer role
    if ($role == 'customer') {
        $_SESSION['customer_name'] = $user['username']; // Store customer name
    
    }

    // Redirect based on role
    if ($role == 'admin') {
        header("Location: admin_dashboard.html"); // Redirect admin to the dashboard
        exit(); // Stop further script execution
    } else {
        header("Location: index2.html"); // Redirect customer to index2.html
        exit(); // Stop further script execution
    }
} else {
    // Invalid credentials: store an error message in session and redirect to login page
    $_SESSION['error_message'] = "Invalid credentials. Please try again.";
    header("Location: login.html"); // Redirect back to the login page
    exit(); // Stop further script execution
}
?>
