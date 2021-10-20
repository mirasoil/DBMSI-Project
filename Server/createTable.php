<?php
function createTable() {

    if( isset($_POST['btnCreateTable']))
    {
        $tableName = $_POST['tableName'];
        echo '<p>'.$tableName.'</p>';

        $currentDB = $_POST['currentDB'];
        echo '<p>'.$currentDB.'</p>';

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
        echo $i;
        $dbname = $xpath->query("/Databases/DataBase/@dataBaseName")->item($i);   //we don't know how many dbs we have
        //echo "<pre>"; print_r($dbname); "</pre>";
        //echo $dbname->value;

        //add table to the specific database
        if($currentDB == $dbname->value){
            $database = $xmldoc->getElementsByTagName('DataBase')->item($i);
            //$database = $database1->getAttribute('tableName');
            echo "<pre>"; print_r($database); "</pre>";
    
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
    
                $xmldoc->save('../Catalog.xml');
            }
        }
    }
    header('Location: ../Client/createTable.php?result=success');
    
}