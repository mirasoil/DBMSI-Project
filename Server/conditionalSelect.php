<?php

require '../vendor/autoload.php';

$db = $_POST['db'];
$coll = $_POST['coll'];
// $db = 'db5';
// $coll = 'order';

$selectOperator = $_POST['selectOperator'];
$selConditionField = $_POST['selConditionField'];
$selConditionOperator = $_POST['selConditionOperator'];
$selConditionFieldSecondary = $_POST['selConditionFieldSecondary'];

// $selectOperator = 'orderName';
// $selConditionField = 'orderAddress';
// $selConditionOperator = '=';
// $selConditionFieldSecondary = 'Cluj';

if (isset($_POST["selANDConditionField"]) && is_array($_POST["selANDConditionField"])) {
    $selANDConditionField = $_POST['selANDConditionField'];
    $selANDConditionOperator = $_POST['selANDConditionOperator'];
    $selANDConditionFieldSecondary = $_POST['selANDConditionFieldSecondary'];
}

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
$j = 0;
$isIndexColl = 0;
$attrNames = [];

$uniqueKey = '';
$nonUniqueKey = '';
$foreignKey = '';

if(str_contains($coll, 'Index') !== false || str_contains($coll, 'FK') !== false) {
    $attrNames[0] = '_id';
    $attrNames[1] = 'value';
} else {
    foreach($tags as $tag) {
        $dbName = $tag->getAttribute("dataBaseName");
        if($dbName == $db){
            // if any database with the same name is found, we should check if it has tables with same name
            $tableTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/Structure/Attribute');
            foreach($tableTags as $tableTag) {
                // for that table we store all the attributes in an array
                $attrNames[$i] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/Structure/Attribute[\''.$i.'\']/@attributeName')->item($i)->value;
                $i++;
            }
            // we shall check if the collection has unique or non-unique index and if this one is equal to our attribute
            if ($xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/uniqueKeys/UniqueAttribute')->count()) {
                $checkForUniqueKey = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/uniqueKeys/UniqueAttribute')->item(0)->textContent;
                if($checkForUniqueKey == $selConditionField) {
                    $uniqueKey = $checkForUniqueKey;
                }
            }
    
            if ($xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/nonUniqueKeys/NonUniqueAttribute')->count()) {
                $checkForNonUniqueKey = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/nonUniqueKeys/NonUniqueAttribute')->item(0)->textContent; 
                if($checkForNonUniqueKey == $selConditionField) {
                    $nonUniqueKey = $checkForNonUniqueKey;
                }
            }
            
            // check for foreign keys
            $dbTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']');
            foreach($dbTags as $dbTag) {
                // for that table we store all the attributes in an array
                if ($xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/foreignKeys/foreignKey/fkAttribute')->count()) {
                    $checkForFK = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$coll.'\']/foreignKeys/foreignKey/fkAttribute')->item(0)->textContent;
                }
            }
            if(!empty($checkForFK) && $checkForFK == $selConditionField) {
                $foreignKey = $checkForFK;
            }
    
            
        }  
    }
}


// if unique or non unique keys exist we then search in the right collections in mongo
if(!empty($uniqueKey) && str_contains($coll, 'Unique') === false) {
    // search in the Unique collection
    $newColl = $coll.'UniqueIndex';
} elseif (!empty($nonUniqueKey) && str_contains($coll, 'NonUnique') === false) {
    // search in the Non-Unique collection
    $newColl = $coll.'NonUniqueIndex';
} elseif (!empty($foreignKey) && str_contains($coll, 'FK') === false) {
    // search in the FK collection
    $newColl = $coll.'FK';
} else {
    // it means no index was found
    $newColl = $coll;
}

$connection = new MongoClient();

$m = new MongoClient();
$db1 = $m->selectDB($db);
$collection = new MongoCollection($db1, $newColl);


// verify if the condition value contains a number (id) or a string
$idType = '';
if (preg_match('/[A-Za-z]/', $selConditionFieldSecondary) && preg_match('/[0-9]/', $selConditionFieldSecondary)){
    $idType = 'string';
} elseif (preg_match('~[0-9]+~', $selConditionFieldSecondary) && str_contains($selConditionField, 'ID') !== false) {
    // we are searching by a numeric id
    $idType = 'int';
} elseif (preg_match('~[A-Z]+~', $selConditionFieldSecondary) && $selConditionField == '_id') {
    // we are searching by a string in id
    $idType = 'string';
} elseif (preg_match('~[A-Z]+~', $selConditionFieldSecondary) && $selConditionField != '_id' && str_contains($newColl, 'Unique') === false) {
    // we are searching by something else than the id
    $idType = 'not-an-id';
} elseif (preg_match('~[A-Z]+~', $selConditionFieldSecondary) && str_contains($newColl, 'Unique') !== false) {
    // we are searching by a string in an unique or non-unique coll
    $idType = 'string';
} elseif (preg_match('~[0-9]+~', $selConditionFieldSecondary) && str_contains($selConditionField, 'age')) {
    // searching by age - no unique or non unique index, age is int
    $idType = 'string';
}


if($idType == 'int' && str_contains($newColl, 'FK') === false) {
    if($selConditionOperator == '=') {
        $cursor = $collection->find(array('_id' => intval($selConditionFieldSecondary)));
    } elseif($selConditionOperator == '>=') {
        $cursor = $collection->find(array('_id' => array('$gte' => intval($selConditionFieldSecondary)) ));
    } elseif($selConditionOperator == '<=') {
        $cursor = $collection->find(array('_id' => array('$lte' => intval($selConditionFieldSecondary)) ));
    } elseif($selConditionOperator == '>') {
        $cursor = $collection->find(array('_id' => array('$gt' => intval($selConditionFieldSecondary)) ));
    } elseif($selConditionOperator == '<') {
        $cursor = $collection->find(array('_id' => array('$lt' => intval($selConditionFieldSecondary)) ));
    }
} elseif ($idType == 'int' && str_contains($newColl, 'FK')) {
    // in FK collection _id field is a number as string 
    $cursor = $collection->find(array('_id' => (string)$selConditionFieldSecondary));
} elseif ($idType == 'string' && $selConditionField !== 'age') {
    $cursor = $collection->find(array('_id' => $selConditionFieldSecondary));
} elseif ($idType == 'string' && $selConditionField === 'age') {
    $cursor = $collection->find();
} elseif ($idType == '' || $idType == 'not-an-id') {
    $cursor = $collection->find();
}

$before = microtime(true);
$collection1 = $connection->$db->$newColl;
$after = microtime(true);
$execution_mills = $after - $before;


$result = [];
$split = null;
$result1 = [];
$arrOfIndexes = [];
$data = [];
$i = 0;
$info = '';
$newIndexes = [];

foreach ($cursor as $id => $value) {
    $result[$i] = $value;

    // if collection is FK we need all the values for that id to look for them in the other collection
    if(str_contains($newColl, 'FK') !== false) {
        $split = explode("#", $value['value']);
        // first position is id
        array_unshift($split, $result[$i]['_id']);

        for($j = 1; $j < count($split)-1; $j++) {
            $arrOfIndexes[$j-1] = $split[$j]; 
        }
        $i++;
        $info = 'found-in-FK';
    } elseif (str_contains($newColl, 'NonUnique') !== false) {
        if(preg_match('~[0-9]+~', $value['value']) && str_contains($value['value'], '#') === false) {
            // if the value from the non unique collection is only one id
            $arrOfIndexes[0] = $result[0]['value'];
        } else {
            $split = explode("#", $value['value']);
            // first position is id
            array_unshift($split, $result[$i]['_id']);
    
            for($j = 1; $j < count($split)-1; $j++) {
                $arrOfIndexes[$j-1] = $split[$j]; 
            }
        }
        $i++;
        $info = 'found-in-NonUnique';
    } elseif (str_contains($newColl, 'UniqueIndex') !== false && str_contains($newColl, 'NonUnique') === false) {
        // if collection names contains unique but not NonUnique
        $arrOfIndexes[0] = $result[$i]['_id']; 
        $arrOfIndexes[1] = $value['value']; 
        $i++;
        $info = 'found-in-Unique';
    } elseif ($selConditionField == "orderAddress") {
        // we need the third value from the value field
        $obj = (object)[];
        $split = explode("#", $value['value']);
        // first position is id
        array_unshift($split, $result[$i]['_id']);

        for($j = 1; $j < count($split)-1; $j++) {
            if ($split[$j] == $selConditionFieldSecondary) {
                array_push($arrOfIndexes, (object)['id' => $split[$j-3]]);
                array_push($arrOfIndexes, (object)['orderName' => $split[$j-2]]);
                array_push($arrOfIndexes, (object)['custID' => $split[$j-1]]);
                array_push($arrOfIndexes, (object)['orderAddress' => $split[$j]]);
            }
        }

        $i++;
        $info = 'is-orderAddress';
    } elseif($selConditionField == "age") {
        // we need the forth value from the value field
        $obj = (object)[];
        $split = explode("#", $value['value']);
        // first position is id
        array_unshift($split, $result[$i]['_id']);

        $ind = 0;
        for($j = 1; $j < count($split)-1; $j++) {
            if ($split[$j] == $selConditionFieldSecondary) {
                array_push($arrOfIndexes, (object)['custID' => $split[$j-3]]);
                array_push($arrOfIndexes, (object)['custFirstName' => $split[$j-2]]);
                array_push($arrOfIndexes, (object)['custLastName' => $split[$j-1]]);
                array_push($arrOfIndexes, (object)['age' => $split[$j]]);
            }
        }

        $i++;
        $info = 'is-age';
    } elseif ($db == 'db5NoIndex') {
        $split = explode("#", $value['value']);
        // first position is id
        array_unshift($split, $id);

        for($t = 0; $t < count($split)-1; $t++) {

            if($split[$t] == $selConditionFieldSecondary) {
                // store the indexes for those whose last name is the one we want
                array_push($newIndexes, $split[$t-2]);
            }
        }

        $i++;
        $info = 'found-in-db5NoIndex';
    } else {
        // for conditions using <=, >=, <, >
        $changedArr = [];
        $m = 0;
        $index = 0;
        if (isset($_POST["selANDConditionField"]) && is_array($_POST["selANDConditionField"])) {
            $split = explode("#", $value['value']);
            // first position is id
            array_unshift($split, $result[$i]['_id']);
    
            for($j = 0; $j < count($split)-1; $j++) {
                array_push($data, (object)[$attrNames[$j] => $split[$j]]);
            }
            $i++;
            $info = 'found-elsewhere';
            
            for($n = 0; $n < count($data); $n++) {
                foreach(array_values($data)[$n] as $key => $value) {
                    for($r = 0; $r < count($selANDConditionField); $r++) {
                        if($key == $selANDConditionField[$r] && $value == $selANDConditionFieldSecondary[$r]){
                            for($t =0; $t < count($attrNames); $t++){
                                if($attrNames[$t] == $selANDConditionField[$r]) {
                                    $index = $t;
                                }
                            }
                            if($index == 0) {
                                $m = $n;
                            } elseif($index == 1) {
                                $m = $n - 1;
                            } elseif($index == 2) {
                                $m = $n - 2;
                            } elseif($index == 3) {
                                $m = $n - 3;
                            }
                        }
                    }
                }
            }

            array_push($changedArr, $data[$m]);
            array_push($changedArr, $data[$m+1]);
            array_push($changedArr, $data[$m+2]);
            array_push($changedArr, $data[$m+3]);

            $data = $changedArr;

        } else {
            $split = explode("#", $value['value']);
            // first position is id
            array_unshift($split, $result[$i]['_id']);
            for($j = 0; $j < count($split)-1; $j++) {
                array_push($data, (object)[$attrNames[$j] => $split[$j]]);
            }
            $i++;
            $info = 'found-elsewhere';

        }
    }
}

$newAttrNames = [];
$x = 0;
$resultFK = [];
$resultNonUnique = [];
$resultUniqueIndex = [];
$resultElsewhere = [];
$resultOrderAddress = [];
$resultAge = [];
$resultDB5NoIndex = [];
$responseFinalData = [];

if($info == 'found-in-FK') {
    if(str_contains($newColl, 'FK') === true) {
        // get the referenced table from xml
        $newColl = str_replace('FK', '', $newColl);
        foreach($tags as $tag) {
            $dbName = $tag->getAttribute("dataBaseName");
            if($dbName == $db){

                // from the new table store all the attributes names in array
                $newTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute');
                foreach($newTags as $newTag) {
                    // for that table we store all the attributes in an array
                    $newAttrNames[$x] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute[\''.$x.'\']/@attributeName')->item($x)->value;
                    $x++;
                }
            }  
        }
    }

    // we search all the indexes in the right table and return the data
    for($i = 0; $i < count($arrOfIndexes); $i++) {
        $conn = new MongoClient();

        $m = new MongoClient();
        $db1 = $m->selectDB($db);
        $coll = new MongoCollection($db1, $newColl);

        $cursor = $coll->find(array('_id' => intval($arrOfIndexes[$i])));

        foreach ($cursor as $id => $value) {
            $result[$i] = $value;

            $split = explode("#", $value['value']);
            // first position is id
            array_push($resultFK, (object)[$newAttrNames[0] => $id]);
            array_unshift($split, $result[$i]['_id']);

            for($j = 1; $j < count($split)-1; $j++) {
                array_push($resultFK, (object)[$newAttrNames[$j] => $split[$j]]);
            }
        }
    }
    $resultFK[count($resultFK)] = $execution_mills;
    $resultFK[count($resultFK)] = count($newAttrNames);
    
    
    echo json_encode($resultFK);
} elseif($info == 'found-in-NonUnique') {
    if(str_contains($newColl, 'NonUniqueIndex') === true) {
        // get the referenced table from xml
        $newColl = str_replace('NonUniqueIndex', '', $newColl);
        foreach($tags as $tag) {
            $dbName = $tag->getAttribute("dataBaseName");
            if($dbName == $db){
                // from the new table store all the attributes names in array
                $newTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute');
                foreach($newTags as $newTag) {
                    // for that table we store all the attributes in an array
                    $newAttrNames[$x] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute[\''.$x.'\']/@attributeName')->item($x)->value;
                    $x++;
                }
            }  
        }
    }

    // we search all the indexes in the right table and return the data
    if(count($arrOfIndexes) == 1) {
        $conn = new MongoClient();
    
        $m = new MongoClient();
        $db1 = $m->selectDB($db);
        $coll = new MongoCollection($db1, $newColl);

        $cursor = $coll->find(array('_id' => intval($arrOfIndexes[0])));

        foreach ($cursor as $id => $value) {
            $result[$i] = $value;

            $split = explode("#", $value['value']);
            // first position is id
            array_push($resultNonUnique, (object)[$newAttrNames[0] => $id]);
            array_unshift($split, $result[$i]['_id']);

            for($j = 1; $j < count($newAttrNames); $j++) {
                array_push($resultNonUnique, (object)[$newAttrNames[$j] => $split[$j]]);
            }
        }
    } else {
        for($i = 0; $i < count($arrOfIndexes); $i++) {
            $conn = new MongoClient();
    
            $m = new MongoClient();
            $db1 = $m->selectDB($db);
            $coll = new MongoCollection($db1, $newColl);
    
            $cursor = $coll->find(array('_id' => intval($arrOfIndexes[$i])));
    
            foreach ($cursor as $id => $value) {
                // var_dump($value);
                $result[$i] = $value;
    
                $split = explode("#", $value['value']);
                // first position is id
                array_push($resultNonUnique, (object)[$newAttrNames[0] => $id]);
                array_unshift($split, $result[$i]['_id']);
    
                for($j = 1; $j < count($newAttrNames); $j++) {
                    array_push($resultNonUnique, (object)[$newAttrNames[$j] => $split[$j]]);
                }
            }
        }
    }
    $resultNonUnique[count($resultNonUnique)] = $execution_mills;
    $resultNonUnique[count($resultNonUnique)] = count($newAttrNames);
    
    
    echo json_encode($resultNonUnique);
} elseif($info == 'found-in-Unique') {
    if(str_contains($newColl, 'UniqueIndex') === true) {
        // get the referenced table from xml
        $newColl = str_replace('UniqueIndex', '', $newColl);
        foreach($tags as $tag) {
            $dbName = $tag->getAttribute("dataBaseName");
            if($dbName == $db){
                // from the new table store all the attributes names in array
                $newTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute');
                foreach($newTags as $newTag) {
                    // for that table we store all the attributes in an array
                    $newAttrNames[$x] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table[@tableName=\''.$newColl.'\']/Structure/Attribute[\''.$x.'\']/@attributeName')->item($x)->value;
                    $x++;
                }
            }  
        }
    }
    // we search all the indexes in the right table and return the data
    $conn = new MongoClient();

    $m = new MongoClient();
    $db1 = $m->selectDB($db);
    $coll = new MongoCollection($db1, $newColl);

    $cursor = $coll->find(array('_id' => intval($arrOfIndexes[1])));

    foreach ($cursor as $id => $value) {
        // echo "$id: ";
        // var_dump( $value );
        $result = $value;

        $split = explode("#", $value['value']);
        // first position is id
        array_unshift($resultUniqueIndex, (object)[$newAttrNames[0] => $arrOfIndexes[1]]);
        array_unshift($split, $arrOfIndexes[1]);

        for($j = 1; $j < count($split)-1; $j++) {
            array_push($resultUniqueIndex, (object)[$newAttrNames[$j] => $split[$j]]);
        }
    }
    $resultUniqueIndex[count($resultUniqueIndex)] = $execution_mills;
    $resultUniqueIndex[count($resultUniqueIndex)] = count($newAttrNames);
    
    
    echo json_encode($resultUniqueIndex);

}  elseif($info == 'found-elsewhere') {
    // we search all the indexes in the right table and return the data
    $resultElsewhere = $data;
    $resultElsewhere[count($resultElsewhere)] = $execution_mills;
    $resultElsewhere[count($resultElsewhere)] = count($attrNames);
    
    
    echo json_encode($resultElsewhere);

} elseif ($info == 'is-orderAddress') {
    $resultOrderAddress = $arrOfIndexes;
    $resultOrderAddress[count($resultOrderAddress)] = $execution_mills;
    $resultOrderAddress[count($resultOrderAddress)] = count($attrNames);
    echo json_encode($resultOrderAddress);
} elseif ($info == 'is-age') {
    $resultAge = $arrOfIndexes;

    // we have to kep only the corresponding data
    $resultAge[count($resultAge)] = $execution_mills;
    $resultAge[count($resultAge)] = count($attrNames);

    echo json_encode($resultAge);
} elseif ($info == 'found-in-db5NoIndex') {
    $newAttrNames = ["custID", "custFirstName", "custLastName", "age"];
    
    for($s = 0; $s < count($newIndexes); $s++) {
        $m5NoIndex = new MongoClient();
        $db5NoIndex = $m5NoIndex->selectDB($db);
        $coll5NoIndex = new MongoCollection($db5NoIndex, $newColl);

        $cursor5NoIndex = $coll5NoIndex->find(array('_id' => intval($newIndexes[$s])));

        foreach ($cursor5NoIndex as $id => $value) {
            $result[$i] = $value;

            $split = explode("#", $value['value']);
            // first position is id
            array_unshift($split, $result[$i]['_id']);

            for($j = 0; $j < count($split)-1; $j++) {
                array_push($responseFinalData, (object)[$newAttrNames[$j] => $split[$j]]);

            }
        }

    }

    // we have to kep only the corresponding data
    $responseFinalData[count($responseFinalData)] = $execution_mills;
    $responseFinalData[count($responseFinalData)] = count($attrNames);

    echo json_encode($responseFinalData);
}



