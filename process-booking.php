<?php

declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app/database/database.php';

if (isset($_POST['name'], $_POST['api-key'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date']) && $_POST['name'] !== '') {
    $name = clean($_POST['name']);
    $apiKey = clean($_POST['api-key']);
    $roomType = clean($_POST['room-type']);
    $arrivalDate = clean($_POST['arrival-date']);
    $departureDate = clean($_POST['departure-date']);


    // Room availability check
    if ($roomType !== "null") {
        if (!roomAvailability($database, $roomType, $arrivalDate, $departureDate)) { ?>
            <p>Sorry, the selected room type is not available for the chosen dates. Please go back and select different dates or room type.</p>
            <button onclick="window.location.href='index.php'">Go Back</button>
        <?php exit();
        }
    }


    // Calculate total cost
    if (isset($_POST['features'])) {
        $features = $_POST['features'];
        $totalFeaturesPrice = getFeaturePriceTotal($features, $featureGrid);
    } else {
        $features = [];
        $totalFeaturesPrice = 0;
    }

    if ($roomType === "null") {
        $totalRoomPrice = 0;
    } else {
        $totalRoomPrice = getRoomPrice($database, getRoomId($roomType)) * calculateDays($arrivalDate, $departureDate);
    }
    $totalCost = $totalFeaturesPrice + $totalRoomPrice;


    // Transfer code generation
    $client = new \GuzzleHttp\Client();

    $getTransferCode = [
        'form_params' => [
            'user' => $name,
            'api_key' => $apiKey,
            'amount' => $totalCost,
        ],
    ];

    try {
        $response = $client->POST('https://www.yrgopelag.se/centralbank/withdraw', $getTransferCode);
        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);
    } catch (Exception $e) {
        ?>
        <p>There was an error processing your request. Please try again later.</p>
        <p><?= $e->getMessage() ?></p>
        <button onclick="window.location.href='index.php'">Go Back</button>
        <?php
        exit();
    }

    // If transfer successful, proceed with booking
    if (isset($response['transferCode']) && $response['status'] === 'success') {
        $transferCode = $response['transferCode'];

        $depositMoney = [
            'form_params' => [
                'user' => 'Malin',
                'uuid-string' => $transferCode,
            ],
        ];

        try {
            $depositResponse = $client->POST('https://www.yrgopelag.se/centralbank/deposit', $depositMoney);
            $depositResponse = $depositResponse->getBody()->getContents();
            $depositResponse = json_decode($depositResponse, true);
        } catch (Exception $e) {
        ?>
            <p>There was an error processing your request. Please try again later.</p>
            <p><?= $e->getMessage() ?></p>
            <button onclick="window.location.href='index.php'">Go Back</button>
        <?php exit();
        }


        // Returning guest check
        if (returningGuest($database, $name)) {
            echo "<p>Welcome back, " . toUppercase($name) . "!</p>";
        } else {
            $insertGuest = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
            $insertGuest->bindParam(':name', $name);
            $insertGuest->execute();
        }


        $guestId = getGuestId($database, $name);
        $roomId = getRoomId($roomType);


        insertReservation($database, $guestId, $roomId, $arrivalDate, $departureDate);
        $reservationId = (int)$database->lastInsertId();
        foreach ($features as $feature) {
            insertBookedFeatures($database, $reservationId, $feature);
        }

        $fuaturesUsed =

            $recipt = [
                'user' => 'Malin',
                'api_key' => $_ENV['API_KEY'],
                'guest_name' => $name,
                'arrival_date' => $arrivalDate,
                'departure_date' => $departureDate,
                'features_used' => $featuresSerialized,
                'star_rating' => 2,

            ];

        $recipt = json_encode($recipt);

        try {
            $client->POST('https://www.yrgopelag.se/centralbank/recipt', [
                'body' => $recipt,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
        } catch (Exception $e) {
        ?>
            <p>There was an error processing your request. Please try again later.</p>
            <p><?= $e->getMessage() ?></p>
            <button onclick="window.location.href='index.php'">Go Back</button>
<?php
            exit();
        }
    }
    /*
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
<?php */
}
