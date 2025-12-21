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

            function clearHighlights() {
                dayCells.forEach(c => {
                    c.classList.remove('calendar-selected');
                    c.classList.remove('calendar-departure');
                });
            }

            function highlightFromInputs(arrivalStr, departureStr) {
                clearHighlights();

                const parseYMD = (str) => {
                    if (!str) return null;
                    const parts = str.split('-').map(Number);
                    if (parts.length !== 3) return null;
                    return {
                        y: parts[0],
                        m: parts[1],
                        d: parts[2]
                    };
                };

                const arrival = parseYMD(arrivalStr);
                const departure = parseYMD(departureStr);

                if (arrival && arrival.y === 2026 && arrival.m === 1) {
                    dayCells.forEach(c => {
                        const cellDay = parseInt(c.textContent.trim(), 10);
                        if (!isNaN(cellDay) && cellDay === arrival.d) {
                            c.classList.add('calendar-selected');
                        }
                    });
                }

                if (departure && departure.y === 2026 && departure.m === 1) {
                    dayCells.forEach(c => {
                        const cellDay = parseInt(c.textContent.trim(), 10);
                        if (!isNaN(cellDay) && cellDay === departure.d) {
                            c.classList.add('calendar-departure');
                        }
                    });
                }
            }

            dayCells.forEach(cell => {
                const dayText = cell.textContent.trim();
                if (dayText && !isNaN(dayText) && parseInt(dayText) > 0 && parseInt(dayText) <= 31) {
                    cell.style.cursor = 'pointer';
                    cell.addEventListener('click', function(e) {
                        clearHighlights();

                        this.classList.add('calendar-selected');

                        const year = 2026;
                        const month = 1;
                        const day = parseInt(dayText);

                        const formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                        arrivalInput.value = formattedDate;
                        arrivalInput.dispatchEvent(new Event('change'));

                        const nextDay = new Date(year, month - 1, day + 1);
                        const nextDayDay = nextDay.getDate();
                        const nextDayStr = `${nextDay.getFullYear()}-${String(nextDay.getMonth() + 1).padStart(2, '0')}-${String(nextDayDay).padStart(2, '0')}`;
                        departureInput.min = nextDayStr;
                        departureInput.value = nextDayStr;

                        highlightFromInputs(arrivalInput.value, departureInput.value);
                    });
                }
            });

            // Sync highlights when arrival is changed via the form (not just calendar clicks)
            arrivalInput.addEventListener('change', function() {
                highlightFromInputs(arrivalInput.value, departureInput.value);
            });
            departureInput.addEventListener('change', function() {
                highlightFromInputs(arrivalInput.value, departureInput.value);
            });

            // Initial highlight if inputs already have values
            highlightFromInputs(arrivalInput.value, departureInput.value);
        });
    </script>
<?php
} catch (Exception $e) { ?>
    <p>Error displaying calendar: <?= htmlspecialchars($e->getMessage()) ?></p>
<?php } ?>