<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $username = $_POST['username'];
    $degree_id = $_POST['degree'];
    $enroll_year = $_POST['enroll_year'];
    $current_semester = $_POST['current_semester'];
    $student_name = $_POST['student_name'];

    // Update user entry
    $sql = "UPDATE users SET username = ? WHERE id = (SELECT user_id FROM students WHERE id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $username, $student_id);

    if ($stmt->execute()) {
        // Update student entry
        $sql = "UPDATE students SET degree_id = ?, enroll_year = ?, current_semester = ?, student_name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisi", $degree_id, $enroll_year, $current_semester, $student_name, $student_id);

        if ($stmt->execute()) {
            echo "<script>alert('Student details updated successfully!'); window.location.href = 'student_list.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'student_list.php';</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'student_list.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
