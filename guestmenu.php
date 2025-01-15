<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: url('bg6.jpg') no-repeat center center/cover;
        }
        /* Navbar styling */
        .navbar {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px 30px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.5s ease forwards; /* Slide down animation */
        }

        .navbar h1 {
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #faf8f8;
        }

        .navbar-buttons {
            display: flex;
            align-items: center;
        }

        /* Button styles */
        .navbar a {
            color: #faf8f8;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            background-color: transparent;
            border-radius: 25px;
            margin-left: 15px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar a:hover {
            transform: scale(1.1);
        }

        /* Menu Section styling */
        .menu-container {
            position: fixed;
            top: 90px;
            margin-bottom: 20px; /* Increase this value to create more gap */
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            max-width: 1000px;
            height: calc(100vh - 130px); /* Adjust height to fit the new top value */
            margin: 0;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            z-index: 100;
        }

        .category {
            margin-bottom: 20px;
        }

        .menu-item {
            display: flex; /* Use flexbox for layout */
            justify-content: space-between; /* Space out items */
            align-items: center; /* Center align vertically */
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 0;
        }

        .item-info {
            flex: 1; /* Allow this section to grow */
            margin-right: 15px; /* Space between the info and button */
        }

        .item-name {
            font-size: 25px;
            font-weight: bold;
        }

        .item-description {
            color: #555;
            margin: 5px 0;
        }

        .item-price {
            color: green;
            font-size: 15px;
        }

        .menu-container h2 {
            font-size: 35px;
            text-align: center;
        }

        .cat-font {
            font-size: 30px;
            color: #e67e22;
        }

        /* Footer styles */
        footer {
            background-color: rgba(0, 0, 0, 0);
            color: white;
            margin-top: auto;
            text-align: center;
            padding: 15px 0;
            position: relative;
            bottom: 0;
            width: 100%;
            animation: slideUp 0.5s ease forwards; /* Slide up animation */
        }

        /* Animations */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1><a href="index3.html" style="color: #faf8f8; text-decoration: none;">Restaurant Management</a></h1>
        <div class="navbar-buttons">
            <a href="index3.html">Home</a>
            <a href="menu.php">Menu</a>
            <a href="guestreservations.php">Make a Reservation</a>
            <a href="signup.html"><i class="fas fa-user-plus icon"></i>Sign Up</a>
        </div>
    </div>

    <!-- Menu Section -->
    <div class="menu-container">
        <h2>MENU</h2>
        <div id="menu-items" class="menu-items">
        <?php
            include 'db_connection.php';

            // Fetch menu items without ordering
            $sql = "SELECT category, id, name, description, price FROM menu_items";
            $result = $conn->query($sql);

            // Initialize an array to hold menu items grouped by category
            $menu_items = [];

            // Define your desired order for categories
            $category_order = [
                'Appetizers', 
                'Burgers', 
                'Pasta', 
                'Pizzas', 
                'Salads', 
                'Indian', 
                'Seafood', 
                'Specialities', 
                'Desserts', 
                'Beverages'
            ];

            // Group menu items by category
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $menu_items[$row['category']][] = $row;
                }
            }

            // Output menu items in HTML format based on the defined category order
            foreach ($category_order as $category) {
                if (isset($menu_items[$category])) {
                    echo "<div class='category'>";
                    echo "<h3 class='cat-font'>" . htmlspecialchars($category) . "</h3>"; // Category name
                    
                    foreach ($menu_items[$category] as $item) {
                        // Display menu item
                        echo "<div class='menu-item'>";
                        echo "<div class='item-info'>"; // Wrap the item details in a new div
                        echo "<div class='item-name'>" . htmlspecialchars($item["name"]) . "</div>";
                        echo "<div class='item-description'>" . htmlspecialchars($item["description"]) . "</div>";
                        echo "<div class='item-price'>₹" . htmlspecialchars($item["price"]) . "</div>";
                        echo "</div>"; // Close item-info div
                        echo "</div>"; // Close menu-item div
                    }
                    echo "</div>"; // Close category div
                }
            }

            if (empty($menu_items)) {
                echo "<div>No menu items found.</div>";
            }

            $conn->close();
        ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Restaurant Management System. All rights reserved.</p>
    </footer>

</body>
</html>
