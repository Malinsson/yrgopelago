<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/features.php';
require_once __DIR__ . '/app/database/database.php';

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

    <button type="submit">Book Now</button>
</form>


<!-- JavaScript to sync departure date with arrival date -->
<script>
    const arrivalDateInput = document.getElementById('arrival-date');
    const departureDateInput = document.getElementById('departure-date');

    function syncDepartureWithArrival() {
        if (!arrivalDateInput.value) return;

        const arrivalDate = new Date(arrivalDateInput.value);
        arrivalDate.setDate(arrivalDate.getDate() + 1);

        const minDepartureDate = `${arrivalDate.getFullYear()}-${String(arrivalDate.getMonth() + 1).padStart(2, '0')}-${String(arrivalDate.getDate()).padStart(2, '0')}`;
        departureDateInput.min = minDepartureDate;

        if (!departureDateInput.value || departureDateInput.value < minDepartureDate) {
            departureDateInput.value = minDepartureDate;
        }
    }

    syncDepartureWithArrival();

    arrivalDateInput.addEventListener('change', function() {
        syncDepartureWithArrival();
    });
</script>