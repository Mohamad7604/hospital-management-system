<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/header.php');

// Fetch a list of patients from the database
$stmt = $conn->query("SELECT patient_id, full_name FROM patients ORDER BY full_name ASC");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Welcome, <?= $_SESSION['user']['name'] ?> (Receptionist)</h2>
<ul>
    <li><a href="register_patient.php">Register Patient</a></li>
    <li><a href="schedule_appointment.php">Schedule Appointment</a></li>
    <li><a href="manage_billing.php">Manage Billing</a></li>
    <li><a href="approve_appointments.php">Approve/Reject Appointments</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
</ul>

<h3>Patient List</h3>
<?php if ($patients): ?>
    <ul>
        <?php foreach ($patients as $patient): ?>
            <li>
                <?= htmlspecialchars($patient['full_name']); ?> - 
                <a href="../messages/conversation.php?patient_id=<?= $patient['patient_id']; ?>">View Conversation</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No patients found.</p>
<?php endif; ?>

<?php include('../includes/footer.php');?>