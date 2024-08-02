<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    // Initialize success flag
    $success = true;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Retrieve the user_id associated with the student
        $sql = "SELECT user_id FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $student_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->bind_result($user_id);
        if (!$stmt->fetch()) throw new Exception("Fetch failed: " . $stmt->error);
        $stmt->close();

        // Delete entries from grades table
        $sql = "DELETE FROM grades WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $student_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Delete entries from student_modules table
        $sql = "DELETE FROM student_modules WHERE student_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $student_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Delete student entry
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $student_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Delete user entry
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Student deleted successfully!'); window.location.href = 'student_list.php';</script>";

    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo "<script>alert('Error deleting student: " . $e->getMessage() . "'); window.location.href = 'student_list.php';</script>";
    } finally {
        $conn->close();
    }
}
?>
