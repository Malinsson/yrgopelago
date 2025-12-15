<?php

declare(strict_types=1);

if (isset($_POST['name'], $_POST['transfer-code'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date'])) {
    $name = htmlspecialchars($_POST['name']);
    $transferCode = htmlspecialchars($_POST['transfer-code']);
    $roomType = htmlspecialchars($_POST['room-type']);
    $arrivalDate = htmlspecialchars($_POST['arrival-date']);
    $departureDate = htmlspecialchars($_POST['departure-date']);

    // Here you would typically process the booking, e.g., save to a database
    // For demonstration, we'll just display a confirmation message

    echo "<h2>Booking Confirmation</h2>";
    echo "<p>Thank you, $name, for your booking!</p>";
    echo "<p>Transfer Code: $transferCode</p>";
    echo "<p>Room Type: $roomType</p>";
    echo "<p>Arrival Date: $arrivalDate</p>";
    echo "<p>Departure Date: $departureDate</p>";
} else {
    echo "<p>Error: All fields are required.</p>";
}
?>
<button onclick="window.history.back();">Go Back</button>