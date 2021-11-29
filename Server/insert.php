<?php

require '../vendor/autoload.php';

//check how many columns we have
$number = count($_POST["col_name"]);  
if($number > 0)  
{  
     for($i=1; $i<$number; $i++)  
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
               //if we have an ref table set => we have to create
               if(isset($_GET['refTable'])) {
                    $refTable = $_GET['refTable'];
                    $m1 = new MongoClient();
                    $dbRefTable = $m1->selectDB($db);
                    $collectionRefTable = new MongoCollection($dbRefTable, $refTable);
                    // $itemRefTable = $collectionRefTable->find(array('_id' => $_POST["col_name"][2]));
                    
                    if(!empty($collectionRefTable->findOne(array('_id' => intval($_POST["col_name"][2]))))) {
                         $itemRefTable = $collectionRefTable->findOne(array('_id' => intval($_POST["col_name"][2])));
                         if(!empty($itemRefTable)) {

                              $bulkFK = new MongoDB\Driver\BulkWrite;
                              $bulkCheckFK = new MongoDB\Driver\BulkWrite;
                              //we also have to check for duplicate ids in tablename
                              $secondCheckDuplicateColl = $_GET['tablename'];
                              $mSecondCheck = new MongoClient();
                              $dbSecondCheck = $mSecondCheck->selectDB($db);
                              $collSecondCheck = new MongoCollection($dbSecondCheck, $secondCheckDuplicateColl);

                              $itemSecondCheck = $collSecondCheck->findOne(array('_id' => intval($_POST["col_name"][0])));

                              if(isset($_GET['foreignKey']) && empty($itemSecondCheck)) {
                                   $foreignKey = $_GET['foreignKey'];
                                   $collFK = $_GET['tablename'].'FK';
                                   
                                   $mFK = new MongoClient();
                                   $dbFK = $mFK->selectDB($db);
                                   $collectionFK = new MongoCollection($dbFK, $collFK);
                                   $itemCheckFK = $collectionFK->find(array('_id' => $_POST["col_name"][2]));
                    
                                   $currentValFK = $collectionFK->findOne(array('_id' => $_POST["col_name"][2]), array('value'));
                                   
                                   if(!empty($itemCheckFK->count())) {
                                        $idCheckFK = $bulkCheckFK->update(['_id' => $_POST["col_name"][2]], ['$set' => ['value' => $currentValFK['value'].$_POST["col_name"][0].'#']]);
                    
                                        $resultCheckFK = $manager->executeBulkWrite($dbFK.'.'.$collFK, $bulkCheckFK);
                                   } else {
                                        $docFK = ['_id' => $_POST["col_name"][2], 'value' => $_POST["col_name"][0].'#'];
          
                                        $idFK = $bulkFK->insert($docFK);
          
                                        $resultFK = $manager->executeBulkWrite($db.'.'.$collFK, $bulkFK);
                                   }
                                   $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);
                              }  else {
                                   echo 'Error! Duplicate key error! Try inserting something else!';
                              }  

                              //check if in xml we have unique keys tag, if so create unique collection
                              $xmldoc = new DomDocument();
                              $xml = file_get_contents( '../Catalog.xml');
                              $xmldoc->loadXML( $xml, LIBXML_NOBLANKS );
                              $xmldoc->Load('../Catalog.xml');
                              $xpath = new DOMXPath($xmldoc);
                              
                              $validateUniqueKeyXML =  $xpath->query('/Databases/DataBase[@dataBaseName=\''.$db.'\']/Tables/Table[@tableName=\''.$coll.'\']/uniqueKeys')->count();
                              if ($validateUniqueKeyXML) {
                                   //if there is uniqueKey tag in XML we insert in mogno unique and non unique index collection
                                   $resultUniqueInd = $manager->executeBulkWrite($db.'.'.$collUniqueInd, $bulkUniqueInd);

                                   //Collection for Non Unique Index
                                   $bulkNonUniq = new MongoDB\Driver\BulkWrite;
                                   $keyNonUniq = $_POST["col_name"][2];  //age
                    
                                   $valueNonUniq = $_POST["col_name"][0];  //id
                    
                                   $collNonUniq = $_GET['tablename'].'NonUniqueIndex';
                    
                                   $m = new MongoClient();
                                   $dbNonUniq = $m->selectDB($db);
                                   $collection = new MongoCollection($dbNonUniq, $collNonUniq);
                                   $item = $collection->find(array('_id' => $keyNonUniq));
                    
                                   //we look in non unique collection to see if we already have the id 
                                   $currentVal = $collection->findOne(array('_id' => $keyNonUniq), array('value'));
                                   
                                   if(!empty($item->count())) {
                                        //if the id already exists we just update the value field
                                        $bulkNonUniq = new MongoDB\Driver\BulkWrite;

                                        $idNonUniq = $bulkNonUniq->update(['_id' => $keyNonUniq], ['$set' => ['value' => $currentVal['value'].$valueNonUniq.'#']]);
                    
                                        $resultNonUnique = $manager->executeBulkWrite($db.'.'.$collNonUniq, $bulkNonUniq);
                                   } else {
                                        //we create a brand new record
                                        $bulkNonUniq = new MongoDB\Driver\BulkWrite;

                                        $documentNonUniq = ['_id' => $keyNonUniq, 'value' => $valueNonUniq.'#'];
                    
                                        $idNonUniq = $bulkNonUniq->insert($documentNonUniq);
                    
                                        $resultNonUniq = $manager->executeBulkWrite($db.'.'.$collNonUniq, $bulkNonUniq);
                                   }
                                   $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);  
                              } 
                              else {
                                   //we only create the table collection in mongo
                                   echo "success";  
                                   $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);
                              } 
                         } 
                    } else {
                         //no such id in parent table
                         echo 'No such id in parent table!';
                    }
                    if($result) {
                         echo 'success';
                    } else {
                         echo 'There was a problem. Please try again!';
                    }
               } else {
                    //if the is no unique key we insert the data
                    if(!empty($resultUniqueInd = $manager->executeBulkWrite($db.'.'.$collUniqueInd, $bulkUniqueInd))) {

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
                         echo 'Error! Duplicate unique key!';
                    } 
                    if($result) {
                         echo 'success';
                    } else {
                         echo 'There was a problem. Please try again!';
                    }
               }                           
          }  else {
               echo 'Please insert value in the field';
          }
     }   
}
