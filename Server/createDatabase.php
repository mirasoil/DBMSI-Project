<?php
require '../vendor/autoload.php';

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
        $node = $xmldoc->documentElement;
        $tags = $node->getElementsByTagName('DataBase');
        $db= null;
        foreach($tags as $tag)
        {
           $databaseName = $tag->getAttribute("dataBaseName");
           if($databaseName == $dbname){
               $db=$databaseName;
               echo $db;
               break;
           }  
       }
       if($db === null)
       {

             //verify if there is a database tag already in the xml file
        //$db = file_exists("");
        
        //create database tag
        $database = $xmldoc->createElement('DataBase');
        $dbAttr = $xmldoc->createAttribute("dataBaseName");
        $dbAttr->value = $dbname;
        $database->appendChild($dbAttr);

        $root->insertBefore($database, $root->firstChild);

        $xmldoc->save('../Catalog.xml');
//                    // Creating Connection  
// $con = new MongoDB\Client("mongodb://localhost:27017");  
// // Creating Database  
// $db = $con->$dbname;  
// //Creating Document  
// $collection = $db->employee;  
// // // Insering Record  
// // $collection->insertOne( [ 'name' =>'Peter', 'email' =>'peter@abc.com' ] );  
// // // Fetching Record  
// // $record = $collection->find( [ 'name' =>'Peter'] );  
// // foreach ($record as $employe) {  
// // echo $employe['name'], ': ', $employe['email']."<br>";  
// // }  

$m = new MongoDB\Client("mongodb://localhost:27017"); 
// echo "Connection to database successfully";
 
// select a database
$db = $m->$dbname;
// echo "Database mydb selected";
// $collection = $db->createCollection("mycol");
// echo "Collection created succsessfully";

        header('Location: ../Client/createDatabase.php?result=success');
        exit;
       }
       else {
        header('Location: ../Client/createDatabase.php?result=faild');
        exit;
       }
       
    }
    
