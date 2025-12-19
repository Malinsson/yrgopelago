<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app/database/database.php';

if (isset($_POST['name'], $_POST['transfer-code'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date'], $_POST['features']) && $_POST['name'] !== '') {
    $name = clean($_POST['name']);
    $transferCode = clean($_POST['transfer-code']);
    $roomType = clean($_POST['room-type']);
    $arrivalDate = clean($_POST['arrival-date']);
    $departureDate = clean($_POST['departure-date']);
    $features = $_POST['features'] ?? [];
    $featuresSerialized = serialize($features);

    if ($roomType !== "null") {
        if (!roomAvailability($database, $roomType, $arrivalDate, $departureDate)) { ?>
            <p>Sorry, the selected room type is not available for the chosen dates. Please go back and select different dates or room type.</p>
            <button onclick="window.location.href='index.php'">Go Back</button>
    <?php exit();
        }
    }

    $guests = searchAllGuests($database);
    $previousGuests = array_column($guests, "name");

    if (!in_array($name, $previousGuests)) {
        $insertGuest = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
        $insertGuest->bindParam(':name', $name);
        $insertGuest->execute();
    }

    $returningGuest = $database->prepare("SELECT id FROM guests WHERE name = :name");
    $returningGuest->bindParam(':name', $name);
    $returningGuest->execute();
    $guestData = $returningGuest->fetch(PDO::FETCH_ASSOC);
    $guestId = $guestData['id'];
    insertReservation($database, $guestId, $roomType, $arrivalDate, $departureDate, $featuresSerialized);

    ?>
    <p>Booking successful! Here are your details:</p>
    <ul>
        <li>Name: <?= $name ?></li>
        <li>Transfer Code: <?= $transferCode ?></li>
        <li>Room Type: <?= $roomType ?></li>
        <li>Arrival Date: <?= $arrivalDate ?></li>
        <li>Departure Date: <?= $departureDate ?></li>
        <li>Selected Features:
            <ul>
                <?php foreach ($features as $feature): ?>
                    <li><?= toUppercase($feature) ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    <button onclick="window.location.href='index.php'">Make another booking</button>
<?php }
