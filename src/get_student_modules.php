<?php
include('config.php');

// Use null coalescing operator to provide default values
$student_id = $_GET['student_id'] ?? null;
$current_year = $_GET['current_year'] ?? null;
$current_semester = $_GET['current_semester'] ?? null;

// parameters
if ($student_id === null || $current_year === null || $current_semester === null) {
    echo "Missing required parameters.";
    exit;
}

// modules for the student
$sql = "SELECT m.id as module_id, m.module_name, m.module_code, sm.year, sm.semester, g.grade, g.mid_marks
        FROM student_modules sm
        INNER JOIN modules m ON sm.module_id = m.id
        LEFT JOIN grades g ON sm.student_id = g.student_id AND sm.module_id = g.module_id
        WHERE sm.student_id = ?
        ORDER BY sm.year, sm.semester";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$modules = [];
while ($row = $result->fetch_assoc()) {
    $modules[$row['year']][$row['semester']][] = $row;
}

$stmt->close();
$conn->close();

//year and semester
if (!empty($modules)) {
    foreach ($modules as $year => $semesters) {
        foreach ($semesters as $semester => $modules) {
            echo "<h3>Year: $year, Semester: $semester</h3>";
            echo "<table>
                    <tr>
                        <th>Module Code</th>
                        <th>Module Name</th>
                        <th>Grade</th>
                        <th>Mid Marks</th>
                        <th>Actions</th>
                    </tr>";
            foreach ($modules as $module) {
                $disabled = ($year > $current_year || ($year == $current_year && $semester > $current_semester)) ? "disabled" : "";
                echo "<tr>
                        <td>{$module['module_code']}</td>
                        <td>{$module['module_name']}</td>
                        <td>{$module['grade']}</td>
                        <td>{$module['mid_marks']}</td>
                        <td>
                            <button onclick='showGradeModal($student_id, {$module['module_id']})' $disabled>Add/Update Grade</button>
                            <button onclick='showMidMarksModal($student_id, {$module['module_id']})' $disabled>Add/Update Mid Marks</button>
                        </td>
                    </tr>";
            }
            echo "</table>";
        }
    }
} else {
    echo "No modules found.";
}
?>