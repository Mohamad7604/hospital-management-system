<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/header.php');

// Process approval or rejection actions if provided via GET
if (isset($_GET['action']) && isset($_GET['id'])) {
    $appointmentId = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $newStatus = 'Scheduled';
    } elseif ($action === 'reject') {
        $newStatus = 'Rejected';
    } else {
        // Default to Pending if action is unrecognized
        $newStatus = 'Pending';
    }

    // Update the appointment status
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
    $stmt->execute([$newStatus, $appointmentId]);

    // Redirect to refresh the page and avoid duplicate actions on refresh
    header("Location: approve_appointments.php");
    exit;
}

// Fetch all pending appointments along with relevant details including patient email
$stmt = $conn->prepare("
    SELECT a.appointment_id,
           a.doctor_id,
           a.appointment_date,
           a.notes,
           p.full_name AS patient_name,
           p.email AS patient_email,
           d.name AS doctor_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.patient_id
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.status = 'Pending'
    ORDER BY a.appointment_date ASC
");
$stmt->execute();
$appointments = $stmt->fetchAll();
?>

<h2>Pending Appointment Requests</h2>

<?php if ($appointments): ?>
<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Appointment ID</th>
        <th>Patient Name</th>
        <th>Doctor Name</th>
        <th>Appointment Date</th>
        <th>Notes</th>
        <th>Conflicts</th>
        <th>Contact</th>
        <th>Action</th>
    </tr>
    <?php foreach ($appointments as $appt): ?>
        <?php
        // Check for any conflicting appointments (same doctor, same date/time, status Pending or Scheduled)
        $conflictStmt = $conn->prepare("
            SELECT COUNT(*) AS conflict_count
            FROM appointments
            WHERE doctor_id = ?
              AND appointment_date = ?
              AND status IN ('Pending','Scheduled')
              AND appointment_id != ?
        ");
        $conflictStmt->execute([
            $appt['doctor_id'],
            $appt['appointment_date'],
            $appt['appointment_id']
        ]);
        $conflictCount = $conflictStmt->fetchColumn();
        ?>
        <tr>
            <td><?= $appt['appointment_id'] ?></td>
            <td><?= htmlspecialchars($appt['patient_name']); ?></td>
            <td><?= htmlspecialchars($appt['doctor_name']); ?></td>
            <td><?= $appt['appointment_date']; ?></td>
            <td><?= htmlspecialchars($appt['notes']); ?></td>
            <td>
                <?php if ($conflictCount > 0): ?>
                    Conflict with <?= $conflictCount ?> other appointment(s)
                <?php else: ?>
                    No conflict
                <?php endif; ?>
            </td>
            <td>
                <!-- Contact Patient via Email -->
                <a href="mailto:<?= htmlspecialchars($appt['patient_email']); ?>">Contact Patient</a>
            </td>
            <td>
                <a href="?action=approve&id=<?= $appt['appointment_id'] ?>">Approve</a> |
                <a href="?action=reject&id=<?= $appt['appointment_id'] ?>">Reject</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No pending appointments found.</p>
<?php endif; ?>

<button onclick="window.history.back();" style="margin-top:20px;">&larr; Back</button>

<?php include('../includes/footer.php');?>