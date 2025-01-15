<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connection.php';

// Handle the EDIT operation
$reservation = null;
if (isset($_GET['edit'])) {
    $reservation_id = $_GET['edit'];
    $query = "SELECT * FROM reservations WHERE reservation_id='$reservation_id'";
    $result = mysqli_query($conn, $query);

    // Check if the reservation exists
    if (mysqli_num_rows($result) > 0) {
        $reservation = mysqli_fetch_assoc($result);
    } else {
        // Handle the case where the reservation does not exist
        echo "Reservation not found.";
        exit();
    }
}

// Handle CREATE or UPDATE operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : '';
    $customer_name = $_POST['customer_name'];
    $no_of_people = $_POST['no_of_people'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];

    if (!empty($reservation_id)) {
        // Update existing reservation
        $query = "UPDATE reservations SET customer_name='$customer_name', no_of_people='$no_of_people', 
                  reservation_date='$reservation_date', reservation_time='$reservation_time' 
                  WHERE reservation_id='$reservation_id'";
        mysqli_query($conn, $query);
    } else {
        // Create new reservation
        $query = "INSERT INTO reservations (customer_name, no_of_people, reservation_date, reservation_time, created_at) 
                  VALUES ('$customer_name', '$no_of_people', '$reservation_date', '$reservation_time', NOW())";
        mysqli_query($conn, $query);
    }
    header('Location: view_reservations.php');
    exit();
}

// Handle DELETE operation
if (isset($_GET['delete'])) {
    $reservation_id = $_GET['delete'];
    $query = "DELETE FROM reservations WHERE reservation_id='$reservation_id'";
    mysqli_query($conn, $query);
    header('Location: view_reservations.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling for the table and buttons */
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
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 25px;
            margin-left: 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .navbar a:hover {
            transform: scale(1.1);
        }
        h2 {
            color: #fff;
            text-align: center;
            margin-top: 20px;
        }
        .table-container {
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
        }
        table {
            width: 100%;
            color: #fff;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #fff;
        }
        th {
            background-color: #333;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            background: #e67e22;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn i {
            margin-right: 5px; /* Space between icon and button text */
        }
        .btn:hover {
            background: #d35400;
        }
        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 20px auto;
            color: #fff;
        }
        input, button, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .time-slot {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the buttons */
            gap: 10px; /* Space between buttons */
        }
        .time-slot button {
            background: #fff;
            color: #333;
            padding: 5px 10px; /* Smaller padding for smaller buttons */
            border: 1px solid #333;
            cursor: pointer;
            flex: 1 1 calc(33.33% - 10px); /* Make buttons take 1/3 of the row, adjusting for gap */
            max-width: 100px; /* Optional: set a maximum width for buttons */
            text-align: center; /* Center text inside buttons */
            border-radius: 5px;
            transition: background 0.3s, color 0.3s; /* Smooth transition for hover effects */
        }
        .time-slot button:hover {
            background: #e67e22; /* Change background color on hover */
            color: white; /* Change text color on hover */
        }
        .footer {
            background-color: rgba(0,0,0,0.5);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            animation: slideUp 0.5s ease-in-out;
        }
        
    </style>
</head>
<body>

<div class="navbar">
        <h1><a href="admin_dashboard.html">Restaurant Management System</a></h1>
        <div class="navbar-buttons">
            <a href="admin_dashboard.html">Home</a>
            <a href="menu_crud.html">Menu</a>
            <a href="view_reservations.php">Reservations</a>
            <a href="logout.php">Sign Out</a>
        </div>
</div>

<h2>Manage Reservations</h2>

<!-- Form for adding/updating reservations -->
<div class="form-container">
    <form action="view_reservations.php" method="POST">
        <input type="hidden" name="reservation_id" value="<?php echo isset($reservation['reservation_id']) ? $reservation['reservation_id'] : ''; ?>">

        <label for="customer_name">Customer Name</label>
        <input type="text" name="customer_name" placeholder="Customer Name" required value="<?php echo isset($reservation['customer_name']) ? $reservation['customer_name'] : ''; ?>">

        <label for="no_of_people">No of Guests</label>
        <input type="number" name="no_of_people" placeholder="Number of Guests" required value="<?php echo isset($reservation['no_of_people']) ? $reservation['no_of_people'] : ''; ?>">

        <label for="reservation_date">Reservation Date</label>
        <input type="date" name="reservation_date" required value="<?php echo isset($reservation['reservation_date']) ? $reservation['reservation_date'] : ''; ?>">

        <label for="meal_type">Select Time</label>
        <select id="meal_type" name="meal_type">
            <option value="">Select Time</option>
            <option value="lunch" <?php echo isset($reservation['meal_type']) && $reservation['meal_type'] === 'lunch' ? 'selected' : ''; ?>>Lunch</option>
            <option value="dinner" <?php echo isset($reservation['meal_type']) && $reservation['meal_type'] === 'dinner' ? 'selected' : ''; ?>>Dinner</option>
        </select>

        <div id="time_slots" class="time-slot">
            <!-- Dynamic time slots will be injected here -->
        </div>

        <input type="hidden" id="reservation_time" name="reservation_time" value="<?php echo isset($reservation['reservation_time']) ? $reservation['reservation_time'] : ''; ?>">

        <button type="submit" class="btn"><?php echo isset($reservation) ? 'Update Reservation' : 'Add Reservation'; ?></button>
    </form>
</div>

<!-- Display Reservations -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>No of Guests</th>
                <th>Reservation Date</th>
                <th>Reservation Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM reservations";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['reservation_id']}</td>";
                echo "<td>{$row['customer_name']}</td>";
                echo "<td>{$row['no_of_people']}</td>";
                echo "<td>{$row['reservation_date']}</td>";
                echo "<td>{$row['reservation_time']}</td>";
                echo "<td>
                        <a href='view_reservations.php?edit={$row['reservation_id']}' class='btn'><i class='fas fa-edit'></i>Edit</a>
                        <a href='view_reservations.php?delete={$row['reservation_id']}' class='btn'><i class='fas fa-trash'></i>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Restaurant Management System. All rights reserved.
</div>

<script>
    // Function to generate time slots
    function generateTimeSlots() {
        const timeSlotsContainer = document.getElementById('time_slots');
        const mealType = document.getElementById('meal_type');
        timeSlotsContainer.innerHTML = ''; // Clear previous slots

        // Define time slots based on meal type
        let timeSlots = [];
        if (mealType.value === 'lunch') {
            timeSlots = ['12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM'];
        } else if (mealType.value === 'dinner') {
            timeSlots = ['7:00 PM', '7:30 PM', '8:00 PM', '8:30 PM'];
        }

        // Create buttons for each time slot
        timeSlots.forEach(slot => {
            const button = document.createElement('button');
            button.innerText = slot;
            button.onclick = () => selectTimeSlot(slot);
            timeSlotsContainer.appendChild(button);
        });
    }

    // Function to select a time slot
   // Function to select a time slot
function selectTimeSlot(slot) {
    const reservationTimeInput = document.getElementById('reservation_time');

    // Convert '7:30 PM' to '19:30:00'
    const timeParts = slot.split(' ');
    let [hours, minutes] = timeParts[0].split(':');
    if (timeParts[1] === 'PM' && hours !== '12') {
        hours = parseInt(hours) + 12;
    } else if (timeParts[1] === 'AM' && hours === '12') {
        hours = '00';
    }
    reservationTimeInput.value = `${hours}:${minutes}:00`; // Set the selected time in HH:MM:SS format
    alert(`Selected Time: ${reservationTimeInput.value}`); // Optional: alert the selected time
}


    // Event listener for meal type change
    document.getElementById('meal_type').addEventListener('change', generateTimeSlots);
</script>

</body>
</html>
