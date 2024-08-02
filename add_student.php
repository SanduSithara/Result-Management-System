<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $student_name = $_POST['student_name'];
    $degree_id = $_POST['degree'];
    $enroll_year = $_POST['enroll_year'];
    $current_semester = $_POST['current_semester'];

    // Check if the username already exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Username already exists
        $stmt->close();
        echo "<script>alert('Username already exists. Please try a different username.'); window.location.href = 'student_list.php';</script>";
    } else {
        // Create user entry
        $sql = "INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, 'student')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $student_name);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Create student entry
            $sql = "INSERT INTO students (user_id, degree_id, enroll_year, current_semester, student_name) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiis", $user_id, $degree_id, $enroll_year, $current_semester, $student_name);

            if ($stmt->execute()) {
                // Get the student ID
                $student_id = $stmt->insert_id;

                // Retrieve the list of modules for the degree
                $sql = "SELECT id, year, semester FROM modules WHERE degree_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $degree_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Insert entries into the student_modules table
                $insert_sql = "INSERT INTO student_modules (student_id, module_id, year, semester) VALUES (?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);

                while ($row = $result->fetch_assoc()) {
                    $module_id = $row['id'];
                    $module_year = $row['year'];
                    $module_semester = $row['semester'];
                    $insert_stmt->bind_param("iiii", $student_id, $module_id, $module_year, $module_semester);
                    $insert_stmt->execute();
                }

                $insert_stmt->close();
                echo "<script>alert('Student and modules added successfully!'); window.location.href = 'student_list.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'student_list.php';</script>";
            }
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'student_list.php';</script>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
