<?php

require '../vendor/autoload.php';

$db = $_GET['dbname'];
$coll = $_GET['tablename'];
$idVal = $_POST['deleteRecordID'];
$id = intval($idVal);

// $db = 'dbmsiproject';  //aici numele bazei de date tot din FE
// $coll = 'Students';  //numele colectiei tot din FE

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete(['_id' => $id], ['limit' => 1]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);

// var_dump($result);
// header('Location: ../Client/deleteRecords.php?result=success');
// exit;

if ($result) {
    echo 'success';
} else {
    echo 'fail';
}