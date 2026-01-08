<?php

declare(strict_types=1);

require_once __DIR__ . '/app/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$envApiKey = $_ENV['API_KEY'] ?? $_ENV['api_key'] ?? null;

// Helper function to redirect with error
function redirectWithError($message, $details = null)
{
    $_SESSION['bookingError'] = [
        'message' => $message,
        'details' => $details
    ];
    header('Location: booking-error.php');
    exit();
}

if (isset($_POST['name'], $_POST['api-key'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date']) && $_POST['name'] !== '') {
    $name = clean($_POST['name']);
    $apiKey = clean($_POST['api-key']);
    $roomId = (int) clean($_POST['room-type']);
    $arrivalDate = clean($_POST['arrival-date']);
    $departureDate = clean($_POST['departure-date']);
    $returningGuest = false;

    // Room availability check
    if ($roomId !== 0) {
        if (!roomAvailability($database, $roomId, $arrivalDate, $departureDate)) {
            redirectWithError('Sorry, the selected room type is not available for the chosen dates. Please select different dates or room type.');
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
    if ($roomId === 0) {
        $totalRoomPrice = 0;
    } else {
        $totalRoomPrice = getRoomPrice($database, $roomId) * calculateDays($arrivalDate, $departureDate);
    }
    $totalCost = $totalFeaturesPrice + $totalRoomPrice;

    if ($totalCost <= 0) {
        redirectWithError('The total cost of your booking is $0. Please select a room type or features to proceed with the booking.');
    }


    // Returning guest discount
    if (returningGuest($database, $name) && $totalCost >= $minimumBookingForDiscount) {
        $returningGuest = true;
        $totalCost -= $returningGuestDiscount;
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
        redirectWithError('There was an error processing your request. Please try again later.', $e->getMessage());
    }

    // Check for API errors
    if (isset($response['status']) && $response['status'] === 'error') {
        redirectWithError('There was an error processing your request. Please try again later.', $response['message'] ?? 'Unknown error');
    }

    // Transfer code generation was successful, proceed with booking
    $transferCode = $response['transferCode'];

    // Create receipt
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
    } catch (Exception $e) {
        redirectWithError('There was an error processing your receipt. Please try again later.', $e->getMessage());
    }

    // Deposit money
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
        redirectWithError('There was an error processing your deposit. Please try again later.', $e->getMessage());
    }

    // Insert guest if new guest
    if (!returningGuest($database, $name)) {
        insertGuest($database, $name);
    }

    $guestId = getGuestId($database, $name);

    // Insert reservation into database
    insertReservation($database, $guestId, $roomId, $arrivalDate, $departureDate);
    $reservationId = (int)$database->lastInsertId();

    // Insert booked features into database
    if (!empty($features)) {
        foreach ($features as $feature) {
            $featureId = getFeatureIdByName($database, $feature);
            $featurePrice = getFeaturePriceByName($database, $feature);
            insertBookedFeatures($database, $reservationId, $featureId, $featurePrice);
        }
    }

    // Insert payment into database
    insertPayment($database, $reservationId, $totalCost, $transferCode, 'paid');

    // Store booking data in session and redirect to confirmation page
    $_SESSION['bookingData'] = [
        'success' => true,
        'receipt' => $receipt,
        'receiptResponse' => $receiptResponse,
        'totalCost' => $totalCost,
    ];

    header('Location: booking-confirmation.php');
    exit();
}
