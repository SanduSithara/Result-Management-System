<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['degree'], $_POST['year'], $_POST['semester'], $_POST['module_name'], $_POST['module_code'])) {
        $degree_id = $_POST['degree'];
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $module_name = $_POST['module_name'];
        $module_code = $_POST['module_code'];

        // Check if there are already 4 modules for the selected year and semester
        $check_sql = "SELECT COUNT(*) as count FROM modules WHERE degree_id = ? AND year = ? AND semester = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iii", $degree_id, $year, $semester);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $row = $check_result->fetch_assoc();
        $check_stmt->close();

        if ($row['count'] >= 4) {
            echo "<script>alert('There can only be 4 modules per semester for a year.'); window.location.href = 'degrees.php';</script>";
        } else {
            // Prepare SQL statement
            $sql = "INSERT INTO modules (degree_id, year, semester, module_name, module_code) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                // Handle prepare error
                die('SQL prepare error: ' . htmlspecialchars($conn->error));
            }

            // Bind parameters and execute query
            $stmt->bind_param("iiiss", $degree_id, $year, $semester, $module_name, $module_code);
            if ($stmt->execute()) {
                echo "<script>alert('Module added successfully!'); window.location.href = 'degrees.php';</script>";
            } else {
                echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href = 'degrees.php';</script>";
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        echo "<script>alert('Error: Missing required form fields.'); window.location.href = 'degrees.php';</script>";
    }
}
?>
