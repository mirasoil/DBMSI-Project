<?php

require '../vendor/autoload.php';

$m = new MongoClient();
$dbName = $_POST['db'];
$db = $m->selectDB($dbName);
$collections = $db->getCollectionNames();

echo json_encode($collections);

?>