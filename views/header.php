<?php

require_once __DIR__ . '/../app/autoload.php';


use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Exception $e) {
    error_log('Dotenv error: ' . $e->getMessage());
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yrgopelago</title>
    <link rel="stylesheet" href="./app/app.css">
    <link rel="stylesheet" type="text/css" href="css/calendar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h2 class="header-name">Mos'Le'Harmless</h2>
        <div class="v-line"></div>
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="#room-showcase">Rooms</a></li>
                <li><a href="#booking-section">Booking</a></li>
            </ul>

            <ul class="navbar admin-nav">
                <?php if (!isset($_SESSION['adminLoggedIn']) || $_SESSION['adminLoggedIn'] !== true) { ?>
                    <li id="admin-login">Admin Login</li>
                <?php } else { ?>
                    <li><a href="admin/logout.php">Logout</a></li>
                <?php } ?>
            </ul>
        </nav>


        <form method="post" action="admin/login.php" id="admin-login-form" class="hidden">
            <label for="user">Username:</label>
            <input type="text" id="user" name="user" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login" value="1">Login as Admin</button>
        </form>

    </header>