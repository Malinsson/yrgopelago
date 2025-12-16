<?php

declare(strict_types=1);

if (isset($_POST['name'], $_POST['transfer-code'], $_POST['room-type'], $_POST['arrival-date'], $_POST['departure-date'])) {
    $name = htmlspecialchars($_POST['name']);
    $transferCode = htmlspecialchars($_POST['transfer-code']);
    $roomType = htmlspecialchars($_POST['room-type']);
    $arrivalDate = htmlspecialchars($_POST['arrival-date']);
    $departureDate = htmlspecialchars($_POST['departure-date']);
    $features = $_POST['features'] ?? []; ?>

    <h2>Booking Confirmation</h2>
    <p>Thank you, <?= $name ?>, for your booking!</p>
    <p>Transfer Code: <?= $transferCode ?></p>
    <p>Room Type: <?= $roomType ?></p>
    <p>Arrival Date: <?= $arrivalDate ?></p>
    <p>Departure Date: <?= $departureDate ?></p>
    <p>Selected Features:</p>
    <ul>
        <?php foreach ($features as $category => $tiers) {
            foreach ($tiers as $tier) { ?>
                <li><?= htmlspecialchars(ucfirst($category)) ?> - <?= htmlspecialchars(ucfirst($tier)) ?></li>
        <?php }
        } ?>
    <?php } else { ?>
        <p>Error: All fields are required.</p>
    <?php } ?>

    <button onclick="window.history.back();">Go Back</button>