<?php

require '../vendor/autoload.php';

$bulk = new MongoDB\Driver\BulkWrite;

               $key = '77';  //Always: first column is id
               $intKey = intval($key);

               $value = 'Ion#76';  //the rest of the columns values concatenated
               // $document1 = [$key => $value];
               $document2 = ['_id' => $intKey, 'value' => $value];
               // $document3 = ['_id' => new MongoDB\BSON\ObjectId, 'title' => 'three'];

               // $_id1 = $bulk->insert($document1);
               $_id2 = $bulk->insert($document2);
               // $_id3 = $bulk->insert($document3);

               // var_dump($_id1, $_id2, $_id3);
               // var_dump($_id2);

               $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

               //Unique index
               $db = 'db3';  //aici numele bazei de date tot din FE
               $coll = 'artists';  //numele colectiei tot din FE
               
               $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);

               //Collection for Unique Index
               $bulk1 = new MongoDB\Driver\BulkWrite;
               $key1 = 'Ion';  //Always: first column is id

               //First check if there already exists this id in unique index file
               $coll1 = 'artistsUniqueIndex';  //numele colectiei tot din FE

               $value1 = '77';

               $m = new MongoClient();
               $dbUI = $m->selectDB($db);
               $collection = new MongoCollection($dbUI, $coll1);
               $collection1 = new MongoCollection($dbUI, $coll);
               $item = $collection->find(array('_id' => $key1));
               echo 'Am ajus aici<br>';
               //concatenate id values for all the unique fields
               $idUnique = $collection1->findOne(array('value' => array('$regex' => $key1)), array('_id'));
               print_r($idUnique);
               $currentVal = $collection->findOne(array('_id' => $key1), array('value'));
               var_dump($currentVal);
               // $uniqueIdStringConcat = '';
               // if(!empty($idUnique)){
               //      $uniqueIdStringConcat = $idUnique['_id'].'#';
               //      var_dump($uniqueIdStringConcat);
               // }
               
               if(!empty($item)) {
                    $_idUniqIndex = $bulk1->update(['_id' => $key1], ['$set' => ['value' => $currentVal['value'].'#'.$value1]]);
                    // $_idUniqIndex = $bulk1->aggregate(['$addFields' => ['value' => ['$concat' => ['value' => '#'.$value1]]]]);

                    $resultUniqueIndex = $manager->executeBulkWrite($db.'.'.$coll1, $bulk1);
               } else {
                    $document3 = ['_id' => $key1, 'value' => $value1];

                    $_id3 = $bulk1->insert($document3);

                    $result2 = $manager->executeBulkWrite($db.'.'.$coll1, $bulk1);
               }