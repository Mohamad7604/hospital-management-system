<?php
include('../includes/db.php');
 
 

if (isset($_POST['register'])) {
    // Collect form data
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Check if a patient with this email already exists
    $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "A patient with this email already exists.";
    } else {
        // Insert new patient record
        $stmt = $conn->prepare("INSERT INTO patients (full_name, dob, gender, contact, email, address) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$full_name, $dob, $gender, $contact, $email, $address])) {
            $success = "Registration successful. You can now log in using your email and your date of birth as your password.";
        } else {
            $error = "An error occurred during registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>

<h2>Patient Registration</h2>

<?php 
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color:green;'>$success</p>";
    }
?>

<form method="post">
    <input type="text" name="full_name" placeholder="Full Name" required><br>
    <input type="date" name="dob" placeholder="Date of Birth" required><br>
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br>
    <input type="text" name="contact" placeholder="Contact Number" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <textarea name="address" placeholder="Address" required></textarea><br>
    <button type="submit" name="register">Register</button>
</form>

<p>Note: After registration, log in using your email and your date of birth (YYYY-MM-DD) as your password.</p>

<?php include('../includes/footer.php'); ?>
</body>
</html>