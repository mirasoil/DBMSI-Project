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
          }  
     }  
     echo "Data Inserted";  
}  
else  
{  
     echo "Please Enter Name";  
}


//For index if ever needed
// $command = new MongoDB\Driver\Command([
//    "createIndexes" => $coll,
//    "indexes"       => [[
//      "name" => "indexName",
//      "key"  => [ $key => 1],
//      "ns"   => $db.'.'.$coll,
//   ]],
// ]);
// $result1 = $manager->executeCommand($db, $command);
// echo '<br/>This is the index<br/>';
// var_dump($result1);