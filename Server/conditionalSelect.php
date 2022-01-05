<?php

require '../vendor/autoload.php';

$db = $_POST['db'];
$coll = $_POST['coll'];
// $db = 'db4';
// $coll = 'orderFK';

$selectOperator = $_POST['selectOperator'];
$selConditionField = $_POST['selConditionField'];
$selConditionOperator = $_POST['selConditionOperator'];
$selConditionFieldSecondary = $_POST['selConditionFieldSecondary'];

// $selectOperator = '*';
// $selConditionField = '_id';
// $selConditionOperator = '=';
// $selConditionFieldSecondary = '2';


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
$isIndexColl = 0;
$attrNames = [];
if(str_contains($coll, 'Index') !== false || str_contains($coll, 'FK') !== false) {
    $attrNames[0] = '_id';
    $attrNames[1] = 'value';
    $isIndexColl = 1;
} else {
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
}

$connection = new MongoClient();

$m = new MongoClient();
$db1 = $m->selectDB($db);
$collection = new MongoCollection($db1, $coll);

// verify if the condition value contains a number (id) or a string
$isId = 0;

if (preg_match('~[0-9]+~', $selConditionFieldSecondary)) {
    // we are searching by a numeric id
    $isId = 1;
} elseif (preg_match('~[A-Z]+~', $selConditionFieldSecondary) && $selConditionField == '_id') {
    // we are searching by a string in id
    $isId = 2;
}

if($isId == 1 && $isIndexColl == 0) {
    $cursor = $collection->find(array('_id' => intval($selConditionFieldSecondary)));
} elseif ($isId == 1 && $isIndexColl == 1) {
    $cursor = $collection->find(array('_id' => (string)$selConditionFieldSecondary));
} elseif ($isId == 2) {
    $cursor = $collection->find(array('_id' => $selConditionFieldSecondary));
} elseif ($isId == 0) {
    $cursor = $collection->find();
}


$before = microtime(true);
$collection1 = $connection->$db->$coll;
$after = microtime(true);
$execution_mills = $after - $before;


$result = [];
$split = null;
$result1 = [];
$i = 0;
foreach ( $cursor as $id => $value )
{
    // echo "$id: ";
    // var_dump( $value );
    $result[$i] = $value;
    if($isIndexColl == 0) {
        $split = explode("#", $value['value']);
        // on the first position is id
        array_unshift($split, $result[$i]['_id']);
        for($j = 0; $j < count($attrNames); $j++) {
            array_push($result1, (object)[$attrNames[$j] => $split[$j]]); 
        }
        $i++;
    } else {
        array_push($result1, (object)[$attrNames[0] => $result[$i]['_id']]); 
        array_push($result1, (object)[$attrNames[1] => $value['value']]); 
        $i++;
    }
}

// echo count($attrNames);   // split pe attrNames dupa index
$newArr = [];
$newArr = array_chunk($result1, count($attrNames));

// if user wants only specific attr
$found = [];
foreach($newArr as $el => $val) {
    for($x = 0; $x < count($attrNames); $x++) {
        if(property_exists($val[$x], $selConditionField)){
            if($val[$x]->$selConditionField == $selConditionFieldSecondary) {
                $found = $val;
            }
        }
    }

}
// var_dump($found);

// last position is empty because id does not contain # so we delete the last record
// array_pop($split);

if(isset($selectOperator) && !empty($selectOperator)) {
    if($selectOperator == '*') {
        if($isIndexColl == 0) {
            $found[count($found)] = $execution_mills;
            $found[count($found)] = count($attrNames);
            
            echo json_encode($found);
        } else {
            $result1[count($result1)] = $execution_mills;
            $result1[count($result1)] = count($attrNames);
    
            echo json_encode($result1);
        }
    } else {
        // user selected specific field
        $found1 = [];
        $result1 = [];
        if($isId != 0) {
            foreach($found as $prop => $val) {
                if(isset($val->$selectOperator)) {
                    $found1[0] = $val;
                }
            }
            $found1[count($found1)] = $execution_mills;
            $found1[count($found1)] = count($attrNames);
            
            echo json_encode($found1);
        } else {
            $result1[count($result1)] = $execution_mills;
            $result1[count($result1)] = count($attrNames);
    
            echo json_encode($result1);
        }
        
        
    }
} else {
    if($isId == 0) {
        $found[count($found)] = $execution_mills;
        $found[count($found)] = count($attrNames);
        
        echo json_encode($found);
    } else {
        $result1[count($result1)] = $execution_mills;
        $result1[count($result1)] = count($attrNames);

        echo json_encode($result1);
    }
}

 

?>