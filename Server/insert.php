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

               $value = ''.$_POST["col_name"][$i].'#'.$_POST["col_name"][$i+1];  //the rest of the columns values concatenated
               // $document1 = [$key => $value];
               $document2 = ['_id' => $intKey, 'value' => $value];
               // $document3 = ['_id' => new MongoDB\BSON\ObjectId, 'title' => 'three'];

               // $_id1 = $bulk->insert($document1);
               $_id2 = $bulk->insert($document2);
               // $_id3 = $bulk->insert($document3);

               // var_dump($_id1, $_id2, $_id3);
               var_dump($_id2);

               $manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

               $db = $_GET['dbname'];  //aici numele bazei de date tot din FE
               $coll = $_GET['tablename'];  //numele colectiei tot din FE
               
               $result = $manager->executeBulkWrite($db.'.'.$coll, $bulk);

               //UNIQUE index - it works but maybe we should do it with documents...
               // $uniqueKeys = $_GET['uniqueIndex'];

               // foreach ($uniqueKeys as $uniqueKey) {
               //      # code...
               //      $commandUniq = new MongoDB\Driver\Command([
               //           "createIndexes" => $coll,
               //           "indexes"       => [[
               //           "name" => "customUniqueIndex",
               //           "key"  => [ 'FirstName' => 1 ],
               //           "ns"   => $db.'.'.$coll,
               //           "unique" => true
               //           ]],
               //      ]);
               //      $result1 = $manager->executeCommand($db, $commandUniq);
               // }

               //Collection for Unique Index
               $bulk1 = new MongoDB\Driver\BulkWrite;
               $key1 = $_POST["col_name"][1];  //Always: first column is id

               $value1 = $_POST["col_name"][0];
               $intValue1 = intval($value1);

               $document3 = ['_id' => $key1, 'value' => $intValue1];

               $_id3 = $bulk1->insert($document3);

               $db1 = $_GET['dbname'];  //aici numele bazei de date tot din FE
               $coll1 = $_GET['tablename'].'UniqueIndex';  //numele colectiei tot din FE

               $result2 = $manager->executeBulkWrite($db1.'.'.$coll1, $bulk1);


               //NON-UNIQUE index - it works but maybe we should do it with documents...

               // $commandNonUniq = new MongoDB\Driver\Command([
               //      "createIndexes" => $coll,
               //      "indexes"       => [[
               //      "name" => "customNonUniqueIndex",
               //      "key"  => [ 'LastName' => 1 ],  //LastName
               //      "ns"   => $db.'.'.$coll,
               //      ]],
               // ]);
               // $result3 = $manager->executeCommand($db, $commandNonUniq);


               //Collection for Non Unique Index
               $bulk2 = new MongoDB\Driver\BulkWrite;
               $key2 = $_POST["col_name"][2];  

               $value2 = $_POST["col_name"][0];
               $intValue2 = intval($value2);

               $document4 = ['_id' => $key2, 'value' => $intValue2];

               $_id4 = $bulk2->insert($document4);

               $coll2 = $_GET['tablename'].'NonUniqueIndex';  //numele colectiei tot din FE

               $result4 = $manager->executeBulkWrite($db.'.'.$coll2, $bulk2);
               
          }  
     }  
     echo "Data Inserted";  
}  
else  
{  
     echo "Please Enter Name";  
}
