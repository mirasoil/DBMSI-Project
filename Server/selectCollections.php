<?php

require '../vendor/autoload.php';

$m = new MongoClient();
// $dbName = $_POST['db'];
$dbName = 'db4';
$db = $m->selectDB($dbName);
$collections = $db->getCollectionNames();

$cleanedCollections = [];
$contor = 0;
$i = 0;
foreach ($collections as $key => $value) {
    if((str_contains($value, 'Index') !== false || str_contains($value, 'FK') !== false)) {
        $contor = 1;
    } else {
        $cleanedCollections[$i] = $value;
        $i++;
    }
}

echo json_encode($cleanedCollections);

?>