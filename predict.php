<?php

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\ModelManager;
use Phpml\Classification\KNearestNeighbors;

$modelManager = new ModelManager();
$classifier = $modelManager->restoreFromFile('temperature_model.model');

if (isset($_GET['temperature'])) {
    $temperature = (float)$_GET['temperature'];
    $prediction = $classifier->predict([$temperature]);
    echo "Prediction: $prediction";
} else {
    echo "Please provide a temperature value.";
}

?>
