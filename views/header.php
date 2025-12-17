<?php
session_start();

require 'vendor/autoload.php';

use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Exception $e) {
    error_log('Dotenv error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yrgopelago</title>
    <link rel="stylesheet" href="./app/app.css">
    <link rel="stylesheet" type="text/css" href="css/calendar.css">
</head>

<body>
    <header>
        <h1>Yrgopelago</h1>
    </header>