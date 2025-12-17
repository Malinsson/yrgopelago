<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/features.php';

?>

<form method="post" action="process-booking.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="transfer-code">Transfer-code:</label>
    <input type="text" id="transfer-code" name="transfer-code" required>

    <label for="room-type">Select room type:</label>
    <select id="room-type" name="room-type" required>
        <option value="budget">Budget</option>
        <option value="standard">Standard</option>
        <option value="luxury">Luxury</option>
        <option value="null">No room</option>
    </select>

    <label for="arrival-date">Select arrival date:</label>
    <input type="date" id="arrival-date" name="arrival-date" required>

    <label for="departure-date">Select departure date:</label>
    <input type="date" id="departure-date" name="departure-date" required>

    <?php displayFeaturesCheckboxes($featureGrid); ?>


    <button type="submit">Book Now</button>
</form>