<?php
// Start the session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine the user's name
$userName = 'Guest'; // Default if no name found
if (isset($_SESSION['user'])) {
    // Use 'name' for receptionist/doctor; for patients, use 'full_name'
    if (!empty($_SESSION['user']['name'])) {
        $userName = $_SESSION['user']['name'];
    } elseif (!empty($_SESSION['user']['full_name'])) {
        $userName = $_SESSION['user']['full_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header style="background:#2E86C1; color:white; padding:15px;">
    <h1>Hospital Management System</h1>
    <p>Welcome, <strong><?= $userName; ?></strong></p>
    <nav>
        <a href="../auth/logout.php" style="color:white;">Logout</a>
    </nav>
</header>
<main>