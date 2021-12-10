<?php

require '../vendor/autoload.php';

$db = $_POST['db'];
$coll = $_POST['coll'];

$connection = new MongoClient();

$before = microtime(true);
$collection = $connection->$db->$coll;
$after = microtime(true);
$execution_mills = $after - $before;

$cursor = $collection->find();
$result = [];
$i = 0;
foreach ( $cursor as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $result[$i] = $value;
    $i++;
}
$result[$i] = $execution_mills;
echo json_encode($result);


// $before = microtime(true);
//     $collection = $connection->$db->$coll;
//     $after = microtime(true);
//     $execution_mills = $after - $before;
//     echo 'Execution time is : ' . $execution_mills;
?>