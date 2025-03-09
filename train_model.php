<?php

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\KNearestNeighbors;
use Phpml\ModelManager;

$file = 'tempData.xml';
if (file_exists($file)) {
    $xml = simplexml_load_file($file);
} else {
    die("Sensor data file not found.");
}

$samples = [];
$labels = [];
$normal_count = 0;
$abnormal_count = 0;

foreach ($xml->record as $record) {
    $temperature = (float)$record->temperature;
    $samples[] = [$temperature];
    

    if ($temperature >= 48 && $temperature <= 52) {
        $labels[] = 'normal';
        $normal_count++;
    } else {
        $labels[] = 'abnormal';
        $abnormal_count++;
    }


    if ($normal_count >= 50 && $abnormal_count >= 50) {
        break;
    }
}

if ($normal_count < 50 || $abnormal_count < 50) {
    die("Not enough data to train the model. Please collect more sensor data.");
}


$classifier = new KNearestNeighbors();
$classifier->train($samples, $labels);


$modelManager = new ModelManager();
$modelManager->saveToFile($classifier, 'temperature_model.model');

echo "Model trained and saved successfully.";

?>
