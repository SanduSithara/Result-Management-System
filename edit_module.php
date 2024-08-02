<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}

include('config.php');

if (isset($_GET['id'])) {
    $module_id = $_GET['id'];

    // Fetch module details
    $sql = "SELECT degree_id, year, semester, module_name, module_code FROM modules WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $module_id);
    $stmt->execute();
    $stmt->bind_result($degree_id, $year, $semester, $module_name, $module_code);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<script>alert('Error: Module ID not provided.'); window.location.href = 'degrees.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['degree'], $_POST['year'], $_POST['semester'], $_POST['module_name'], $_POST['module_code'], $_POST['module_id'])) {
        $degree_id = $_POST['degree'];
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $module_name = $_POST['module_name'];
        $module_code = $_POST['module_code'];
        $module_id = $_POST['module_id'];

        // Prepare SQL statement
        $sql = "UPDATE modules SET degree_id = ?, year = ?, semester = ?, module_name = ?, module_code = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            // Handle prepare error
            die('SQL prepare error: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters and execute query
        $stmt->bind_param("iiissi", $degree_id, $year, $semester, $module_name, $module_code, $module_id);
        if ($stmt->execute()) {
            echo "<script>alert('Module updated successfully!'); window.location.href = 'degrees.php';</script>";
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href = 'degrees.php';</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Error: Missing required form fields.'); window.location.href = 'degrees.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Module</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>Edit Module</h2>
    <form action="edit_module.php?id=<?php echo $module_id; ?>" method="POST">
        <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
        <label for="degree">Select Degree:</label>
        <select id="degree" name="degree" required>
            <?php
            $degrees_sql = "SELECT id, degree_name FROM degrees";
            $degrees_result = $conn->query($degrees_sql);
            while ($degree_row = $degrees_result->fetch_assoc()) {
                $selected = ($degree_row['id'] == $degree_id) ? 'selected' : '';
                echo "<option value='" . $degree_row['id'] . "' $selected>" . $degree_row['degree_name'] . "</option>";
            }
            ?>
        </select><br><br>
        <label for="year">Select Year:</label>
        <select id="year" name="year" required>
            <option value="1" <?php if ($year == 1) echo 'selected'; ?>>1</option>
            <option value="2" <?php if ($year == 2) echo 'selected'; ?>>2</option>
            <option value="3" <?php if ($year == 3) echo 'selected'; ?>>3</option>
            <option value="4" <?php if ($year == 4) echo 'selected'; ?>>4</option>
        </select><br><br>
        <label for="semester">Select Semester:</label>
        <select id="semester" name="semester" required>
            <option value="1" <?php if ($semester == 1) echo 'selected'; ?>>1</option>
            <option value="2" <?php if ($semester == 2) echo 'selected'; ?>>2</option>
        </select><br><br>
        <label for="module_name">Module Name:</label>
        <input type="text" id="module_name" name="module_name" value="<?php echo $module_name; ?>" required><br><br>
        <label for="module_code">Module Code:</label>
        <input type="text" name="module_code" id="module_code" value="<?php echo $module_code; ?>" required><br><br>
        <input type="submit" value="Update Module">
    </form>
</body>
</html>
