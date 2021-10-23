<?php

if( isset($_POST['btnCreateTable']))
{
    $tableName = $_POST['tableName'];
    //echo '<p>'.$tableName.'</p>';

    $currentDB = $_POST['currentDB'];
    //echo '<p>'.$currentDB.'</p>';

    $attributeNameValue = $_POST['attributeName'];

    $isnullValue = $_POST['isnullValue'];

    $uniqueKeyValue = $_POST['uniqueKeyValue'];

    $primaryKeyValue = $_POST['primaryKeyValue'];

    $foreignKeyValue = $_POST['foreignKeyValue'];
    $refTableValue = $_POST['refTableValue'];
    $refAttrValue = $_POST['refAttrValue'];
    $uAttrValue = $_POST['uAttrValue'];

    $lengthValue = $_POST['lengthInput'];
    $typeValue = $_POST['dataType'];

    $xmldoc = new DomDocument();

    //get the xml file
    $xml = file_get_contents( '../Catalog.xml');
    $xmldoc->loadXML( $xml, LIBXML_NOBLANKS );

    //access the name of the database
    $xmldoc->Load('../Catalog.xml');
    $xpath = new DOMXPath($xmldoc);
    $i = 0;
    while($currentDB != $xpath->query("/Databases/DataBase/@dataBaseName")->item($i)->value) {
        $i++;
    }
    //echo $i;
    $dbname = $xpath->query("/Databases/DataBase/@dataBaseName")->item($i);   //we don't know how many dbs we have
    //echo "<pre>"; print_r($dbname); "</pre>";
    //echo $dbname->value;

    //add table to the specific database
    if($currentDB == $dbname->value){
        $database = $xmldoc->getElementsByTagName('DataBase')->item($i);
        //$database = $database1->getAttribute('tableName');
        //echo "<pre>"; print_r($database); "</pre>";

        //check if the tables tag already exists
        $validation =  $xmldoc->getElementsByTagName('Tables')->item($i);
        if(empty($validation)) {
            $tables = $xmldoc->createElement('Tables');
            $database->insertBefore($tables, $database->firstChild);

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
            $isnull->value = $isnull->value = ($isnullValue == 'on') ? 0 : 1;
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

            $references = $xmldoc->createElement('references');
            $fKey->appendChild($references);
            $refTable = $xmldoc->createElement('refTable', $refTableValue);
            $references->appendChild($refTable);
            $refAttr = $xmldoc->createElement('refAttribute', $refAttrValue);
            $references->appendChild($refAttr);

            $uKeys = $xmldoc->createElement('uniqueKeys');
            $table->appendChild($uKeys);
            $uAttr = $xmldoc->createElement('UniqueAttribute', $uAttrValue);
            $uKeys->appendChild($uAttr);

            $xmldoc->save('../Catalog.xml');
        } else {
            $tables = $xmldoc->getElementsByTagName('Tables')->item($i);

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
            $isnull->value = $isnull->value = ($isnullValue == 'on') ? 0 : 1;
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

            $references = $xmldoc->createElement('references');
            $fKey->appendChild($references);
            $refTable = $xmldoc->createElement('refTable', $refTableValue);
            $references->appendChild($refTable);
            $refAttr = $xmldoc->createElement('refAttribute', $refAttrValue);
            $references->appendChild($refAttr);

            $uKeys = $xmldoc->createElement('uniqueKeys');
            $table->appendChild($uKeys);
            $uAttr = $xmldoc->createElement('UniqueAttribute', $uAttrValue);
            $uKeys->appendChild($uAttr);

            $xmldoc->save('../Catalog.xml');
        }
    }
}
header('Location: ../Client/createTable.php?result=success');
exit;
