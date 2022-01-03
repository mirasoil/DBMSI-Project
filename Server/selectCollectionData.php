<?php

require '../vendor/autoload.php';

$db = $_POST['db'];
$coll = $_POST['coll'];
// $db = 'db4';
// $coll = 'order';


// we have to look inside catalog to check how many attribute tags we have
$xmldoc = new DomDocument();

//get the xml file
$xml = file_get_contents( '../Catalog.xml');
$xmldoc->loadXML( $xml, LIBXML_NOBLANKS );

//access the name of the database
$xmldoc->Load('../Catalog.xml');
$xpath = new DOMXPath($xmldoc);

//verify if the table name already exists in the database
$node = $xmldoc->documentElement;
$tags = $node->getElementsByTagName('DataBase'); //access databases


$i = 0;
$attrNames = [];

foreach($tags as $tag)
{
    $dbName = $tag->getAttribute("dataBaseName");
    if($dbName == $db){
        //if any database with the same name is found, we should check if it has tables with same name
        $tableTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/Structure/Attribute');
        foreach($tableTags as $tableTag) {
            $attrNames[$i] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/Structure/Attribute[\''.$i.'\']/@attributeName')->item($i)->value;
            $i++;
        }
    }  
}
// echo $i;
// echo '<br>';
// var_dump($attrNames);
// echo '<br>';
// var_dump($attrNames[0]);
// echo '<br>';
// var_dump($attrNames[1]);
// echo '<br>';
// echo(count($attrNames));


$connection = new MongoClient();

$before = microtime(true);
$collection = $connection->$db->$coll;
$after = microtime(true);
$execution_mills = $after - $before;

$cursor = $collection->find();
$result = [];
$split = null;
$result1 = [];
$i = 0;
foreach ( $cursor as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $result[$i] = $value;
    // if(strpos($value['value']) !== false ) {}
    $split = explode("#", $value['value']);
    // on the first position is id
    array_unshift($split, $result[$i]['_id']);
    for($j = 0; $j < count($attrNames); $j++) {
        array_push($result1, (object)[$attrNames[$j] => $split[$j]]); 
    }
    $i++;
}

// last position is empty because id does not contain # so we delete the last record
// array_pop($split);


$result1[count($result1)] = $execution_mills;
$result1[count($result1)] = count($attrNames);
echo json_encode($result1);

?>