<?php

require '../vendor/autoload.php';

//check how many columns we have
$number = count($_POST["col_name"]);  
if($number > 0)  
{  
     for($i=1; $i<=$number; $i++)  
     {  
          if(trim($_POST["col_name"][$i] != ''))  
          {  
               // echo $_POST["col_name"][$i];
               $bulk = new MongoDB\Driver\BulkWrite;

               $key = $_POST["col_name"][0];  //Always: first column is id
               $intKey = intval($key);

               //creating the concatenated value from the rest of the fields
               $value = '';
               $j = $number;
               $index = 1;
               while($j > 0 && $index < $number) {
                    $value = $value.$_POST["col_name"][$index].'#'; 
                    $j--;
                    $index++;
               }
               
               //prepare data for insert
               $documentTable = ['_id' => $intKey, 'value' => $value];
               
               $idTable = $bulk->insert($documentTable);

               $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

               //Unique index
               $db = $_GET['dbname'];  //user's choosed db
               $coll = $_GET['tablename'];    //user's choosed collection
               


               //Collection for Unique Index
               $bulkUniqueInd = new MongoDB\Driver\BulkWrite;
               $keyUniqueInd = $_POST["col_name"][1];  //Always: first column is id

               $valueUniqueInd = $_POST["col_name"][0];   //the id

               $documentUniqueInd = ['_id' => $keyUniqueInd, 'value' => $valueUniqueInd];

               $idUniqueInd = $bulkUniqueInd->insert($documentUniqueInd);
 
               $collUniqueInd = $_GET['tablename'].'UniqueIndex'; 

               $ok = 0;
               $refTable = $_GET['refTable'];
                    $m1 = new MongoClient();
                    $dbRefTable = $m1->selectDB($db);
                    $collectionRefTable = new MongoCollection($dbRefTable, $refTable);
                    // $itemRefTable = $collectionRefTable->find(array('_id' => $_POST["col_name"][2]));
                    
                    if($collectionRefTable->findOne(array('_id' => intval($_POST["col_name"][2])))) {
                         $itemRefTable = $collectionRefTable->findOne(array('_id' => intval($_POST["col_name"][2])));
                         if(!empty($itemRefTable)) {

                              $bulkFK = new MongoDB\Driver\BulkWrite;
                              if($foreignKey = $_GET['foreignKey']) {
                                   $collFK = $_GET['tablename'].'FK';
          
                                   $docFK = ['_id' => $_POST["col_name"][2], 'value' => $_POST["col_name"][0]];
          
                                   $idFK = $bulkFK->insert($docFK);
          
                                   $resultFK = $manager->executeBulkWrite($db.'.'.$collFK, $bulkFK);
     
                                   $ok = 1;
                              }
                         } 
                    } else {
                         header('Location: ../Client/insertRecords.php?result=failedNoSuchId');
                         exit;
                    }

               //if the is no unique key we insert the data
               if($resultUniqueInd = $manager->executeBulkWrite($db.'.'.$collUniqueInd, $bulkUniqueInd) && $ok == 1) {

                    //Collection for Non Unique Index
                    $bulkNonUniq = new MongoDB\Driver\BulkWrite;
                    $keyNonUniq = $_POST["col_name"][2];  //age
     
                    $valueNonUniq = $_POST["col_name"][0];  //id
     
                    $collNonUniq = $_GET['tablename'].'NonUniqueIndex';
     
                    $m = new MongoClient();
                    $dbNonUniq = $m->selectDB($db);
                    $collection = new MongoCollection($dbNonUniq, $collNonUniq);
                    $item = $collection->find(array('_id' => $keyNonUniq));
     
                    $currentVal = $collection->findOne(array('_id' => $keyNonUniq), array('value'));
                    
                    if(!empty($item->count())) {
                         $idUniqIndex = $bulkNonUniq->update(['_id' => $keyNonUniq], ['$set' => ['value' => $currentVal['value'].'#'.$valueNonUniq]]);
     
                         $resultUniqueIndex = $manager->executeBulkWrite($db.'.'.$collNonUniq, $bulkNonUniq);
     
                         //we insert in the table also
                         $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);
                    } else {
                         $documentNonUniq = ['_id' => $keyNonUniq, 'value' => $valueNonUniq];
     
                         $_idNonUniq = $bulkNonUniq->insert($documentNonUniq);
     
                         $resultNonUniq = $manager->executeBulkWrite($db.'.'.$collNonUniq, $bulkNonUniq);
     
                         //we insert in the table also
                         $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);
                    }

                    
               } else {
                    header('Location: ../Client/insertRecords.php?result=failedInsert');
                    exit;
               }              
          }  
     }  
     echo "Data Inserted";  
}  
else  
{  
     echo "Please Enter Name";  
}
