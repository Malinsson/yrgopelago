<?php
require_once __DIR__ . '/app/autoload.php';

$roomsData = searchAllRooms($database);
$roomPrices = [
    '0' => 0  // No room
];

foreach ($roomsData as $room) {
    $roomPrices[$room['id']] = $room['price_per_night'];
}

$featurePrices = [];
foreach ($featureGrid as $feature) {
    $featurePrices[$feature['feature']] = $feature['base_price'];
}

$bookingData = [
    'roomPrices' => $roomPrices,
    'featurePrices' => $featurePrices
];

?>


<form method="post" action="process-booking.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="api-key">Api-key:</label>
    <input type="text" id="api-key" name="api-key" required>

    <label for="room-type">Select room type:</label>
    <select id="room-type" name="room-type" required>
        <option value='1'>Budget</option>
        <option value='2'>Standard</option>
        <option value='3'>Luxury</option>
        <option value='0'>No room</option>
    </select>

    <div class="date-wrapper">
        <div class="arrival-date-wrapper">
            <label for="arrival-date">Select arrival date:</label>
            <input type="date" id="arrival-date" name="arrival-date" min="2026-01-01" max="2026-01-31" required>
        </div>
        <div class="departure-date-wrapper">
            <label for="departure-date">Select departure date:</label>
            <input type="date" id="departure-date" name="departure-date" min="2026-01-02" max="2026-01-31" required>
        </div>
    </div>
    <fieldset>
        <legend>Select Features:</legend>
        <?php displayFeaturesCheckboxes($featureGrid); ?>
    </fieldset>

    <div id="total-price-container">
        <p>Total Price: $<span id="total-price">0</span></p>
    </div>
    <button type="submit">Book Now</button>
</form>


<!-- Pass PHP data to JavaScript file -->
<script>
    window.bookingData = <?= json_encode($bookingData); ?>;
</script>