<?php 
include('../includes/session.php'); 
include('../includes/db.php'); 
include('../includes/header.php'); 
?>

<h2>View Appointments</h2>

<form method="get" action="">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="">All</option>
        <option value="Scheduled" <?php if(isset($_GET['status']) && $_GET['status'] == 'Scheduled') echo 'selected'; ?>>Scheduled</option>
        <option value="Completed" <?php if(isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
        <option value="Cancelled" <?php if(isset($_GET['status']) && $_GET['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>
    <button type="submit">Filter</button>
</form>

<table border="1" cellpadding="10" style="width:100%; margin-top:20px;">
    <tr>
        <th>Appointment ID</th>
        <th>Patient Name</th>
        <th>Date &amp; Time</th>
        <th>Status</th>
        <th>Notes</th>
    </tr>

<?php
$doctorId = $_SESSION['user']['doctor_id'];

$sql = "SELECT a.*, p.full_name AS patient_name 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.patient_id 
        WHERE a.doctor_id = ?";
$params = [$doctorId];

if (isset($_GET['status']) && $_GET['status'] != "") {
    $sql .= " AND a.status = ?";
    $params[] = $_GET['status'];
}
$sql .= " ORDER BY a.appointment_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
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