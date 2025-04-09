<?php
include('../includes/session.php');
include('../includes/db.php');
include('../includes/header.php');

// Function to generate available appointment slots based on doctor's schedule
function getAvailableSlots($doctorId) {
    $availableSlots = [];
    // Start from tomorrow and check for the next 14 days
    $startDate = new DateTime('tomorrow');
    $endDate   = new DateTime('+14 days');

    // Dummy schedule: Even doctor IDs -> Mon, Wed, Fri at 09:00, 10:00, 11:00
    //                Odd  doctor IDs -> Tue, Thu at 14:00, 15:00, 16:00
    if ($doctorId % 2 == 0) {
        $workingDays = ['Mon', 'Wed', 'Fri'];
        $timeSlots   = ['09:00', '10:00', '11:00'];
    } else {
        $workingDays = ['Tue', 'Thu'];
        $timeSlots   = ['14:00', '15:00', '16:00'];
    }

    $interval = new DateInterval('P1D');
    for ($date = clone $startDate; $date <= $endDate; $date->add($interval)) {
        $day = $date->format('D');
        if (in_array($day, $workingDays)) {
            foreach ($timeSlots as $slot) {
                // Format for HTML5 datetime-local input: "Y-m-d\TH:i" (with a literal "T")
                $availableSlots[] = $date->format('Y-m-d') . 'T' . $slot;
            }
        }
    }
    return $availableSlots;
}

// ──────────────────────────────────────────────────────────
// STEP 3: Final Submission (User chose date/time and clicked "Submit Appointment Request")
// ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_final'])) {
    $patientId       = $_SESSION['user']['patient_id'];
    $selectedDisease = $_POST['disease'];
    $doctorId        = $_POST['doctor_id'];
    $appointmentSlot = $_POST['appointment_slot'];
    $notes           = $_POST['notes'];

    // Insert the appointment with status "Pending"
    $stmt = $conn->prepare("
        INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, notes)
        VALUES (?, ?, ?, 'Pending', ?)
    ");
    if ($stmt->execute([$patientId, $doctorId, $appointmentSlot, $notes])) {
        $success = "Your appointment has been successfully booked and is pending approval. Thank you!";
    } else {
        $error = "There was an error booking your appointment. Please try again.";
    }

    // Display the result here
    echo '<h2>Appointment Status</h2>';
    if (isset($success)) {
        echo '<p style="color:green;">' . $success . '</p>';
    }
    if (isset($error)) {
        echo '<p style="color:red;">' . $error . '</p>';
    }

    // Button to return to patient dashboard
    echo '<button onclick="window.location.href=\'dashboard.php\';" style="margin-top:20px;">Return to Dashboard</button>';

    include('../includes/footer.php');
    exit; // Stop execution so we stay on this confirmation
}

// ──────────────────────────────────────────────────────────
// STEP 3: Doctor selected, show available slots
// ──────────────────────────────────────────────────────────
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor_id']) && !isset($_POST['book_final'])) {
    $selectedDisease = $_POST['disease'];
    $doctorId        = $_POST['doctor_id'];

    // Fetch doctor details (optional)
    $stmt = $conn->prepare("SELECT name FROM doctors WHERE doctor_id = ?");
    $stmt->execute([$doctorId]);
    $doctor = $stmt->fetch();

    // Generate available slots
    $availableSlots = getAvailableSlots($doctorId);
    ?>
    <h2>Book an Appointment with Dr. <?= htmlspecialchars($doctor['name']); ?></h2>
    <form method="post">
        <!-- Keep selected disease and doctor in hidden fields -->
        <input type="hidden" name="disease" value="<?= htmlspecialchars($selectedDisease); ?>">
        <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctorId); ?>">

        <label for="appointment_slot">Select Appointment Slot:</label>
        <select name="appointment_slot" id="appointment_slot" required>
            <option value="">-- Select an Available Slot --</option>
            <?php foreach ($availableSlots as $slot): ?>
                <option value="<?= $slot; ?>">
                    <?= date("l, F j, Y", strtotime($slot)) . " at " . date("g:i A", strtotime($slot)); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="notes">Notes (Optional):</label><br>
        <textarea name="notes" id="notes" rows="4" cols="50" placeholder="Enter any additional information"></textarea>
        <br><br>

        <button type="submit" name="book_final">Submit Appointment Request</button>
    </form>
    <button onclick="window.history.back();" style="margin-top:20px;">&larr; Back</button>
    <?php
}

// ──────────────────────────────────────────────────────────
// STEP 2: Disease selected, choose a doctor
// ──────────────────────────────────────────────────────────
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disease'])) {
    $selectedDisease = $_POST['disease'];

    // Retrieve doctors with that specialization
    $stmt = $conn->prepare("SELECT doctor_id, name FROM doctors WHERE specialization = ?");
    $stmt->execute([$selectedDisease]);
    $doctors = $stmt->fetchAll();
    ?>
    <h2>Book an Appointment for <?= htmlspecialchars($selectedDisease); ?></h2>
    <form method="post">
        <!-- Keep the selected disease -->
        <input type="hidden" name="disease" value="<?= htmlspecialchars($selectedDisease); ?>">

        <label for="doctor_id">Choose a Doctor:</label>
        <select name="doctor_id" id="doctor_id" required>
            <option value="">-- Select a Doctor --</option>
            <?php foreach ($doctors as $doc): ?>
                <option value="<?= $doc['doctor_id']; ?>">
                    <?= htmlspecialchars($doc['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit" name="select_doctor">Next</button>
    </form>
    <button onclick="window.history.back();" style="margin-top:20px;">&larr; Back</button>
    <?php
}

// ──────────────────────────────────────────────────────────
// STEP 1: No disease selected yet, show disease selection form
// ──────────────────────────────────────────────────────────
else {
    // Retrieve distinct specializations from the doctors table
    $stmt = $conn->query("SELECT DISTINCT specialization FROM doctors");
    $specializations = $stmt->fetchAll();
    ?>
    <h2>Book an Appointment</h2>
    <form method="post">
        <label for="disease">Select Your Disease / Specialization:</label>
        <select name="disease" id="disease" required>
            <option value="">-- Select Disease --</option>
            <?php foreach ($specializations as $spec): ?>
                <option value="<?= htmlspecialchars($spec['specialization']); ?>">
                    <?= htmlspecialchars($spec['specialization']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit" name="select_disease">Next</button>
    </form>
    <?php
}

include('../includes/footer.php');
?>