<?php

require '../vendor/autoload.php';

$conn = new MongoClient("mongodb://localhost");
$dbases = $conn->listDBs();
echo json_encode($dbases);

?>