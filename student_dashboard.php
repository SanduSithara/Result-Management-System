<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}

include('config.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        .background {
            position: absolute;
            left: 0;
            top: 20%;
            /* bottom: 0;
            width: 40%; */
            z-index: -1;
            overflow: hidden;
        }

        .background img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            background-color:#007BFF;
            color: white;
            padding: 20px;
            width: 100%;
            text-align: center;
            margin: 0;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-button {
            padding: 5px 15px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            background-color: rgba(0, 86, 179, 0.9);
            transition: background-color 0.3s;
            width: 10%;
            margin-right:2%;
        }

        .logout-button:hover {
            background-color: #003d80;
        }

        .main-content {
            margin-top: 80px; /* Adjust this to give space for the fixed header */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: calc(100% - 80px); /* Subtract the height of the fixed header */
        }

        .options {
            list-style-type: none;
            margin: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .option {
            width: 100%;
            height: 25%;
            margin: 10px 0;
            padding-top: 1%;
        }

        button {
            text-decoration: none;
            color: white;
            padding: 20px 30px;
            background-color: rgba(0, 86, 179, 0.9);
            border-radius: 5px;
            transition: background-color 0.3s;
            width: calc(100% - 10px);
            text-align: left;
            height: 100%;
            display: flex;
            align-items: center;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #003d80;
        }

        .icon {
            margin-right: 15px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <h2>
        Welcome, <?php echo $_SESSION['username']; ?>
        <a href="logout.php" class="logout-button">Logout</a>
    </h2>
    <div class="main-content">
        <div class="background">
            <img src="student_dashboard_background.png" alt="background" />
        </div>
        <div class="container">
            <div class="options">
                <!-- <div class="option">
                    <h3>Select Modules</h3>
                    <button onclick="location.href='select_modules.php'"><i class="fas fa-book icon"></i>Select Modules</button>
                </div> -->
                <div class="option">
                    <h3>Dashboard</h3>
                    <button onclick="location.href='view_results.php'"><i class="fas fa-chart-bar icon"></i>View Results</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
