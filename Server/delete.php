<?php

require '../vendor/autoload.php';

$table = $_POST['deleteTableName'];
$idVal = $_POST['deleteColumnName'];
$id = intval($idVal);

$db = 'dbmsiproject';  //aici numele bazei de date tot din FE
$coll = 'test';  //numele colectiei tot din FE

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete(['_id' => $id], ['limit' => 1]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);

// var_dump($result);
header('Location: ../Client/deleteRecords.php?result=success');
exit;