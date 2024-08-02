<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .navbar {
            overflow: hidden;
            background-color: #007BFF;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 16px;
        }
        .navbar a:hover {
            background-color: #0056b3;
            color: white;
        }
        .navbar a.active {
            background-color: #004085;
            color: white;
        }
        .navbar-right {
            float: right;
        }
        .navbar a {
            transition: background-color 0.3s, color 0.3s;
        }
        .navbar-right a {
            padding: 14px 20px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <?php if ($_SESSION['role'] == 'teacher'): ?>
        <a href="teacher_dashboard.php" class="active">Dashboard</a>
        <a href="student_list.php">Student List</a>
        <a href="exam_results.php">Exam Results</a>
        <a href="degrees.php">Degrees</a>
        <!-- Commented student options -->
        <!--
        <a href="student_dashboard.php">Student Dashboard</a>
        <a href="view_modules.php">View Modules</a>
        <a href="student_results.php">View Results</a>
        -->
    <?php elseif ($_SESSION['role'] == 'student'): ?>
        <!-- Uncomment these lines when student navigation is needed -->
        
        <a href="student_dashboard.php" class="active">Dashboard</a>
        <!-- <a href="select_modules.php">Select Modules</a> -->
        <a href="view_results.php">View Results</a>
       
    <?php endif; ?>
    <div class="navbar-right">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
