<?php include('../includes/session.php'); ?>
<?php include('../includes/db.php'); ?>
<?php include('../includes/header.php'); ?>
<button onclick="window.history.back();" style="margin: 10px 0; padding: 8px 12px; font-size: 16px;">&larr; Back</button>
<h2>My Test Results</h2>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Test ID</th>
        <th>Test Type</th>
        <th>Result</th>
        <th>Test Date</th>
    </tr>

<?php
$patientId = $_SESSION['user']['patient_id'];

$stmt = $conn->prepare("
    SELECT * FROM tests 
    WHERE patient_id = ?
    ORDER BY test_date DESC
");
$stmt->execute([$patientId]);
$tests = $stmt->fetchAll();

if ($tests) {
    foreach ($tests as $test) {
        echo "<tr>
                <td>{$test['test_id']}</td>
                <td>{$test['test_type']}</td>
                <td>{$test['result']}</td>
                <td>{$test['test_date']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No test results found.</td></tr>";
}
?>
</table>

<?php include('../includes/footer.php'); ?>