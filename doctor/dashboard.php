<?php include('../includes/session.php'); ?>
<?php include('../includes/db.php'); ?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/footer.php'); ?>
<h2>Doctor Dashboard</h2>

<p>Welcome, <strong><?= $_SESSION['user']['name']; ?></strong></p>

<hr>

<h3>Navigation</h3>
<ul style="list-style-type: none; padding: 0;">
    <li><a href="view_appointments.php">📅 View My Appointments</a></li>
    <li><a href="add_prescription.php">💊 Add Prescription</a></li>
    <li><a href="request_test.php">🧪 Request a Test</a></li>
    <li><a href="view_test_results.php">📋 View Test Results</a></li>
    <li><a href="../auth/logout.php">🚪 Logout</a></li>
</ul>

<hr>

<h3>Upcoming Appointments</h3>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Appointment ID</th>
        <th>Patient Name</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th>Notes</th>
    </tr>

<?php
$doctorId = $_SESSION['user']['doctor_id'];

$stmt = $conn->prepare("
    SELECT a.*, p.full_name AS patient_name 
    FROM appointments a 
    JOIN patients p ON a.patient_id = p.patient_id
    WHERE a.doctor_id = ? 
    ORDER BY a.appointment_date ASC
    LIMIT 10
");
$stmt->execute([$doctorId]);

$appointments = $stmt->fetchAll();

if ($appointments) {
    foreach ($appointments as $appt) {
        echo "<tr>
                <td>{$appt['appointment_id']}</td>
                <td>{$appt['patient_name']}</td>
                <td>{$appt['appointment_date']}</td>
                <td>{$appt['status']}</td>
                <td>{$appt['notes']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No appointments found.</td></tr>";
}
?>

</table>

<?php include('../includes/footer.php'); ?>