<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        h2 {
            background-color: #007BFF;
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
        .logout-btn {
            padding: 5px 15px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            background-color: #007BFF;
            transition: background-color 0.3s;
            width: 10%;
            margin-right:2%;
            justify-content:center;
        }
        .logout-btn:hover {
            background-color: #0056b3;
        }
        .main-content {
            margin-top: 80px; /* Adjust this to give space for the fixed header */
            display: flex;
            justify-content: left;
            align-items: center;
            width: 100%;
            height: calc(100% - 80px); /* Subtract the height of the fixed header */
        }
        .dashboard-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 10%;
        }
        ul {
            list-style-type: none;
            margin: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        li {
            width: 100%;
            height: 25%;
            margin: 10px 0;
            padding-top: 1%;
        }
        a {
            text-decoration: none;
            color: white;
            padding: 20px 30px;
            display: inline-block;
            background-color: #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s;
            width: calc(100% - 100px);
            text-align: left;
            height: 100%;
            display: flex;
            align-items: center;
            font-size: 18px;
        }
        a:hover {
            background-color: #0056b3;
        }
        .icon {
            margin-right: 15px;
            font-size: 24px;
        }
        .background {
            position: absolute;
            right: 10%;
            top: 20%; /* Align with the fixed header height */
            bottom: 0;
            z-index: -1;
            overflow: hidden;
        }
        .background img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h2>
        Welcome, <?php echo $_SESSION['username']; ?>
        <a href="logout.php" class="logout-btn">Logout</a>
    </h2>
    <div class="main-content">
        <div class="dashboard-container">
            <ul>
                <li><a href="student_list.php"><i class="fas fa-users icon"></i>Student List</a></li>
                <li><a href="exam_results.php"><i class="fas fa-file-alt icon"></i>Exam Results</a></li>
                <li><a href="degrees.php"><i class="fas fa-graduation-cap icon"></i>Degrees</a></li>
            </ul>
        </div>
    </div>
    <div class="background">
        <img src="teach_dashboard_background.png" alt="background" />
    </div>
</body>
</html>
