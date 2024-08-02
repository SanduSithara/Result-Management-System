<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if (isset($_GET['id'])) {
    $module_id = $_GET['id'];

    // Prepare SQL statement to delete module
    $sql = "DELETE FROM modules WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $module_id);

    if ($stmt->execute()) {
        echo "<script>alert('Module deleted successfully!'); window.location.href = 'degrees.php';</script>";
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href = 'degrees.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Error: Module ID not provided.'); window.location.href = 'degrees.php';</script>";
}
?>
