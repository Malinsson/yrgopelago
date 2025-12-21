<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

try {
    $calendar = new Calendar();
    $calendar->useMondayStartingDate(); ?>

    <div id="calendar-container">
        <?php echo $calendar->draw(date('2026-01-01')); ?>
    </div>

    <style>
        .calendar-selected {
            background-color: green !important;
            color: white !important;
        }
    </style>

    <!-- JavaScript to handle date selection via clicking on calendar days -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarContainer = document.getElementById('calendar-container');
            const arrivalInput = document.getElementById('arrival-date');
            const departureInput = document.getElementById('departure-date');

            if (!arrivalInput || !departureInput) {
                return;
            }

            const dayCells = calendarContainer.querySelectorAll('td, div[class*="day"]');

            dayCells.forEach(cell => {
                const dayText = cell.textContent.trim();
                if (dayText && !isNaN(dayText) && parseInt(dayText) > 0 && parseInt(dayText) <= 31) {
                    cell.style.cursor = 'pointer';
                    cell.addEventListener('click', function(e) {
                        dayCells.forEach(c => c.classList.remove('calendar-selected'));

                        this.classList.add('calendar-selected');

                        const year = 2026;
                        const month = 1;
                        const day = parseInt(dayText);

                        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                        arrivalInput.value = formattedDate;
                        arrivalInput.dispatchEvent(new Event('change'));

                        const nextDay = new Date(year, month - 1, day + 1);
                        const nextDayStr = `${nextDay.getFullYear()}-${String(nextDay.getMonth() + 1).padStart(2, '0')}-${String(nextDay.getDate()).padStart(2, '0')}`;
                        departureInput.min = nextDayStr;
                        departureInput.value = nextDayStr;
                    });
                }
            });
        });
    </script>
<?php
} catch (Exception $e) { ?>
    <p>Error displaying calendar: <?= htmlspecialchars($e->getMessage()) ?></p>
<?php } ?>