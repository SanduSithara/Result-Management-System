
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_report_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
