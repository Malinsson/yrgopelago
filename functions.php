<?php

declare(strict_types=1);
require_once __DIR__ . '/features.php';

function displayFeaturesCheckboxes(): void
{
    global $featureGrid;

    foreach ($featureGrid as $category => $features) { ?>
        <fieldset>
            <legend> <?= htmlspecialchars(ucfirst($category)) ?> features:</legend>

            <?php foreach ($features as $tier => $feature) {
                $checkboxId = htmlspecialchars($category . '-' . $tier);
                $checkboxName = 'features[' . htmlspecialchars($category) . '][]';
                $checkboxValue = htmlspecialchars($tier); ?>

                <div>
                    <input type="checkbox" id="<?= $checkboxId ?>" name="<?= $checkboxName ?>" value="<?= $checkboxValue ?>">
                    <label for="<?= $checkboxId ?>"><?= htmlspecialchars(ucfirst($feature)) ?></label>
                </div>
            <?php } ?>

        </fieldset>
<?php }
}
