<?php

declare(strict_types=1);
require_once __DIR__ . '/features.php';

function displayFeaturesCheckboxes(array $featureGrid): void
{
    foreach ($featureGrid as $index => $feature) {
        $category = toUppercase($feature['category']);
        $tier = toUppercase($feature['tier']);
        $featureName = toUppercase($feature['feature']); ?>
        <label for="<?= $featureName ?>">Tier: <?= $tier ?></label>
        <input type="checkbox" name=" <?= $featureName ?>" value="<?= $featureName ?>"><?= $featureName ?></input>
<?php }
}

function clean(string $data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
};

function toUppercase(string $data): string
{
    return ucfirst($data);
}
