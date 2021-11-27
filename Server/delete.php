<?php

require '../vendor/autoload.php';

$dbname = $_GET['dbname'];
$coll = $_GET['tablename'];
$idVal = $_POST['deleteRecordID'];
$id = intval($idVal);

$doc = new DOMDocument;
$doc->load('../Catalog.xml');

$node = $doc->documentElement;
$tags = $node->getElementsByTagName('DataBase');
$tags1 = $node->getElementsByTagName('Table');
$tags2 = $node->getElementsByTagName('Tables');

foreach($tags as $tag) {
$databaseName = $tag->getAttribute("dataBaseName");
    if($databaseName == $dbname){
        $db = $databaseName;
        break;
    }
}

if($db !== null) {
    $m = new MongoClient();
    $dbDelete = $m->selectDB($db);
    $collection = new MongoCollection($dbDelete, $coll.'FK');

    if(is_null($collection->findOne(array('value' => array('$regex' => (string)$id)))) ) {
        //we can't delete because of FK
        $result = 0;
    } else {
        $bulk = new MongoDB\Driver\BulkWrite;

        $bulk->delete(['_id' => $id], ['limit' => 1]);

        $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

        $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);

        //delete from fk
        $bulkFK = new MongoDB\Driver\BulkWrite;

        $myArray = [];
        $collectionFoundId = new MongoCollection($dbDelete, $coll.'FK');
        $value = $collectionFoundId->findOne(array('value' => array('$regex' => (string)$id)));
        $myArray[] = $value;
        
        $valueToModify = (string)$myArray[0]['value'];
        $newstring = str_replace($id.'#', '', $valueToModify);

        $bulkFK->update(['value' => ['$regex' => (string)$id]], ['$set' => ['value' => $newstring]]);

        $resultFK = $manager->executeBulkWrite($db.'.'.$coll.'FK', $bulkFK);

        //delete from non unique
        $bulkNonUnique = new MongoDB\Driver\BulkWrite;

        $myArrayNonUnique = [];
        $collNonUnique = new MongoCollection($dbDelete, $coll.'NonUniqueIndex');
        $valueNonUnique = $collNonUnique->findOne(array('value' => array('$regex' => (string)$id)));
        $myArrayNonUnique[] = $valueNonUnique;
        
        $valueToModifyNonUnique = (string)$myArrayNonUnique[0]['value'];
        $newstringNonUnique = str_replace($id.'#', '', $valueToModifyNonUnique);

        $bulkNonUnique->update(['value' => ['$regex' => (string)$id]], ['$set' => ['value' => $newstringNonUnique]]);

        $resultNonUnique = $manager->executeBulkWrite($db.'.'.$coll.'NonUniqueIndex', $bulkNonUnique);

        //delete from unique
        $bulkUnique = new MongoDB\Driver\BulkWrite;

        $collUnique = new MongoCollection($dbDelete, $coll.'UniqueIndex');

        $valueUnique = $collUnique->findOne(array('value' => (string)$id));   
        
        $bulkUnique->delete(['value' => (string)$id]);

        $resultUnique = $manager->executeBulkWrite($db.'.'.$coll.'UniqueIndex', $bulkUnique);
    }
    
} else {
    header('Location: ../Client/deleteRecords.php?result=failedDB');
    exit;
}

if ($result) {
    echo 'success';
} else {
    echo 'fail';
}