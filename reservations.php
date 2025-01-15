<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations</title>
    <style>
        /* Same styling as before */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('bg8.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.5); 
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.5s ease-in-out;
        }
        .navbar h1 {
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .navbar-buttons {
            display: flex;
            align-items: center;
        }
        .navbar a {
            color: #faf8f8;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            background-color: transparent;
            border-radius: 25px;
            margin-left: 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            position: relative;
        }
        .navbar a:hover {
            transform: scale(1.1);
        }
        h2, h3 {
            color: white;
            text-align: center;
            margin: 20px 0;
        }
        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            margin: 20px auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            width: 100%;
        }
        input {
            border: 1px solid #ccc;
        }
        button {
            background-color: #e67e22;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #d35400;
        }
        .reservations-container {
            margin-top: 30px;
            color: white;
        }
        .reservation-item {
            background: rgba(0,0,0,0.7);
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .reservation-item button {
            background-color: #c0392b; /* Delete button color */
        }
        .reservation-item button:hover {
            background-color: #a93226; /* Darker shade on hover */
        }
        .time-slot {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the buttons */
            gap: 10px; /* Space between buttons */
        }
        button.active {
            background-color: #2ecc71; /* Highlight selected time */
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1><a href="index2.html" style="color: #faf8f8; text-decoration: none;">Restaurant Management </a></h1>
        <div class="navbar-buttons">
            <a href="index2.html">Home</a>
            <a href="menu.php">Menu</a>
            <a href="reservations.php">Make a Reservation</a>
            <a href="logout.php">Sign Out</a>
        </div>
    </div>

    <h2>Make a Reservation</h2>

    <!-- Reservation Form -->
    <div class="form-container">
        <form action="save_reservation.php" method="POST">
            <?php
            session_start();
            
            // Check if the user is logged in and display their name
            if (isset($_SESSION['customer_name'])) {
                echo '<input type="text" name="customer_name" value="' . htmlspecialchars($_SESSION['customer_name']) . '" readonly>';
            } else {
                echo '<input type="text" name="customer_name" placeholder="Customer Name" required>';
            }
            ?>
            <input type="number" name="no_of_people" placeholder="Number of Guests" required>
            <input type="date" name="reservation_date" required>
            
            <select id="meal_type" name="meal_type" >
                <option value="">Select Time</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
            </select>

            <div id="time_slots" class="time-slot">
                <!-- Dynamic time slots will be injected here -->
            </div>

            <input type="hidden" id="reservation_time" name="reservation_time" value="">
            <button type="submit">Submit Reservation</button>
        </form>
    </div>

    <!-- Display Existing Reservations -->
    <div class="reservations-container">
        <h3>Your Reservations</h3>
        <?php
        // Fetch and display reservations
        if (isset($_SESSION['customer_name'])) {
            include 'db_connection.php'; // Include the database connection
            
            $customer_name = $_SESSION['customer_name'];
            $query = "SELECT * FROM reservations WHERE customer_name='$customer_name'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($reservation = mysqli_fetch_assoc($result)) {
                    echo '<div class="reservation-item">';
                    echo '<div>';
                    echo 'Name: ' . htmlspecialchars($reservation['customer_name']) . '<br>'; // Display customer name
                    echo 'No. of People: ' . htmlspecialchars($reservation['no_of_people']) . '<br>';
                    // Assuming 'date' is in 'Y-m-d' format
                    // Format the date as dd-mm-yyyy
                    $date = new DateTime($reservation['reservation_date']);
                    $formattedDate = $date->format('d-m-Y'); // Format as "Day-Month-Year"
                    echo 'Date: ' . htmlspecialchars($formattedDate) . '<br>';

                    // Format the time as h:m A
                    $time = new DateTime($reservation['reservation_time']);
                    $formattedTime = $time->format('h:i A'); // Format as "Hour:Minute AM/PM"
                    echo 'Time: ' . htmlspecialchars($formattedTime);

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<center><p>No reservations found.</p></center>';
            }
        } else {
            echo '<center><p>Please log in to view your reservations.</p></center>';
        }
        ?>
    </div>

    <!-- JavaScript for dynamic time slot buttons -->
    <script>
    document.getElementById('meal_type').addEventListener('change', function() {
        var timeSlotsDiv = document.getElementById('time_slots');
        timeSlotsDiv.innerHTML = ''; // Clear existing buttons

        var selectedMealType = this.value;

        if (selectedMealType === 'lunch') {
            var lunchSlots = ['12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM'];
            lunchSlots.forEach(function(time) {
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = time;
                button.onclick = function(event) {
                    event.preventDefault(); // Prevent form submission
                    document.getElementById('reservation_time').value = time; // Set the selected time in the hidden input
                    console.log('Selected time:', time); // Debugging line
                    timeSlotsDiv.querySelectorAll('button').forEach(function(btn) {
                        btn.classList.remove('active'); // Remove active state from all buttons
                    });
                    this.classList.add('active'); // Add active state to the clicked button
                    console.log('Hidden input value:', document.getElementById('reservation_time').value); // Debugging line
                };
                timeSlotsDiv.appendChild(button);
            });
        } else if (selectedMealType === 'dinner') {
            var dinnerSlots = ['7:00 PM', '7:30 PM', '8:00 PM', '8:30 PM', '9:00 PM'];
            dinnerSlots.forEach(function(time) {
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = time;
                button.onclick = function(event) {
                    event.preventDefault(); // Prevent form submission
                    document.getElementById('reservation_time').value = time; // Set the selected time in the hidden input
                    console.log('Selected time:', time); // Debugging line
                    timeSlotsDiv.querySelectorAll('button').forEach(function(btn) {
                        btn.classList.remove('active'); // Remove active state from all buttons
                    });
                    this.classList.add('active'); // Add active state to the clicked button
                    console.log('Hidden input value:', document.getElementById('reservation_time').value); // Debugging line
                };
                timeSlotsDiv.appendChild(button);
            });
        }
    });
    </script>
</body>
</html>
