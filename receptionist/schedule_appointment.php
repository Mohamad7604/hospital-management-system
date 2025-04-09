<?php include('../includes/db.php'); ?>

<h2>Schedule Appointment</h2>
<form method="post">
    <input type="number" name="patient_id" placeholder="Patient ID" required><br>
    <input type="number" name="doctor_id" placeholder="Doctor ID" required><br>
    <input type="datetime-local" name="appointment_date" required><br>
    <textarea name="notes" placeholder="Notes"></textarea><br>
    <button type="submit" name="schedule">Schedule</button>
</form>

<?php
if (isset($_POST['schedule'])) {
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, notes)
                            VALUES (?, ?, ?, 'Scheduled', ?)");
    $stmt->execute([
        $_POST['patient_id'], $_POST['doctor_id'], $_POST['appointment_date'], $_POST['notes']
    ]);
    echo "Appointment scheduled successfully!";
}
?><?php include('../includes/footer.php');?>