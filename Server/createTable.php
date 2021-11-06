<?php
 require '../Client/createDatabase.php';
if( isset($_POST['btnCreateTable']))
{
    $tableName = $_POST['tableName'];
    $currentDB = $_POST['currentDB'];
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

            $dbname = $xpath->query("/Databases/DataBase/@dataBaseName")->item($i);   //we don't know how many dbs we have
        
            //add table to the specific database
            if($currentDB == $dbname->value){
                $database = $xpath->query("/Databases/DataBase[@dataBaseName=\"".$currentDB."\"]")[0];
        
                //check if the tables tag already exists
                $validation =  $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables');
                
                if($validation->length == 0) {
                    $tables = $xmldoc->createElement('Tables');
                    $database->appendChild($tables);
                } else {
                    $tables = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables')[0];
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

                if (isset($_POST["attributeNames"]) && is_array($_POST["attributeNames"])){ 
                    for($i = 0; $i < count($_POST['attributeNames']); $i++) {
                        $attribute = $xmldoc->createElement('Attribute');
                        $structure->appendChild($attribute);
                        
                        $isnull = $xmldoc->createAttribute('isnull');
                        // $checkForNull = ($_POST['isNullValue'][$i] == 'yes' ? 0 : 1);
                        $isnull->value = $_POST['isNullValue'][$i];
                        $attribute->appendChild($isnull);
                
                        $length = $xmldoc->createAttribute('length');
                        $length->value = $_POST['lengthInput'][$i];
                        $attribute->appendChild($length);
                
                        $type = $xmldoc->createAttribute('type');
                        $type->value = $_POST['dataType'][$i];
                        $attribute->appendChild($type);
                
                        $attributeName = $xmldoc->createAttribute('attributeName');
                        $attributeName->value = $_POST['attributeNames'][$i];
                        $attribute->appendChild($attributeName);
                    }
                }
                //only if user entered a primary key
                if(! empty($primaryKeyValue)) {
                    $prKey = $xmldoc->createElement('primaryKey');
                    $table->appendChild($prKey); 

                    $prKeyAttr = $xmldoc->createElement('pkAttribute', $primaryKeyValue);
                    $prKey->appendChild($prKeyAttr);
                }
        
                if(! empty($foreignKeyValue)) {
                    $fKeys = $xmldoc->createElement('foreignKeys');
                    $table->appendChild($fKeys);
            
                    $fKey = $xmldoc->createElement('foreignKey');
                    $fKeys->appendChild($fKey);
            
                    $fKeyAttr = $xmldoc->createElement('fkAttribute', $foreignKeyValue);
                    $fKey->appendChild($fKeyAttr);
                }
        
                //verify if the referenced table exists
                $tableNamesValidation = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$refTableValue.'\']');

                if($tableNamesValidation->length != 0) { //means we have a table in our db with required name
                    $references = $xmldoc->createElement('references');
                    $fKey->appendChild($references);

                    //verify if the referenced attribute exists in the referenced table
                    $refAttrValidation = $xpath->query('/Databases/DataBase[@dataBaseName=\''.$currentDB.'\']/Tables/Table[@tableName=\''.$refTableValue.'\']/Structure/Attribute[@attributeName=\''.$refAttrValue.'\']');
                    
                    if($refAttrValidation->length != 0) {
                        $refTable = $xmldoc->createElement('refTable', $refTableValue);
                        $references->appendChild($refTable);
                        
                        $refAttr = $xmldoc->createElement('refAttribute', $refAttrValue);
                        $references->appendChild($refAttr);
                
                        if(! empty($uAttrValue)) {
                            $uKeys = $xmldoc->createElement('uniqueKeys');
                            $table->appendChild($uKeys);
                            $uAttr = $xmldoc->createElement('UniqueAttribute', $uAttrValue);
                            $uKeys->appendChild($uAttr);
                        }
                
                        if(! empty($indexTypeValue)) {
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
                        }
                
                        $xmldoc->save('../Catalog.xml');
                    } else {
                        header('Location: ../Client/createTable.php?result=failedRefAttr');
                        exit;
                    }

                } else if($refTableValue == null) {  
                //if user didn't insert any referenced table we skip the step of creating one 
                    if(! empty($uAttrValue)) {  //only create unique key tag if any value inserted
                        $uKeys = $xmldoc->createElement('uniqueKeys');
                        $table->appendChild($uKeys);
                        $uAttr = $xmldoc->createElement('UniqueAttribute', $uAttrValue);
                        $uKeys->appendChild($uAttr);
                    }
            
                    if(! empty($indexTypeValue)) {  //only create index file tag if any value inserted
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
                    }
                    
                    $xmldoc->save('../Catalog.xml');
                } else {
                    header('Location: ../Client/createTable.php?result=failedRefTable');
                    exit;
                }
            } 
        } else {
            header('Location: ../Client/createTable.php?result=failedTable');
            exit;
        }
    }
}
header('Location: ../Client/createTable.php?result=success');
exit;
