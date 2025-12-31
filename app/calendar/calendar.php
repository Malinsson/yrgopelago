<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../database/database.php';

use benhall14\phpCalendar\Calendar as Calendar;

$selectedRoomId = isset($_POST['room-type']) ? (int) $_POST['room-type'] : 1;

try {
    $calendar = new Calendar();
    $calendar->useMondayStartingDate();

    $bookedDates = searchBookedDates($database);

    foreach ($bookedDates as $booking) {
        if ($booking['room_id'] === $selectedRoomId) {

            $calendar->addEvent(
                $booking['arrival_date'],
                $booking['departure_date'],
                'Booked',
                true,
                ['booked'],
            );

            // Add departure date as a separate single-day event to ensure it's marked as booked
            $calendar->addEvent(
                $booking['departure_date'],
                $booking['departure_date'],
                'Booked',
                true,
                ['booked'],
            );
        }
    }

?>

    <?php if (!isset($_POST['room-type'])): ?>
        <div id="calendar-container">
        <?php endif; ?>
        <?php echo $calendar->draw(date('2026-01-01')); ?>
        <?php if (!isset($_POST['room-type'])): ?>
        </div>
    <?php endif; ?>

<?php
} catch (Exception $e) { ?>
    <p>Error displaying calendar: <?= htmlspecialchars($e->getMessage()) ?></p>
<?php } ?>