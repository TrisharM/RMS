<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'db_connection.php';

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $no_of_people = $_POST['no_of_people'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];

    // Convert time from 12-hour format to 24-hour format (HH:MM:SS)
    $reservation_time_24h = date("H:i:s", strtotime($reservation_time));

    // Prepare the SQL statement with placeholders
    $sql = $conn->prepare("INSERT INTO reservations (customer_name, no_of_people, reservation_date, reservation_time) VALUES (?, ?, ?, ?)");

    // Bind parameters (s = string, i = integer)
    $sql->bind_param("siss", $customer_name, $no_of_people, $reservation_date, $reservation_time_24h);

    // Execute the query
    if ($sql->execute()) {
        echo "<script>alert('Reservation successful!'); window.location.href = 'reservations.php';</script>";
    } else {
        echo "Error: " . $sql->error;
    }

    // Close the statement
    $sql->close();
}

// Close the connection
$conn->close();
?>
