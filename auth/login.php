<?php
session_start();
include('../includes/db.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role === 'receptionist') {
        $stmt = $conn->prepare("SELECT * FROM receptionists WHERE email = ? AND password = ?");
    } elseif ($role === 'doctor') {
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE email = ? AND password = ?");
    } elseif ($role === 'patient') {
        $stmt = $conn->prepare("SELECT * FROM patients WHERE email = ? AND dob = ?");
    }

    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;

        if ($role === 'receptionist') {
            header("Location: ../receptionist/dashboard.php");
        } elseif ($role === 'doctor') {
            header("Location: ../doctor/dashboard.php");
        } elseif ($role === 'patient') {
            header("Location: ../patient/dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="post">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password / DOB for Patient (YYYY-MM-DD)" required><br>
                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="receptionist">Receptionist</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select><br>
                <button type="submit" name="login">Login</button>
            </form>
            <p class="register-link">New patient? <a href="../patient/register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
