<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/app/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->safeLoad();

$envApiKey = $_ENV['API_KEY'] ?? $_ENV['api_key'] ?? null;

$currestEconomyPrice = getFeaturePriceByTier($database, 'economy');
$currestBasicPrice = getFeaturePriceByTier($database, 'basic');
$currestPremiumPrice = getFeaturePriceByTier($database, 'premium');
$currestSuperiorPrice = getFeaturePriceByTier($database, 'superior');

$errors = [];
$successMessages = [];

// Admin authentication check
if (!isset($_SESSION['adminLoggedIn']) || $_SESSION['adminLoggedIn'] !== true) {
    header('Location: index.php');
    exit();
}


if (isset($_POST['getFeatures'])) {
    $featuresFromApi = getAllFeaturesFromApi($envApiKey);

    if ($featuresFromApi !== [] || $featuresFromApi !== null) {
        foreach ($featuresFromApi as $feature) {
            if (in_array($feature['feature'], array_column($featureGrid, 'feature'))) {
                continue;
            }
            $activity = $feature['activity'];
            $tier = $feature['tier'];
            $featureName = $feature['feature'];
            $basePrice = 1; // Default price

            insertNewFeaturesIntoDatabase($database, $activity, $tier, $featureName, $basePrice);
        }
        $successMessages[] = 'Features have been successfully fetched from the API and inserted into the database.';
    } else {
        $errors[] = 'Error: Unable to fetch features from the API. Please check your API key and try again.';
    }
}

if (isset($_POST['changePrices'])) {
    $economyPrice = isset($_POST['economy-price']) ? (int)$_POST['economy-price'] : $currestEconomyPrice;
    $basicPrice = isset($_POST['basic-price']) ? (int)$_POST['basic-price'] : $currestBasicPrice;
    $premiumPrice = isset($_POST['premium-price']) ? (int)$_POST['premium-price'] : $currestPremiumPrice;
    $superiorPrice = isset($_POST['superior-price']) ? (int)$_POST['superior-price'] : $currestSuperiorPrice;

    //Safety check to ensure prices are greater than 0
    if ($economyPrice > 0 && $basicPrice > 0 && $premiumPrice > 0 && $superiorPrice > 0) {
        updateFeaturePrices($database, $economyPrice, $basicPrice, $premiumPrice, $superiorPrice);
        $successMessages[] = 'Feature prices have been successfully updated.';
    } else {
        $errors[] = 'Error: All prices must be greater than 0.';
    }
}

if (isset($_POST['changeRoomPrices'])) {
    $budgetPrice = isset($_POST['budget-price']) ? (int)$_POST['budget-price'] : getRoomPrice($database, 1);
    $standardPrice = isset($_POST['standard-price']) ? (int)$_POST['standard-price'] : getRoomPrice($database, 2);
    $luxuryPrice = isset($_POST['luxury-price']) ? (int)$_POST['luxury-price'] : getRoomPrice($database, 3);

    //Safety check to ensure prices are greater than 0
    if ($budgetPrice > 0 && $standardPrice > 0 && $luxuryPrice > 0) {
        changeRoomPrices($database, $budgetPrice, $standardPrice, $luxuryPrice);
        $successMessages[] = 'Room prices have been successfully updated.';
    } else {
        $errors[] = 'Error: All room prices must be greater than 0.';
    }
}

require_once __DIR__ . '/views/header.php';

?>



<body>
    <main>
        <h2>Admin Dashboard</h2>
        <form method="post" action="">
            <p>Fetch latest features from external API and insert into database</p>
            <button type="submit" name="getFeatures" value="1">Get Features from API</button>
        </form>

        <p>Change price of features</p>
        <form method="post" action="">
            <label for="economy-price">Economy</label>
            <input type="number" id="economy-price" name="economy-price" placeholder="<?= $currestEconomyPrice ?>" min="1" required>
            <label for="basic-price">Basic</label>
            <input type="number" id="basic-price" name="basic-price" placeholder="<?= $currestBasicPrice ?>" min="1" required>
            <label for="premium-price">Premium</label>
            <input type="number" id="premium-price" name="premium-price" placeholder="<?= $currestPremiumPrice ?>" min="1" required>
            <label for="superior-price">Superior</label>
            <input type="number" id="superior-price" name="superior-price" placeholder="<?= $currestSuperiorPrice ?>" min="1" required>
            <button type="submit" name="changePrices" value="1">Change Prices</button>
        </form>

        <p>Change price of rooms</p>
        <form method="post" action="">
            <label for="budget-price">Budget</label>
            <input type="number" id="budget-price" name="budget-price" placeholder="<?= getRoomPrice($database, 1) ?>" min="1" required>
            <label for="standard-price">Standard</label>
            <input type="number" id="standard-price" name="standard-price" placeholder="<?= getRoomPrice($database, 2) ?>" min="1" required>
            <label for="luxury-price">Luxury</label>
            <input type="number" id="luxury-price" name="luxury-price" placeholder="<?= getRoomPrice($database, 3) ?>" min="1" required>
            <button type="submit" name="changeRoomPrices" value="1">Change Room Prices</button>
        </form>
        <?php
        if (!empty($errors)) { ?>
            <div class="error-messages">;
                <?php
                foreach ($errors as $error) { ?>
                    <p><?= $error ?></p>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
        if (!empty($successMessages)) { ?>
            <div class="success-messages">
                <?php foreach ($successMessages as $message) { ?>
                    <p><?= $message ?></p>
                <?php } ?>
            </div>
        <?php } ?>
    </main>
    <?php
    require_once __DIR__ . '/views/footer.php';
