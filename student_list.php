<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('navbar.php');
include('config.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .modal-content .password-container {
            display: flex;
            align-items: center;
        }

        .modal-content .password {
            flex: 1;
            /* padding: 5px; */
            margin-right: 10px;
            box-sizing: border-box;
        }

        .modal-content .show-password {
            display: flex;
            align-items: center;
        }

        .modal-content .show-password label {
            margin-left: 5px;
        }

    </style>
    <script>
        // Function to show modal dialog
        function showModal() {
            document.getElementById('addStudentModal').style.display = 'block';
        }

        // Function to hide modal dialog
        function closeModal() {
            document.getElementById('addStudentModal').style.display = 'none';
        }
        
        function showEditModal(studentId, username, degreeId, enrollYear, currentSemester, studentName) {
            document.getElementById('edit_student_id').value = studentId;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_degree').value = degreeId;
            document.getElementById('edit_enroll_year').value = enrollYear;
            document.getElementById('edit_current_semester').value = currentSemester;
            document.getElementById('edit_student_name').value = studentName;
            document.getElementById('editStudentModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editStudentModal').style.display = 'none';
        }
         // Function to toggle password visibility
         function togglePasswordVisibility(passwordFieldId, checkboxId) {
            var passwordField = document.getElementById(passwordFieldId);
            var checkbox = document.getElementById(checkboxId);
            if (checkbox.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</head>
<body>
    <h2>Student List</h2>

    <!-- Button to open modal -->
    <button class="add-student-button" onclick="showModal()">Add Student</button>

    <!-- Modal dialog for adding student -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add Student</h3>
            <form action="add_student.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Password:</label>
                <span class="password-container">
                    <input class="password" type="password" id="password" name="password" required>
                    <span class="show-password">
                        <input type="checkbox" id="showPasswordAdd" onclick="togglePasswordVisibility('password', 'showPasswordAdd')">
                        
                    </span>
                </span><br><br>
                <label for="degree">Degree Program:</label>
                <select id="edit_degree" name="degree" required>
                <?php
                $degrees_sql = "SELECT id, degree_name FROM degrees";
                $degrees_result = $conn->query($degrees_sql);
                while ($degree_row = $degrees_result->fetch_assoc()) {
                    $selected = ($degree_row['id'] == $degreeId) ? "selected" : "";
                    echo "<option value='" . $degree_row['id'] . "' $selected>" . $degree_row['degree_name'] . "</option>";
                }
                ?>
            </select><br><br>
                <label for="enroll_year">Enroll Year:</label>
                <select id="enroll_year" name="enroll_year" required>
                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>
                    <option value="4">Year 4</option>
                </select>
                <label for="current_semester">Current Semester:</label>
                <select id="current_semester" name="current_semester" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select><br><br>
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required><br><br>
                <input type="submit" value="Add Student">
            </form>
        </div>
    </div>

    <!-- Modal dialog for editing student -->
    <div id="editStudentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Edit Student</h3>
            <form action="edit_student.php" method="POST">
                <input type="hidden" id="edit_student_id" name="student_id">
                <label for="edit_username">Username:</label>
                <input type="text" id="edit_username" name="username" required><br><br>
                <label for="edit_degree">Degree Program:</label>
                <select id="edit_degree" name="degree" required>
                    <?php
                    $degrees_sql = "SELECT id, degree_name FROM degrees";
                    $degrees_result = $conn->query($degrees_sql);
                    while ($degree_row = $degrees_result->fetch_assoc()) {
                        echo "<option value='" . $degree_row['id'] . "'>" . $degree_row['degree_name'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <label for="edit_enroll_year">Enroll Year:</label>
                <select id="edit_enroll_year" name="enroll_year" required>
                    <option value="1">Year 1</option>
                    <option value="2">Year 2</option>
                    <option value="3">Year 3</option>
                    <option value="4">Year 4</option>
                </select>
                <label for="edit_current_semester">Current Semester:</label>
                <select id="edit_current_semester" name="current_semester" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select><br><br>
                <label for="edit_student_name">Student Name:</label>
                <input type="text" id="edit_student_name" name="student_name" required><br><br>
                <input type="submit" value="Edit Student">
            </form>
        </div>
    </div>

    <!-- Display student list -->
    <?php
    $sql = "SELECT s.id, u.username, d.degree_name, s.enroll_year, s.current_semester, s.student_name
            FROM students s
            INNER JOIN users u ON s.user_id = u.id
            INNER JOIN degrees d ON s.degree_id = d.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Student Username</th>
                    <th>Degree</th>
                    <th>Enroll Year</th>
                    <th>Current Semester</th>
                    <th>Student Name</th>
                    <th>Actions</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['degree_name'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['enroll_year'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['current_semester'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>" . htmlspecialchars($row['student_name'], ENT_QUOTES, 'UTF-8') . "</td>
                            <td>
                                <div class='action-buttons'>
                                    <button style='background-color: #007BFF; color: white; border: none; padding: 10px 20px; font-size: 16px; margin-right: 5px; border-radius: 5px; cursor: pointer;' 
                                            onclick=\"showEditModal('" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "', '" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "', '" . htmlspecialchars($row['degree_name'], ENT_QUOTES, 'UTF-8') . "', '" . htmlspecialchars($row['enroll_year'], ENT_QUOTES, 'UTF-8') . "', '" . htmlspecialchars($row['current_semester'], ENT_QUOTES, 'UTF-8') . "', '" . htmlspecialchars($row['student_name'], ENT_QUOTES, 'UTF-8') . "')\">
                                        Edit
                                    </button>
                                    <form action='delete_student.php' method='POST' style='display: inline; margin: 0;'>
                                        <input type='hidden' name='student_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                        <input type='submit' value='Delete' style='background-color: #dc3545; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer;' 
                                               onclick=\"return confirm('Are you sure you want to delete this student?');\">
                                    </form>
                                </div>
                            </td>
                          </tr>";
                }
                
        echo "</table>";
    } else {
        echo "No students found.";
    }
    $conn->close();
    ?>
</body>
</html>
