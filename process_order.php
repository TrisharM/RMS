<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);

// Log the raw received data for debugging purposes
file_put_contents('php_error_log.txt', print_r($data, true), FILE_APPEND);

// Check if data is received correctly
if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received or invalid JSON."]);
    exit();
}

$username = $data['username'] ?? null;
$items = $data['items'] ?? null;
$totalItems = $data['totalItems'] ?? null;
$totalPrice = $data['totalPrice'] ?? null;

// Validate that all required fields are present
if (is_null($username) || is_null($items) || is_null($totalItems) || is_null($totalPrice)) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit();
}

// Validate that total items and total price are numeric
if (!is_numeric($totalItems) || !is_numeric($totalPrice)) {
    echo json_encode(["success" => false, "message" => "Invalid data: Total items and price must be numeric."]);
    exit();
}

include 'db.connection.php';

// Check for database connection error
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Prepare SQL query
$stmt = $conn->prepare("INSERT INTO orders (username, items, total_items, total_price) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Failed to prepare statement: " . $conn->error]);
    exit();
}

// Convert items to JSON for storage
$itemsJson = json_encode($items);

// Bind the parameters (username, items JSON, total items, total price)
$stmt->bind_param("ssdd", $username, $itemsJson, $totalItems, $totalPrice);

// Execute the query and check for errors
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Order has been confirmed!"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to execute query: " . $stmt->error]);
}

$stmt->close();
$conn->close();
