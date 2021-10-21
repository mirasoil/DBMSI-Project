<?php
require_once('../Server/createRoot.php');
createRootElement();

if( isset($_POST['btnCreate']))
    {
        $dbname = $_POST['DBname'];
        //echo '<p>'.$dbname.'</p>';

        $xmldoc = new DomDocument();

        //get the xml file
        $xml = file_get_contents( '../Catalog.xml');
        $xmldoc->loadXML( $xml, LIBXML_NOBLANKS );

        //get the root
        $root = $xmldoc->getElementsByTagName('Databases')->item(0);
       // echo "<pre>"; print_r($root); "</pre>";
        
        //verify if there is a database tag already in the xml file
        //$db = file_exists("");
        
        //create database tag
        $database = $xmldoc->createElement('DataBase');
        $dbAttr = $xmldoc->createAttribute("dataBaseName");
        $dbAttr->value = $dbname;
        $database->appendChild($dbAttr);

        $root->insertBefore($database, $root->firstChild);

        $xmldoc->save('../Catalog.xml');
    }
    header('Location: ../Client/createDatabase.php?result=success');
    exit;
