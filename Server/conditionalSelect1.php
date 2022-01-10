
<?php

require '../vendor/autoload.php';

$db = 'db4';
$coll = 'customer';

$condition1Field = 'custFirstName';
$condition1 = 'Vasile';
$condition2Field = 'year';
$condition2 = '32';

$conn = new MongoClient();

$m = new MongoClient();
$db1 = $m->selectDB($db);
$collection = new MongoCollection($db1, $coll);

$myVar = "8";

// $cursor = $collection->find(array('_id' => array('$gt' => intval($myVar)) ));
$cursor = $collection->find();
$result = [];
$split = null;
$result1 = [];
$i = 0;
$finalRes = [];
$attrNames = ['custID', 'custFirstName', 'custLastName', 'year'];
foreach ( $cursor as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $result[$i] = $value;

    $split = explode("#", $value['value']);
    // on the first position is id
    array_unshift($split, $result[$i]['_id']);
    for($j = 0; $j < count($attrNames); $j++) {
        // array_push($result1, array($attrNames[$j] => $split[$j])); 
        $result1[$i][$attrNames[$j]] = $split[$j];
        // $result1[$j] = array($attrNames[$j] => $split[$j]);
    }
    $i++;

}
// print_r($result1[1]);

$ok1 = 0;
$ok2 = 0;
$y = 0;
for ($i=0; $i < count($result1); $i++) { 
    if(isset($result1[$i][$condition1Field])  && $result1[$i][$condition1Field] == $condition1 && isset($result1[$i][$condition2Field]) && $result1[$i][$condition2Field] == $condition2) {
        $finalRes = (object)[$y => $result1[$i]];
        $ok1 = 1;
    }
}
if($ok1 == 1 ) {
    echo json_encode($finalRes);
} 