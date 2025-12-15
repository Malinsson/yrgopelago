<?php

require __DIR__ . '/vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

try {
    $calendar = new Calendar();
    $calendar->useMondayStartingDate();
    echo $calendar->draw(date('2026-01-01'));;
} catch (Exception $e) {
    echo '<p>Error displaying calendar: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
