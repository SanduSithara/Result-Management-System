<?php
session_start();
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.html");
    exit;
}
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $degree_id = $_GET['id'];

    $sql = "SELECT degree_name FROM degrees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $degree_id);
    $stmt->execute();
    $stmt->bind_result($degree_name);
    $stmt->fetch();
    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $degree_id = $_POST['id'];
    $degree_name = $_POST['degree_name'];

    $sql = "UPDATE degrees SET degree_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $degree_name, $degree_id);
    if ($stmt->execute()) {
        header("Location: degrees.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Degree</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Modal styling */
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
    </style>
    <script>
        function showModal() {
            document.getElementById('editDegreeModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editDegreeModal').style.display = 'none';
            window.location.href = 'degrees.php'; // Redirect to degrees page on close
        }
    </script>
</head>
<body onload="showModal()">
    <!-- Modal dialog for editing degree -->
    <div id="editDegreeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Degree</h3>
            <form action="edit_degree.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($degree_id); ?>">
                <label for="degree_name">Degree Name:</label>
                <input type="text" id="degree_name" name="degree_name" value="<?php echo htmlspecialchars($degree_name); ?>" required><br><br>
                <input type="submit" value="Update Degree">
            </form>
        </div>
    </div>
</body>
</html>
