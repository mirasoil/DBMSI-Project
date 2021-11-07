<?php

require '../vendor/autoload.php';


// $db = 'dbmsiproject';  //aici numele bazei de date tot din FE
// $coll = 'test';  //numele colectiei tot din FE

if( isset($_POST['dropTableBtn']))
 {
    $dbname = $_POST['deleteTableDBname'];
    $tblname = $_POST['deleteTableName'];
    $idVal = $_POST['deleteColumnName'];
    $id = intval($idVal);

     $doc = new DOMDocument;
     $doc->load('../Catalog.xml');

     $node = $doc->documentElement;
     $tags = $node->getElementsByTagName('DataBase');
     $tags1 = $node->getElementsByTagName('Table');
     $tags2 = $node->getElementsByTagName('Tables');

     foreach($tags as $tag)
     {
        $databaseName = $tag->getAttribute("dataBaseName");
        if($databaseName == $dbname){
            $db=$databaseName;
            echo $db;
            break;
        }
        
    }
    if($db !== null){

    foreach($tags1 as $tag1)
    {
        $tabelName = $tag1->getAttribute("tableName");
        //echo $tabelName.'<br />';
        if($tabelName == $tblname && $databaseName == $db) {
        
            $con = (new \MongoDB\Client("mongodb://localhost:27017"))->dbname ;
            $collection = $con->tblname;
            $cursor = $collection->find(array('_id'=> $id));
    
            if($cursor)
           { 
            // $con = new MongoDB\Client("mongodb://localhost:27017");
            //$db = $con->$dbname;
            // $delRec = $collection->deleteOne(
            // [['_id' => $id ], ['limit' => 1]]
            // );
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->delete(['_id' => $id], ['limit' => 1]);
            $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
            $result =$manager->executeBulkWrite($dbname.'.'.$tblname, $bulk);
            
            header('Location: ../Client/deleteRecords.php?result=success');
            exit;

           } else{
            header('Location: ../Client/deleteRecords.php?result=faildId');
            exit;
        }

        } else {
            header('Location: ../Client/deleteRecords.php?result=faildTable');
            exit;
        }
    }} else {
        header('Location: ../Client/deleteRecords.php?result=faildData');
        exit;
    }
}
