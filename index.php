<?php
require_once __DIR__ . '/views/header.php';
?>


<div class="hero-container">
    <div class="overlay">
        <h1 class="hero-title">Welcome to Mos'Le'Comfortable</h1>
        <p class="hero-subtitle">Your Mos'Le'Safe getaway in the heart of Yrgopelago</p>
    </div>
    <img src="./images/hero.jpg" alt="Hero Image" class="hero-image">
</div>

<main>

    <section class="about-section card" id="about-section">
        <div class="about-wrapper">
            <h2 class="card-header">About Mos'Le'Comfortable</h2>
            <p>Located in the stunning Yrgopelago, Mos'Le'Comfortable offers a unique balance between comfort and adventure. Our accommodations are designed to provide a Mos'Le'safe haven from the local wildlife while ensuring you have an unforgettable experience. Whether you're here for relaxation or exploration, our friendly staff and top-notch facilities are here to make your stay exceptional.</p>
            <p>Us here at Mos'Le'Comfortable hotel will ensure that all visitor will recieve a Mos'Le'safe experience. You may experience mild discomfort due to local wildlife or within the hotel itself by staff or amenities but rest assured that this is a key part of the experiencehere at Mos'Le'Comfortable.</p>
        </div>
        <div class="about-img-container">
            <img src="./images/about-image.jpg" alt="About Us Image" class="about-image">
        </div>
    </section>

    <section class="offer-section card" id="offer-section">
        <div class="offer-card">
            <h2 class="card-header">Returning guest discount!</h2>
            <p>Enjoy a special discount when you return to Mos'Le'Harmless. We appreciate our loyal guests and want to make your next stay even more enjoyable.</p>
            <p>Just use the same name and API-KEY to automatically receive your discount at booking!</p>
            <b>Returning guest discount of <?= $returningGuestDiscount ?>$ on bookings of <?= $minimumBookingForDiscount ?>$ or more!</b>
        </div>

        <div class="v-line-black"></div>

        <div class="feature-card">
            <h2 class="card-header">Book exclusive features!</h2>
            <p>Enhance your stay with our exclusive add-ons and personalized services designed to make your experience truly memorable. Add as many features as you like to customize your visit.</p>
            <b>Features aren't exclusive to our staying guests; day visitors can also enjoy them!</b>
        </div>
    </section>

    <section class="room-showcase" id="room-showcase">
        <div class="room-card budget card">

            <div class="room-info-wrapper">
                <h2 class="card-header">Budget Room</h2>
                <p class="card-description">Cozy and affordable room for budget-conscious travelers where you can stay Mos'Le'painlessly.</p>
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

        <div class="room-card premium card">
            <div class="room-info-wrapper">
                <h2 class="card-header">Premium Room</h2>
                <p class="card-description">Spacious and comfortable room for travelers seeking a bit more luxury during their stay at Mos'Le'Comfortable.</p>
                <ul class="room-features-list">
                    <li>Free Wi-Fi</li>
                    <li>All-inclusive meals</li>
                    <li>Full protection from wildlife</li>
                    <li>Sea front view guaranteed</li>
                </ul>
                <small class="disclaimer-text">*All-inclusive meals do not include alchohol. By booking this room we disclaim any liability for dissatisfaction.</small>
                <p class="room-price"><?= getRoomPrice($database, 2)  ?>$ per night</p>
                <button class="to-booking premium" id="book-2">Book Now</button>
            </div>
            <div class="room-img-container">
                <img src="./images/room2.jpg" alt="Premium Room" class="room-image">
            </div>
        </div>

        <div class="room-card luxury card">
            <div class="room-info-wrapper">
                <h2 class="card-header">Luxury Room</h2>
                <p class="card-description">Our most luxurious room for travelers seeking the ultimate comfort and experience at Mos'Le'Comfortable.</p>
                <ul class="room-features-list">
                    <li>Free Wi-Fi</li>
                    <li>All inclusive meals and alchohol</li>
                    <li>Roomservice avaliable 24/7</li>
                    <li>Sea front view and balcony</li>
                </ul>
                <small class="disclaimer-text">This room may be too comfortable for some guests looking for a true Mos'Le'Comfortable experience.</small>
                <p class="room-price"><?= getRoomPrice($database, 3)  ?>$ per night</p>
                <button class="to-booking luxury" id="book-3">Book Now</button>

            </div>
            <div class="room-img-container">
                <img src="./images/room3.jpg" alt="Luxury Room" class="room-image">
            </div>
        </div>
    </section>

    <section class="booking-section card" id="booking-section">
        <h2 class="card-header">Book Your Stay</h2>
        <p>Use the form below to book your stay at Mos'Le'Harmless. Select your room type, arrival and departure dates, and any additional features you'd like to include in your booking.</p>
        <p>Use the caledar to select an arrival date. Dates marked as "Booked" are unavailable for selection.</p>
        <div class="booking-wrapper">
            <?php
            require_once __DIR__ . '/booking-form.php';
            require_once __DIR__ . '/app/calendar/calendar.php';
            ?>
        </div>
    </section>
</main>

<?php
require_once __DIR__ . '/views/footer.php';
?>