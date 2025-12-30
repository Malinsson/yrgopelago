<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app/database/database.php';
require_once __DIR__ . '/features.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$envApiKey = $_ENV['API_KEY'] ?? $_ENV['api_key'] ?? null;



if (isset($_POST['name'], $_POST['api-key'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date']) && $_POST['name'] !== '') {
    $name = clean($_POST['name']);
    $apiKey = clean($_POST['api-key']);
    $roomType = clean($_POST['room-type']);
    $arrivalDate = clean($_POST['arrival-date']);
    $departureDate = clean($_POST['departure-date']);
    $returningGuest = false;
    $roomId = getRoomId($roomType);


    // Room availability check
    if ($roomType !== "null") {
        if (!roomAvailability($database, $roomId, $arrivalDate, $departureDate)) {
?>
            <p>Sorry, the selected room type is not available for the chosen dates. Please go back and select different dates or room type.</p>
            <button onclick="window.location.href='index.php'">Go Back</button>
        <?php exit();
        }
    }

    // Calculate total cost

    // Features cost calculation
    if (isset($_POST['features'])) {
        $features = $_POST['features'];
        $totalFeaturesPrice = getFeaturePriceTotal($features, $featureGrid);
    } else {
        $features = [];
        $totalFeaturesPrice = 0;
    }

    // Room cost calculation
    if ($roomType === "null") {
        $totalRoomPrice = 0;
    } else {
        $totalRoomPrice = getRoomPrice($database, $roomId) * calculateDays($arrivalDate, $departureDate);
    }
    $totalCost = $totalFeaturesPrice + $totalRoomPrice;

    if ($totalCost <= 0) {
        ?>
        <p>The total cost of your booking is $0. Please select a room type or features to proceed with the booking.</p>
        <button onclick="window.location.href='index.php'">Go Back</button>
    <?php
        exit();
    }


    // Returning guest discount
    if (returningGuest($database, $name) && $totalCost >= 3) {
        $returningGuest = true;
        $totalCost -= 1;
    }


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


    if (!isset($response['transferCode']) && $response['status'] === 'success') {
    ?>
        <p>There was an error processing your request. Please try again later.</p>
        <p> <?= $response['message'] ?> </p>
        <button onclick="window.location.href='index.php'">Go Back</button>
        <?php exit();
    } else {

        // If transfer code generation was successful, proceed with booking
        $transferCode = $response['transferCode'];

        $depositMoney = [
            'form_params' => [
                'user' => 'Malin',
                'transferCode' => $transferCode,
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


        // Insert guest if new guest
        if (!$returningGuest) {
            $insertGuest = $database->prepare("INSERT INTO guests (name) VALUES (:name)");
            $insertGuest->bindParam(':name', $name);
            $insertGuest->execute();
        }

        $guestId = getGuestId($database, $name);
        $roomId = getRoomId($roomType);

        $featuresUsed = convertFeaturesToReceiptFormat($features, $featureGrid);

        $receipt = [
            'user' => 'Malin',
            'api_key' => $envApiKey,
            'guest_name' => $name,
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'features_used' => $featuresUsed,
            'star_rating' => 2,
        ];


        try {
            $receiptResponse = $client->POST('https://www.yrgopelag.se/centralbank/receipt', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $receipt,
            ]);
            $receiptResponse = $receiptResponse->getBody()->getContents();
            $receiptResponse = json_decode($receiptResponse, true);
            var_dump($receipt) ?>
            <p>Booking successful! Your receipt is: <?= $receiptResponse['receipt_id'] ?></p>

        <?php

        } catch (Exception $e) {
        ?>
            <p>There was an error processing your request. Please try again later.</p>
            <p><?= $e->getMessage() ?></p>
            <button onclick="window.location.href='index.php'">Go Back</button>
<?php
            exit();
        }


        // Insert reservation into database
        insertReservation($database, $guestId, $roomId, $arrivalDate, $departureDate);
        $reservationId = (int)$database->lastInsertId();

        foreach ($featuresUsed as $feature) {
            insertBookedFeatures($database, $reservationId, $feature['id'], $feature['price']);
        }

        insertPayment($database, $reservationId, $totalCost, $transferCode, 'paid');
    }
}
