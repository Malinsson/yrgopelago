<?php

declare(strict_types=1);

require_once __DIR__ . '/app/database/database.php';
require_once __DIR__ . '/functions.php';

$featureGrid = searchAllFeatures($database);

function displayFeaturesCheckboxes(array $featureGrid): void
{
    foreach ($featureGrid as $index => $feature) {
        $featureId = $feature['id'];
        $category = toUppercase($feature['category']);
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
    foreach ($featureGrid as $feature) {
        if (in_array($feature['feature'], $selectedFeatures, true)) {
            $totalFeaturesPrice += $feature['base_price'];
        }
    }
    return $totalFeaturesPrice;
}
