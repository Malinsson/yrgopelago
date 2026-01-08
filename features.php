<?php

declare(strict_types=1);

require_once __DIR__ . '/app/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$envApiKey = $_ENV['API_KEY'] ?? $_ENV['api_key'] ?? null;

function getAllFeaturesFromApi($envApiKey): array
{

    $client = new \GuzzleHttp\Client();

    $response = $client->request(
        'POST',
        'https://www.yrgopelag.se/centralbank/islandFeatures',
        [
            'form_params' => [
                'user' => 'Malin',
                'api_key' => $envApiKey,
            ],
        ]
    );

    $response = $response->getBody()->getContents();
    $response = json_decode($response, true);
    return $response['features'];
}

$featureGrid = searchAllFeatures($database);

function insertNewFeaturesIntoDatabase(PDO $database, string $activity, string $tier, string $feature, int $basePrice): void
{
    $insertFeature = $database->prepare("INSERT INTO features (activity, tier, feature, base_price) VALUES (:activity, :tier, :feature, :base_price)");
    $insertFeature->bindParam(':activity', $activity);
    $insertFeature->bindParam(':tier', $tier);
    $insertFeature->bindParam(':feature', $feature);
    $insertFeature->bindParam(':base_price', $basePrice);
    $insertFeature->execute();
}


function displayFeaturesCheckboxes(array $featureGrid): void
{
    $featuresByTier = [];
    foreach ($featureGrid as $feature) {
        $tier = $feature['tier'];
        if (!isset($featuresByTier[$tier])) {
            $featuresByTier[$tier] = [];
        }
        $featuresByTier[$tier][] = $feature;
    }

    foreach ($featuresByTier as $tier => $features) {
        $tierLabel = toUppercase($tier);
?>
        <fieldset>
            <legend><?= $tierLabel ?></legend>
            <?php foreach ($features as $feature) {
                $featureId = $feature['id'];
                $activity = toUppercase($feature['activity']);
                $featureName = toUppercase($feature['feature']);
                $originalFeatureName = $feature['feature']; // Keep original for price lookup
                $price = $feature['base_price'];
            ?>
                <input type="checkbox" id="feature-<?= $featureId ?>" name="features[]" value="<?= $originalFeatureName ?>">
                <label for="feature-<?= $featureId ?>"><?= $featureName ?> - <?= $activity ?> - $<?= $price ?></label>
                <br>
            <?php } ?>
        </fieldset>
<?php
    }
}

function getFeaturePriceTotal(array $selectedFeatures, array $featureGrid): int
{
    $totalFeaturesPrice = 0;
    foreach ($selectedFeatures as $selectedFeature) {
        foreach ($featureGrid as $feature) {
            if ($feature['feature'] === $selectedFeature) {
                $totalFeaturesPrice += (int)$feature['base_price'];
            }
        }
    }
    return $totalFeaturesPrice;
}

function updateFeaturePrices(PDO $database, int $economyPrice, int $basicPrice, int $premiumPrice, int $superiorPrice): void
{
    $updatePrices = $database->prepare("UPDATE features SET base_price = CASE tier 
        WHEN 'economy' THEN :economy 
        WHEN 'basic' THEN :basic 
        WHEN 'premium' THEN :premium
        WHEN 'superior' THEN :superior
        END");
    $updatePrices->bindParam(':economy', $economyPrice, PDO::PARAM_INT);
    $updatePrices->bindParam(':basic', $basicPrice, PDO::PARAM_INT);
    $updatePrices->bindParam(':premium', $premiumPrice, PDO::PARAM_INT);
    $updatePrices->bindParam(':superior', $superiorPrice, PDO::PARAM_INT);
    $updatePrices->execute();
}

function convertFeaturesToReceiptFormat(array $selectedFeatures, array $featureGrid): array
{
    $featuresForReceipt = [];
    foreach ($selectedFeatures as $selectedFeature) {
        foreach ($featureGrid as $feature) {
            if ($feature['feature'] === $selectedFeature) {
                $featuresForReceipt[] = [
                    'activity' => $feature['activity'],
                    'tier' => $feature['tier'],
                ];
            }
        }
    }
    return $featuresForReceipt;
}

function getFeatureIdByName(PDO $database, string $featureName): ?int
{
    $getFeatureId = $database->prepare("SELECT id FROM features WHERE feature = :feature");
    $getFeatureId->bindParam(':feature', $featureName);
    $getFeatureId->execute();
    $featureData = $getFeatureId->fetch(PDO::FETCH_ASSOC);
    return $featureData ? (int)$featureData['id'] : null;
}

function getFeaturePriceByName(PDO $database, string $featureName): ?int
{
    $getFeaturePrice = $database->prepare("SELECT base_price FROM features WHERE feature = :feature");
    $getFeaturePrice->bindParam(':feature', $featureName);
    $getFeaturePrice->execute();
    $featureData = $getFeaturePrice->fetch(PDO::FETCH_ASSOC);
    return $featureData ? (int)$featureData['base_price'] : null;
}

function getFeaturePriceByTier(PDO $database, string $tier): ?int
{
    $getFeaturePrice = $database->prepare("SELECT base_price FROM features WHERE tier = :tier LIMIT 1");
    $getFeaturePrice->bindParam(':tier', $tier);
    $getFeaturePrice->execute();
    $featureData = $getFeaturePrice->fetch(PDO::FETCH_ASSOC);
    return $featureData ? (int)$featureData['base_price'] : null;
}
