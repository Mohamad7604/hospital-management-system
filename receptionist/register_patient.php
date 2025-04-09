<?php include('../includes/db.php'); ?>
<h2>Register New Patient</h2>
<form method="post">
    <input type="text" name="full_name" placeholder="Full Name" required><br>
    <input type="date" name="dob" required><br>
    <select name="gender">
        <option>Male</option>
        <option>Female</option>
    </select><br>
    <input type="text" name="contact" placeholder="Contact Number"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <textarea name="address" placeholder="Address"></textarea><br>
    <button type="submit" name="register">Register</button>
</form>

<?php
if (isset($_POST['register'])) {
    $stmt = $conn->prepare("INSERT INTO patients (full_name, dob, gender, contact, email, address)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['full_name'], $_POST['dob'], $_POST['gender'],
        $_POST['contact'], $_POST['email'], $_POST['address']
    ]);
    echo "Patient registered successfully!";
}
?>