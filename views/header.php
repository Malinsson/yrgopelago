<?php
session_start();

require 'vendor/autoload.php';


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
        </nav>

    </header>