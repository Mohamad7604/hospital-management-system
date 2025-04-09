<?php include('../includes/session.php'); ?>
<?php include('../includes/db.php'); ?>
<?php include('../includes/header.php'); ?>
<button onclick="window.history.back();" style="margin: 10px 0; padding: 8px 12px; font-size: 16px;">&larr; Back</button>
<h2>My Prescriptions</h2>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Prescription ID</th>
        <th>Appointment Date</th>
        <th>Doctor Name</th>
        <th>Medication</th>
        <th>Treatment</th>
    </tr>

<?php
$patientId = $_SESSION['user']['patient_id'];

$stmt = $conn->prepare("
    SELECT pr.*, a.appointment_date, d.name AS doctor_name 
    FROM prescriptions pr 
    JOIN appointments a ON pr.appointment_id = a.appointment_id 
    JOIN doctors d ON a.doctor_id = d.doctor_id 
    WHERE a.patient_id = ?
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$patientId]);
$prescriptions = $stmt->fetchAll();

if ($prescriptions) {
    foreach ($prescriptions as $presc) {
        echo "<tr>
                <td>{$presc['prescription_id']}</td>
                <td>{$presc['appointment_date']}</td>
                <td>{$presc['doctor_name']}</td>
                <td>{$presc['medication']}</td>
                <td>{$presc['treatment']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No prescriptions found.</td></tr>";
}
?>
</table>

<?php include('../includes/footer.php'); ?>