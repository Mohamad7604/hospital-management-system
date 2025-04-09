<?php include('../includes/session.php'); ?>
<?php include('../includes/db.php'); ?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/footer.php');?>
<h2>Doctor Availability</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Doctor ID</th>
        <th>Name</th>
        <th>Specialization</th>
        <th>Upcoming Appointments</th>
    </tr>

<?php
// Fetch doctors
$doctors = $conn->query("SELECT * FROM doctors");

foreach ($doctors as $doc) {
    // Count upcoming appointments
    $stmt = $conn->prepare("SELECT COUNT(*) AS upcoming FROM appointments 
                            WHERE doctor_id = ? AND appointment_date >= NOW() AND status = 'Scheduled'");
    $stmt->execute([$doc['doctor_id']]);
    $count = $stmt->fetch();

    echo "<tr>
            <td>{$doc['doctor_id']}</td>
            <td>{$doc['name']}</td>
            <td>{$doc['specialization']}</td>
            <td>{$count['upcoming']}</td>
          </tr>";
}
?>

</table>

<?php include('../includes/footer.php'); ?>