<?php

require '../vendor/autoload.php';

//Retrieve column names from xml
$currentDB = $_POST['currentDB'];
$tableName = $_POST['tableName'];

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
    

    $checkdb = null;
    $checktable = null;

    foreach($tags as $tag)
    {
        $dbName = $tag->getAttribute("dataBaseName");
        if($dbName == $currentDB){
            $checkdb = $dbName;
            //if any database with the same name is found, we should check if it has tables with same name
            $tableTags = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$dbName.'\']/Tables/Table');
            foreach($tableTags as $tableTag) {
                $tableNameCheck = $tableTag->getAttribute("tableName");

                if($tableNameCheck == $tableName) {
                    $checktable = $tableNameCheck;
                    break;
                }
            }
        }  
    }

    if($checkdb == null) {  //it means no database with that name was found
        echo json_encode('noSuchDB');
    } else {
        if($checktable == null) {
            // header('Location: ../Client/insertRecords.php?result=failedTable');
            // exit;
            echo json_encode('noSuchTable');
        } else {
            $tables = $xmldoc->getElementsByTagName('Table');
            // var_dump($tables[0]);
            foreach($tables as $table) {
                if($table->getAttribute('tableName') == $tableName) {
                    $columns =  $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/Structure/Attribute');

                    // $attrName =  $columns[0]->getAttribute('attributeName');
                    $allColumns = [];
                    // var_dump($columns);
                    // echo $attrName;
                    foreach ($columns as $column) {
                        
                        $colName = $column->getAttribute('attributeName');
                        $colType = $column->getAttribute('type');
        
                        // array_push($allColumns, $colName, $colType);
                        $allColumns[$colName] = $colType;
 
                    }

                    
                    
                }
            }
            //if any unique index is set, let the user know that
            if($xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/uniqueKeys/UniqueAttribute/text()')->count()) {
                $length = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/uniqueKeys/UniqueAttribute/text()')->count();
                $i = 0;
                $j = 1;
                while($i<$length){
                    $allColumns['uniqueKey'.$i] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/uniqueKeys['.$j.']/UniqueAttribute/text()')->item(0)->textContent;
                    $i++;
                    $j++;
                }
            }
            //if any foreign key is set, let the user know that
            if($xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/foreignKeys/foreignKey/fkAttribute/text()')->count()) {
                $length1 = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/foreignKeys/foreignKey/fkAttribute/text()')->count();
                $i1 = 0;
                $j1 = 1;
                while($i1<$length1){
                    $allColumns['foreignKey'.$i1] = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$tableName.'\']/foreignKeys/foreignKey/fkAttribute/text()')->item(0)->textContent;
                    $i1++;
                    $j1++;
                }
            }
            echo json_encode($allColumns);
        }
    }

