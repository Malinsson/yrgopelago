<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../database/database.php';

use benhall14\phpCalendar\Calendar as Calendar;

try {
    $calendar = new Calendar();
    $calendar->useMondayStartingDate();

    $bookedDates = searchBookedDates($database);

    foreach ($bookedDates as $booking) {
        if ($booking['room_id'] === 1) {

            $calendar->addEvent(
                $booking['arrival_date'],   # start date in either Y-m-d or Y-m-d H:i if you want to add a time.
                $booking['departure_date'],   # end date in either Y-m-d or Y-m-d H:i if you want to add a time.
                'Booked',  # event name text
                true,           # should the date be masked - boolean default true
                ['booked'],   # (optional) additional classes in either string or array format to be included on the event days
                ['booked']
            );
        }
    }

?>

    <div id="calendar-container">
        <?php echo $calendar->draw(date('2026-01-01')); ?>
    </div>

<?php
} catch (Exception $e) { ?>
    <p>Error displaying calendar: <?= htmlspecialchars($e->getMessage()) ?></p>
<?php } ?>