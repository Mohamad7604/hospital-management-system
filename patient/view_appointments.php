<?php
// 1. Include session & DB
include('../includes/session.php');
include('../includes/db.php');

// 2. Include header
include('../includes/header.php');
?>

<h2>My Appointments</h2>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Appointment ID</th>
        <th>Doctor Name</th>
        <th>Date &amp; Time</th>
        <th>Status</th>
        <th>Notes</th>
    </tr>

<?php
// 3. Get the currently logged-in patient ID from session
$patientId = $_SESSION['user']['patient_id'];

// 4. Select appointments and include the status column
$stmt = $conn->prepare("
    SELECT a.appointment_id,
           a.appointment_date,
           a.status,
           a.notes,
           d.name AS doctor_name
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.patient_id = ?
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$patientId]);
$appointments = $stmt->fetchAll();

// 5. Display each appointment in a table row
if ($appointments) {
    foreach ($appointments as $appt) {
        echo "<tr>
                <td>{$appt['appointment_id']}</td>
                <td>Dr. {$appt['doctor_name']}</td>
                <td>{$appt['appointment_date']}</td>
                <td>{$appt['status']}</td>
                <td>{$appt['notes']}</td>
              </tr>";
    }
} else {
    // If no appointments found
    echo "<tr><td colspan='5'>No appointments found.</td></tr>";
}
?>
</table>

<button onclick="window.history.back();" style="margin-top:20px;">&larr; Back</button>

<?php
// 6. Include footer
include('../includes/footer.php');
?>