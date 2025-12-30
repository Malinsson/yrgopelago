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
