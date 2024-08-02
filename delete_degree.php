<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}
include('config.php');

if (isset($_GET['id'])) {
    $degree_id = $_GET['id'];

    $sql = "DELETE FROM degrees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $degree_id);
    if ($stmt->execute()) {
        header("Location: degrees.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
