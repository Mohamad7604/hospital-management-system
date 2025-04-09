<?php 
include('../includes/session.php'); 
include('../includes/db.php'); 
include('../includes/header.php'); 

?>

<h2>Add Prescription</h2>

<form method="post">
    <label for="appointment_id">Select Appointment:</label>
    <select name="appointment_id" id="appointment_id" required>
        <option value="">--Select Appointment--</option>
        <?php
        // Fetch upcoming scheduled appointments for this doctor
        $doctorId = $_SESSION['user']['doctor_id'];
        $stmt = $conn->prepare("
            SELECT a.appointment_id, p.full_name, a.appointment_date
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            WHERE a.doctor_id = ? AND a.status = 'Scheduled'
            ORDER BY a.appointment_date ASC
        ");
        $stmt->execute([$doctorId]);
        $appointments = $stmt->fetchAll();
        foreach ($appointments as $appt) {
            echo "<option value='{$appt['appointment_id']}'>
                    Appointment #{$appt['appointment_id']} - {$appt['full_name']} on {$appt['appointment_date']}
                  </option>";
        }
        ?>
    </select>
    <br><br>
    
    <label for="medication">Select Medication:</label>
    <select name="medication" id="medication" required>
        <option value="">--Select Medication--</option>
        <option value="Aspirin 100mg">Aspirin 100mg</option>
        <option value="Paracetamol 500mg">Paracetamol 500mg</option>
        <option value="Ibuprofen 200mg">Ibuprofen 200mg</option>
        <option value="Amoxicillin 250mg">Amoxicillin 250mg</option>
    </select>
    <br><br>
    
    <label for="treatment">Treatment Instructions:</label><br>
    <textarea name="treatment" id="treatment" rows="4" cols="50" placeholder="Enter treatment instructions here..."></textarea>
    <br><br>
    
    <button type="submit" name="prescribe">Add Prescription</button>
</form>

<?php
if (isset($_POST['prescribe'])) {
    $stmt = $conn->prepare("INSERT INTO prescriptions (appointment_id, medication, treatment)
                            VALUES (?, ?, ?)");
    $stmt->execute([$_POST['appointment_id'], $_POST['medication'], $_POST['treatment']]);
    echo "<p style='color:green;'>Prescription added successfully!</p>";
}
?>

<?php include('../includes/footer.php'); ?>