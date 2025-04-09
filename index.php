<?php
 

 
include('./includes/footer.php'); 
// Redirect user if already logged in
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'receptionist') {
        header("Location: receptionist/dashboard.php");
    } elseif ($_SESSION['role'] === 'doctor') {
        header("Location: doctor/dashboard.php");
    } else {
        // You can add a patient role check here if implemented
        echo "Unknown role!";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System</title>
</head>
<body>
    <h1>Welcome to the Hospital Management System</h1>
    <p><a href="auth/login.php">Click here to Login</a></p>
</body>
</html>