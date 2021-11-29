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
    $collection = new MongoCollection($dbDelete, 'orderFK');  //we have to check in child table

    if(!strpos($collection, $coll) && $collection->findOne(array('_id' => $idVal)))  {
        //we can't delete because of FK
        $result = 0;
        echo 'Cannot delete because of FK constraint!';
    } else {
        //check if the required id to be deleted exists in the table
        $m = new MongoClient();
        $db = $m->selectDB($db);
        $collection = new MongoCollection($db, $coll);
        $itemToDelete = $collection->findOne(array('_id' => $id));

        if(!empty($itemToDelete)) {
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
    
            //check if value is empty in FK collection - if so, delete the record
            $bulkDeleteEmpty = new MongoDB\Driver\BulkWrite;
    
            $myArrayCheckEmpty = [];
            $collectionEmpty = new MongoCollection($dbDelete, $coll.'FK');
    
            if($collectionEmpty->findOne(array('value' => ''))) {
                $myArrayCheckEmpty[] = $collectionEmpty->findOne(array('value' => ''));
                if($myArrayCheckEmpty[0]['value']) {
                    //it means the value is not empty yet
                } else {
                    $idToDelete = $myArrayCheckEmpty[0]['_id'];
    
                    $bulkDeleteEmpty->delete(array('value' => ''));
    
                    $resultEmpty = $manager->executeBulkWrite($db.'.'.$coll.'FK', $bulkDeleteEmpty);
                }
            }
    
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

            echo 'success';
        } else {
            echo 'this id does not exist';
        }

    }
    
} else {
    echo 'No such database!';
}