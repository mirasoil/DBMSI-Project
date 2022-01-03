<?php

require '../vendor/autoload.php';

$m = new MongoClient();

$db = $_POST['db'];
$coll = $_POST['coll1'];

$collJoin = $_POST['joinTable'];

$joinType = $_POST['joinType'];
$joinField = $_POST['joinField'];

// $db = $m->selectDB($dbName);
// $collections = $db->getCollectionNames();

// echo json_encode($collections);

$before = microtime(true);
$collection = $m->$db->$coll;
$after = microtime(true);
$execution_mills = $after - $before;

$beforeJoin = microtime(true);
$collectionJoin = $m->$db->$collJoin;
$afterJoin = microtime(true);
$execution_millsJoin = $afterJoin - $beforeJoin;

$cursor = $collection->find();
$result = [];
array_push($result, (object)['collection' => $coll]); 
$i = 1;
foreach ( $cursor as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $result[$i] = $value;
    $i++;
}
// $result[$i] = $execution_mills;

$cursorJoin = $collectionJoin->find();
$resultJoin = [];
array_push($resultJoin, (object)['collectionJoin' => $collJoin]); 
$j = 1;
foreach ( $cursorJoin as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $resultJoin[$j] = $value;
    $j++;
}
// $resultJoin[$j] = $execution_millsJoin;

$finalResult = array_merge($result, $resultJoin);
$finalResult[count($finalResult)-1] = $execution_mills;
$finalResult[count($finalResult)-1] = $execution_millsJoin;

echo json_encode($finalResult);

?>