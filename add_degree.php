<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $degree_name = $_POST['degree_name'];

    $sql = "INSERT INTO degrees (degree_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $degree_name);
    if ($stmt->execute()) {
        header("Location: degrees.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
