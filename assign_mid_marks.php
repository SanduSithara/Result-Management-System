<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $module_id = $_POST['module_id'];
    $mid_marks = $_POST['mid_marks'];

    // Check if the grade record already exists
    $sql = "SELECT * FROM grades WHERE student_id = ? AND module_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $student_id, $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // Update the existing grade record
        $sql = "UPDATE grades SET mid_marks = ? WHERE student_id = ? AND module_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $mid_marks, $student_id, $module_id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Mid marks updated successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }
    } else {
        // Insert a new grade record with the mid marks
        $sql = "INSERT INTO grades (student_id, module_id, mid_marks) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $student_id, $module_id, $mid_marks);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Mid marks assigned successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
