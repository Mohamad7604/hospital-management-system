<?php 
include('../includes/session.php'); 
include('../includes/db.php'); 
include('../includes/header.php'); 
?>

<h2>View Test Results</h2>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Test ID</th>
        <th>Patient Name</th>
        <th>Test Type</th>
        <th>Result</th>
        <th>Test Date</th>
    </tr>

<?php
// Get doctor ID from session
$doctorId = $_SESSION['user']['doctor_id'];

// Fetch test results for this doctor
$stmt = $conn->prepare("
    SELECT t.*, p.full_name AS patient_name 
    FROM tests t 
    JOIN patients p ON t.patient_id = p.patient_id 
    WHERE t.doctor_id = ? 
    ORDER BY t.test_date DESC
");
$stmt->execute([$doctorId]);
$tests = $stmt->fetchAll();

if ($tests) {
    foreach ($tests as $test) {
        echo "<tr>
                <td>{$test['test_id']}</td>
                <td>{$test['patient_name']}</td>
                <td>{$test['test_type']}</td>
                <td>{$test['result']}</td>
                <td>{$test['test_date']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No test results found.</td></tr>";
}
?>
</table>

<?php include('../includes/footer.php'); ?>