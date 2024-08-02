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
    <title>Degrees</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Styles for modals, tables, and buttons */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: grey;
        }

        .btn {
            padding: 10px 15px;
            margin: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .btncontainer{
            display:flex;
            justify-content:center;
        }
        .btn-primary {
            background-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function confirmDelete(moduleId, type) {
            if (confirm('Are you sure you want to delete this ' + type + '?')) {
                if(type == 'module') {
                    window.location.href = 'delete_module.php?id=' + moduleId;
                } else if (type == 'degree') {
                    window.location.href = 'delete_degree.php?id=' + moduleId;
                }
            }
        }
    </script>
</head>
<body>
    <h2>Degrees</h2>

    <!-- Buttons to open modals -->
    <div class="btncontainer">
        <button class="btn btn-primary" onclick="showModal('addDegreeModal')">Add Degree</button>
        <button class="btn btn-primary" onclick="showModal('addModuleModal')">Add Module</button>
    </div>

    <!-- Modal dialog for adding degree -->
    <div id="addDegreeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addDegreeModal')">&times;</span>
            <h3>Add Degree</h3>
            <form action="add_degree.php" method="POST">
                <label for="degree_name">Degree Name:</label>
                <input type="text" id="degree_name" name="degree_name" required><br><br>
                <input type="submit" value="Add Degree">
            </form>
        </div>
    </div>

    <!-- Modal dialog for adding module -->
    <div id="addModuleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModuleModal')">&times;</span>
            <h3>Add Module to Degree</h3>
            <form action="add_module.php" method="POST">
                <label for="degree">Select Degree:</label>
                <select id="degree" name="degree" required>
                    <?php
                    $degrees_sql = "SELECT id, degree_name FROM degrees";
                    $degrees_result = $conn->query($degrees_sql);
                    while ($degree_row = $degrees_result->fetch_assoc()) {
                        echo "<option value='" . $degree_row['id'] . "'>" . $degree_row['degree_name'] . "</option>";
                    }
                    ?>
                </select><br><br>
                <label for="year">Select Year:</label>
                <select id="year" name="year" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select><br><br>
                <label for="semester">Select Semester:</label>
                <select id="semester" name="semester" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select><br><br>
                <label for="module_name">Module Name:</label>
                <input type="text" id="module_name" name="module_name" required><br><br>
                <label for="module_code">Module Code:</label>
                <input type="text" name="module_code" id="module_code" required><br><br>
                <input type="submit" value="Add Module">
            </form>
        </div>
    </div>

    <h3>Existing Degrees:</h3>
    <table>
        <tr>
            <th>Degree Name</th>
            <th>Actions</th>
        </tr>
        <?php
        $degrees_sql = "SELECT id, degree_name FROM degrees";
        $degrees_result = $conn->query($degrees_sql);
        while ($degree_row = $degrees_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $degree_row['degree_name'] . "</td>";
            echo "<td>
                    <button class='btn btn-secondary' onclick=\"window.location.href='edit_degree.php?id=" . $degree_row['id'] . "'\">Edit</button> 
                    <button class='btn btn-danger' onclick=\"confirmDelete(" . $degree_row['id'] . ", 'degree')\">Delete</button>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h3>Modules by Degree:</h3>
    <?php
    // Reset the result pointer for degrees
    $degrees_result->data_seek(0);
    while ($degree_row = $degrees_result->fetch_assoc()) {
        echo "<h4>Degree: " . $degree_row['degree_name'] . "</h4>";

        $modules_sql = "SELECT id, module_name, module_code, year, semester FROM modules WHERE degree_id = " . $degree_row['id'];
        $modules_result = $conn->query($modules_sql);

        if ($modules_result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Module Name</th><th>Module Code</th><th>Year</th><th>Semester</th><th>Actions</th></tr>";
            while ($module_row = $modules_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $module_row['module_name'] . "</td>";
                echo "<td>" . $module_row['module_code'] . "</td>";
                echo "<td>" . $module_row['year'] . "</td>";
                echo "<td>" . $module_row['semester'] . "</td>";
                echo "<td>
                        <button class='btn btn-secondary' onclick=\"window.location.href='edit_module.php?id=" . $module_row['id'] . "'\">Edit</button>
                        <button class='btn btn-danger' onclick=\"confirmDelete(" . $module_row['id'] . ", 'module')\">Delete</button>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No modules found for this degree.</p>";
        }
    }
    ?>
</body>
</html>
