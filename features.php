<?php

declare(strict_types=1);

require_once __DIR__ . '/app/database/database.php';

$featureGrid = $database->query('SELECT * FROM features');
$featureGrid = $featureGrid->fetchAll(PDO::FETCH_ASSOC);
