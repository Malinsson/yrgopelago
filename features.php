<?php

declare(strict_types=1);

require_once __DIR__ . '/app/database/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/vendor/autoload.php';

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

function insertNewFeaturesIntoDatabase(PDO $database, string $category, string $tier, string $feature, int $basePrice): void
{
    $insertFeature = $database->prepare("INSERT INTO features (category, tier, feature, base_price) VALUES (:category, :tier, :feature, :base_price)");
    $insertFeature->bindParam(':category', $category);
    $insertFeature->bindParam(':tier', $tier);
    $insertFeature->bindParam(':feature', $feature);
    $insertFeature->bindParam(':base_price', $basePrice);
    $insertFeature->execute();
}


function displayFeaturesCheckboxes(array $featureGrid): void
{
    foreach ($featureGrid as $index => $feature) {
        $featureId = $feature['id'];
        $activity = toUppercase($feature['activity']);
        $tier = toUppercase($feature['tier']);
        $featureName = toUppercase($feature['feature']);
        $price = $feature['base_price'] ?>
        <input type="checkbox" name="features[]" value="<?= $featureName ?>"></input>
        <label for="<?= $featureName ?>"> <?= $featureName ?> Cost: <?= $price ?>$</label>
<?php }
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

function updateFeaturePrices(PDO $database, int $basicPrice, int $standardPrice, int $premiumPrice, int $superiorPrice): void
{
    $updatePrices = $database->prepare("UPDATE features SET base_price = CASE tier 
        WHEN 'basic' THEN :basic_price 
        WHEN 'standard' THEN :standard_price 
        WHEN 'premium' THEN :premium_price 
        WHEN 'superior' THEN :superior_price 
        END");
    $updatePrices->bindParam(':basic_price', $basicPrice);
    $updatePrices->bindParam(':standard_price', $standardPrice);
    $updatePrices->bindParam(':premium_price', $premiumPrice);
    $updatePrices->bindParam(':superior_price', $superiorPrice);
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
