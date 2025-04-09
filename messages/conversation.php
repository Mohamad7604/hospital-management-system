<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/header.php');

// Determine conversation parameters based on user role
if ($_SESSION['role'] == 'patient') {
    // For patients, use their own patient ID
    $patientId = $_SESSION['user']['patient_id'];
    $conversationPartner = "Receptionist";
} elseif ($_SESSION['role'] == 'receptionist') {
    // For receptionist, require a patient_id parameter in the URL
    if (isset($_GET['patient_id'])) {
        $patientId = $_GET['patient_id'];
    } else {
        echo "<p>Please select a patient to view the conversation.</p>";
        include('../includes/footer.php');
        exit;
    }
    // Optionally, get the patient’s name
    $stmt = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
    $stmt->execute([$patientId]);
    $patientInfo = $stmt->fetch();
    $conversationPartner = $patientInfo ? $patientInfo['full_name'] : "Unknown Patient";
} else {
    echo "Unauthorized access.";
    include('../includes/footer.php');
    exit;
}

// Process form submission to send a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        // The sender is the current role
        $sender = $_SESSION['role']; // "patient" or "receptionist"
        // Set the receiver as the opposite role
        $receiver = ($sender == 'patient') ? 'receptionist' : 'patient';
        $stmt = $conn->prepare("INSERT INTO chat_messages (sender, receiver, message, patient_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sender, $receiver, $message, $patientId]);
        // Redirect to avoid resubmission
        header("Location: conversation.php" . (($_SESSION['role'] == 'receptionist') ? "?patient_id=" . $patientId : ""));
        exit;
    }
}

// Retrieve all messages for this conversation (by patient_id)
$stmt = $conn->prepare("SELECT sender, message, DATE_FORMAT(timestamp, '%Y-%m-%d %H:%i:%s') AS timestamp 
                        FROM chat_messages 
                        WHERE patient_id = ? 
                        ORDER BY timestamp ASC");
$stmt->execute([$patientId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Conversation with <?= htmlspecialchars($conversationPartner); ?></h2>

<!-- Display conversation history -->
<div style="border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll; background: #f9f9f9;">
    <?php if ($messages): ?>
        <?php foreach ($messages as $msg): ?>
            <p>
                <strong><?= ucfirst(htmlspecialchars($msg['sender'])); ?>:</strong>
                <?= htmlspecialchars($msg['message']); ?>
                <br>
                <small><?= $msg['timestamp']; ?></small>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No messages yet.</p>
    <?php endif; ?>
</div>

<!-- Message sending form -->
<form method="post" style="margin-top:20px;">
    <textarea name="message" rows="3" style="width:100%;" placeholder="Type your message here..."></textarea>
    <button type="submit" style="margin-top:10px;">Send Message</button>
</form>

<!-- Manual refresh button -->
<button onclick="window.location.reload();" style="margin-top:20px;">Refresh Conversation</button>

<?php include('../includes/footer.php');?>