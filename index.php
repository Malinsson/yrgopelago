<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/views/header.php';
?>

<main>

    <div class="hero-container">
        <img src="./images/hero.jpg" alt="Hero Image" class="hero-image">
    </div>

    <section class="room-showcase" id="room-showcase">
        <div class="room-card budget">

            <div class="room-info-wrapper">
                <h2>Budget Room</h2>
                <p>Cozy and affordable room for budget-conscious travelers.</p>
            </div>
            <div class="room-img-container">
                <img src="./images/room1.jpg" alt="Budget Room" class="room-image">
            </div>
        </div>

        <div class="room-card premium">
            <div class="room-info-wrapper">
                <h2>Premium Room</h2>
                <p>Cozy and affordable room for budget-conscious travelers.</p>
            </div>
            <div class="room-img-container">
                <img src="./images/room2.jpg" alt="Premium Room" class="room-image">
            </div>
        </div>

        <div class="room-card luxury">
            <div class="room-info-wrapper">
                <h2>Luxury Room</h2>
                <p>Cozy and affordable room for budget-conscious travelers.</p>
            </div>
            <div class="room-img-container">
                <img src="./images/room3.jpg" alt="Luxury Room" class="room-image">
            </div>
        </div>
    </section>

    <section class="booking-section" id="booking-section">
        <?php
        require_once __DIR__ . '/booking-form.php';
        require_once __DIR__ . '/app/calendar/calendar.php';
        ?>
    </section>
</main>

<?php
require_once __DIR__ . '/views/footer.php';
?>