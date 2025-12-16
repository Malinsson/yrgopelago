<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app/database/database.php';

if (isset($_POST['name'], $_POST['transfer-code'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date']) && $_POST['name'] !== '') {
    $name = clean($_POST['name']);
    $transferCode = clean($_POST['transfer-code']);
    $roomType = clean($_POST['room-type']);
    $arrivalDate = clean($_POST['arrival-date']);
    $departureDate = clean($_POST['departure-date']);
    $features = $_POST['features'] ?? []; ?>

   