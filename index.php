<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/views/header.php';
require_once __DIR__ . '/app/database/database.php';
?>

<main>

    <div class="hero-container">
        <div class="overlay">
            <h1 class="hero-title">Welcome to Mos'Le'Harmless</h1>
            <p class="hero-subtitle">Your Mos'Le'Safe getaway in the heart of Yrgopelago</p>
        </div>
        <img src="./images/hero.jpg" alt="Hero Image" class="hero-image">
    </div>

    <section class="room-showcase" id="room-showcase">
        <div class="room-card budget">

            <div class="room-info-wrapper">
                <h2 class="card-header">Budget Room</h2>
                <p class="card-description">Cozy and affordable room for budget-conscious travelers.</p>
                <ul class="room-features-list">
                    <li>Free Wi-Fi</li>
                    <li>Complimentary breakfast</li>
                    <li>Basic protection from wildlife</li>
                    <li>Our patented sea view window technology&reg;</li>
                </ul>
                <small class="disclaimer-text">*Sea view window technology may not be effective in all weather conditions. By booking this room we disclaim any liability for dissatisfaction.</small>
                <p class="room-price"><?= getRoomPrice($database, 1)  ?>$ per night</p>
                <button class="to-booking budget" id="book-1">Book Now</button>
            </div>
            <div class="room-img-container">
                <img src="./images/room1.jpg" alt="Budget Room" class="room-image">
            </div>
        </div>

        <div class="room-card premium">
            <div class="room-info-wrapper">
                <h2 class="card-header">Premium Room</h2>
                <p class="card-description">Cozy and affordable room for budget-conscious travelers.</p>
                <ul class="room-features-list">
                    <li>Free Wi-Fi</li>
                    <li>All-inclusive meals</li>
                    <li>Full protection from wildlife</li>
                    <li>Sea front view guaranteed</li>
                </ul>
                <p class="room-price"><?= getRoomPrice($database, 2)  ?>$ per night</p>
                <button class="to-booking premium" id="book-2">Book Now</button>
            </div>
            <div class="room-img-container">
                <img src="./images/room2.jpg" alt="Premium Room" class="room-image">
            </div>
        </div>

        <div class="room-card luxury">
            <div class="room-info-wrapper">
                <h2 class="card-header">Luxury Room</h2>
                <p class="card-description">Cozy and affordable room for budget-conscious travelers.</p>
                <ul class="room-features-list">
                    <li>Free Wi-Fi</li>
                    <li>All inclusive meals and alchohol</li>
                    <li>Roomservice avaliable 24/7</li>
                    <li>Sea front view and balcony</li>
                </ul>
                <p class="room-price"><?= getRoomPrice($database, 3)  ?>$ per night</p>
                <button class="to-booking luxury" id="book-3">Book Now</button>

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