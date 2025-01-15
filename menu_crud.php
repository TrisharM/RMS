<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

include 'db_connection.php'; // Your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Add a new item
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $category);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item.']);
        }
    } elseif ($action === 'update') {
        // Update an item
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        $stmt = $conn->prepare("UPDATE menu_items SET name=?, description=?, price=?, category=? WHERE id=?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update item.']);
        }
    } elseif ($action === 'delete') {
        // Delete an item
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM menu_items WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete item.']);
        }
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all menu items
    $result = $conn->query("SELECT * FROM menu_items");
    $menu_items = [];

    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }

    echo json_encode($menu_items);
}

$conn->close();
?>
