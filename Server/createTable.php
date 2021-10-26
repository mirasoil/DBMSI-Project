<?php

if( isset($_POST['btnCreateTable']))
{
    $tableName = $_POST['tableName'];
    $currentDB = $_POST['currentDB'];
    $attributeNameValue = $_POST['attributeName'];
    $isnullValue = $_POST['isnullValue'];
    $primaryKeyValue = $_POST['primaryKeyValue'];
    $foreignKeyValue = $_POST['foreignKeyValue'];
    $refTableValue = $_POST['refTableValue'];
    $refAttrValue = $_POST['refAttrValue'];
    $uAttrValue = $_POST['uAttrValue'];
    $indexTypeValue = $_POST['indexTypeValue'];
    $indexIsUniqueValue = $_POST['isUniqueValue'];
    $indexKeyLengthValue = $_POST['indexKeyLengthValue'];
    $indexNameValue = $_POST['indexNameValue'];
    $IAttributeValue = $_POST['IAttributeValue'];
    $lengthValue = $_POST['lengthInput'];
    $typeValue = $_POST['dataType'];
    
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
        header('Location: ../Client/createTable.php?result=failedDB');
        exit;
    } else {
        if($checktable == null) {
            //if no database or table with the same name were found we can insert
            $i = 0;
            while($currentDB != $xpath->query("/Databases/DataBase/@dataBaseName")->item($i)->value) {
                $i++;
            }
            // echo 'i este: '.$i;
            $dbname = $xpath->query("/Databases/DataBase/@dataBaseName")->item($i);   //we don't know how many dbs we have
            // echo "<pre>"; print_r($dbname); "</pre>";
            // echo $dbname->value;
        
            //add table to the specific database
            if($currentDB == $dbname->value){
                $database = $xpath->query("/Databases/DataBase[@dataBaseName=\"".$currentDB."\"]")[0];
                //$database = $database1->getAttribute('tableName');
                //echo "<pre>"; print_r($database); "</pre>";
        
                //check if the tables tag already exists
                $validation =  $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables');
                // echo "<pre>Validare "; print_r($validation); "</pre>";
                
                if($validation->length == 0) {
                    $tables = $xmldoc->createElement('Tables');
                    $database->appendChild($tables);
                } else {
                    $tables = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables')[0];
                    // echo "<pre>tables este "; print_r($tables); "</pre>";
                }
                $table = $xmldoc->createElement('Table');
                $tables->appendChild($table);
                $rowLength = $xmldoc->createAttribute("rowLength");
                $rowLength->value = "114";  //variable
                $table->appendChild($rowLength);
        
                $fileName = $xmldoc->createAttribute("fileName");
                $fileName->value = "fileName"; //variable
                $table->appendChild($fileName);
        
                $tblName = $xmldoc->createAttribute("tableName");
                $tblName->value = $tableName;
                $table->appendChild($tblName);
        
                $structure = $xmldoc->createElement('Structure');
                $table->appendChild($structure);
        
                $attribute = $xmldoc->createElement('Attribute');
                $structure->appendChild($attribute);
                
                $isnull = $xmldoc->createAttribute('isnull');
                $isnull->value = $isnullValue;
                $attribute->appendChild($isnull);
        
                $length = $xmldoc->createAttribute('length');
                $length->value = $lengthValue;
                $attribute->appendChild($length);
        
                $type = $xmldoc->createAttribute('type');
                $type->value = $typeValue;
                $attribute->appendChild($type);
        
                $attributeName = $xmldoc->createAttribute('attributeName');
                $attributeName->value = $attributeNameValue;
                $attribute->appendChild($attributeName);
        
                //there is no need to verify since here we only create the table ???
                $prKey = $xmldoc->createElement('primaryKey');
                $table->appendChild($prKey);
        
                $prKeyAttr = $xmldoc->createElement('pkAttribute', $primaryKeyValue);
                $prKey->appendChild($prKeyAttr);
        
                $fKeys = $xmldoc->createElement('foreignKeys');
                $table->appendChild($fKeys);
        
                $fKey = $xmldoc->createElement('foreignKey');
                $fKeys->appendChild($fKey);
        
                $fKeyAttr = $xmldoc->createElement('fkAttribute', $foreignKeyValue);
                $fKey->appendChild($fKeyAttr);
        
                //verify if the referenced table exists
                $tableNamesValidation = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$refTableValue.'\']');
                // echo "<pre>"; print_r($tableNamesValidation); "</pre>";

                if($tableNamesValidation->length != 0) {
                    $references = $xmldoc->createElement('references');
                    $fKey->appendChild($references);

                    //verify if the referenced attribute exists in the referenced table
                    $refAttrValidation = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$refTableValue.'\']/Structure/Attribute[@attributeName=\''.$refAttrValue.'\']');
                    // echo "<pre>"; print_r($refAttrValidation); "</pre>";
                    
                    if($refAttrValidation->length != 0) {
                        $refTable = $xmldoc->createElement('refTable', $refTableValue);
                        $references->appendChild($refTable);
                        
                        $refAttr = $xmldoc->createElement('refAttribute', $refAttrValue);
                        $references->appendChild($refAttr);
                
                        $uKeys = $xmldoc->createElement('uniqueKeys');
                        $table->appendChild($uKeys);
                        $uAttr = $xmldoc->createElement('UniqueAttribute', $uAttrValue);
                        $uKeys->appendChild($uAttr);
                
                        $indexFiles = $xmldoc->createElement('IndexFiles');
                        $table->appendChild($indexFiles);
                        $indexFile = $xmldoc->createElement('IndexFile');
                        $indexFiles->appendChild($indexFile);
                
                        $indexType = $xmldoc->createAttribute('indexType');
                        $indexType->value = $indexTypeValue;
                        $indexFile->appendChild($indexType);
                
                        $indexIsUnique = $xmldoc->createAttribute('isUnique');
                        $indexIsUnique->value = $indexIsUniqueValue;
                        $indexFile->appendChild($indexIsUnique);
                
                        $indexKeyLength = $xmldoc->createAttribute('keyLength');
                        $indexKeyLength->value = $indexKeyLengthValue;
                        $indexFile->appendChild($indexKeyLength);
                
                        $indexName = $xmldoc->createAttribute('indexName');
                        $indexName->value = $indexNameValue;
                        $indexFile->appendChild($indexName);
                
                        $indexAttributes = $xmldoc->createElement('IndexAttributes');
                        $indexFile->appendChild($indexAttributes);
                
                        $iAttribute = $xmldoc->createElement('IAttribute', $IAttributeValue);
                        $indexAttributes->appendChild($iAttribute);
                
                        $xmldoc->save('../Catalog.xml');
                    } else {
                        header('Location: ../Client/createTable.php?result=failedRefAttr');
                        exit;
                        // echo 'no such attribute in this table';
                    }

                } else {
                    header('Location: ../Client/createTable.php?result=failedRefTable');
                    exit;
                    // echo 'no such table';
                }
            } 
        } else {
            header('Location: ../Client/createTable.php?result=failedTable');
            exit;
            echo 'error';
        }
    }
}
header('Location: ../Client/createTable.php?result=success');
exit;
