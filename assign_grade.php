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
    $grade = $_POST['grade'];

    // Check if the grade record already exists
    $sql = "SELECT * FROM grades WHERE student_id = ? AND module_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $student_id, $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // Update the existing grade record
        $sql = "UPDATE grades SET grade = ? WHERE student_id = ? AND module_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $grade, $student_id, $module_id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Grade updated successfully!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error: ' . $stmt->error;
        }
    } else {
        // Insert a new grade record
        $sql = "INSERT INTO grades (student_id, module_id, grade) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $student_id, $module_id, $grade);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Grade assigned successfully!';
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
