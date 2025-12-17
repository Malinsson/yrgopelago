<?php

declare(strict_types=1);

require_once __DIR__ . '/app/database/database.php';
require_once __DIR__ . '/functions.php';

$featureGrid = searchAllFeatures($database);

function displayFeaturesCheckboxes(array $featureGrid): void
{
    foreach ($featureGrid as $index => $feature) {
        $category = toUppercase($feature['category']);
        $tier = toUppercase($feature['tier']);
        $featureName = toUppercase($feature['feature']);
        $price = $feature['base_price'] ?>
        <input type="checkbox" name="features[]" value="<?= $featureName ?>"></input>
        <label for="<?= $featureName ?>"> <?= $featureName ?> Cost: <?= $price ?>$</label>
<?php }
}
