<?php include('../includes/session.php'); ?>
<?php include('../includes/header.php'); ?>

<h2>Welcome, <?= $_SESSION['user']['full_name'] ?> (Patient)</h2>

<ul style="list-style: none; padding: 0;">
    <li><a href="book_appointment.php">📅 Book an Appointment</a></li>
    <li><a href="view_appointments.php">📅 My Appointments</a></li>
    <li><a href="view_prescriptions.php">💊 My Prescriptions</a></li>
    <li><a href="view_tests.php">🧪 My Test Results</a></li>
    <li><a href="view_bills.php">💵 My Bills</a></li>

    <!-- NEW LINK to conversation page -->
    <li><a href="../messages/conversation.php">💬 Chat with Receptionist</a></li>

    <li><a href="../auth/logout.php">🚪 Logout</a></li>
</ul>

<?php include('../includes/footer.php');?>