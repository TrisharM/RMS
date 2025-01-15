<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Restaurant Management System</title>
    <style>
        /* Add styling similar to login page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .forgot-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            max-height: 300px;
            text-align: center;
            color: white;
            margin: 20px auto;
            backdrop-filter: blur(10px);
        }

        .forgot-container h2 {
            font-size: 30px;
            margin-bottom: 20px;
        }

        .forgot-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .forgot-container input[type="password"]:focus {
            outline: none;
            border: 2px solid #e74c3c;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .forgot-container input[type="submit"] {
            background-color: #e74c3c;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-top: 15px;
        }

        .forgot-container input[type="submit"]:hover {
            background-color: #c0392b;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: url('bg7.jpg') no-repeat center center/cover; /* Add background image */
        }

        .navbar {
            background: rgba(0, 0, 0, 0.5); /* Change text color for contrast */
            padding: 20px 30px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.5s ease-in-out; /* Slide down animation */
        }

        .navbar h1 {
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: transform 0.3s ease; /* Add transition for hover effect */
        }

        .navbar-buttons {
            display: flex;
            align-items: center;
        }

        /* Transparent buttons with hover effect */
        .navbar a {
            color: #faf8f8; /* Change link color for contrast */
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            background-color: transparent; /* Transparent background */
            border-radius: 25px;
            margin-left: 15px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
            position: relative; /* Position for pseudo-element */
        }

        .navbar a::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            background-color: #e74c3c; /* Color for the underline effect */
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .navbar a:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1><a href="index.html" style="color: #faf8f8; text-decoration: none;">Restaurant Management</a></h1>
        
    </div>

    <div class="forgot-container">
        <h2>Reset Password</h2>
        <p>Enter your new password</p>

        <form action="password-reset.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
