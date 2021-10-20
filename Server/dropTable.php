<?php

function dropTable() {
    if( isset($_POST['dropTableBtn'])) {
        $doc = new DOMDocument; 
        $doc->load('../Catalog.xml');

        $tblname = $_POST['dropTableName'];

        $thedocument = $doc->documentElement;

        //this gives you a list of the messages
        $list = $thedocument->getElementsByTagName('Table');

        //figure out which ones you want -- assign it to a variable (ie: $nodeToRemove )
        $nodeToRemove = null;
        foreach ($list as $domElement){
        $attrValue = $domElement->getAttribute('tableName');
        if ($attrValue == $tblname) {
            $nodeToRemove = $domElement; //will only remember last one- but this is just an example :)
            echo "<br><pre>"; print_r($nodeToRemove); "</pre>";
        }
        }
        echo "<br><pre>"; print_r($nodeToRemove); "</pre>";
        //Now remove it.
        if ($nodeToRemove != null)
        $thedocument->removeChild($nodeToRemove);

        echo $doc->saveXML(); 
    }
    
}


// function dropTable() {

//     if( isset($_POST['dropTableBtn']))
//     {
//         $dbname = $_POST['dropTableDBname'];
//         $tblname = $_POST['dropTableName'];
//         echo '<p>'.$dbname.'</p><br>';
//         echo '<p>'.$tblname.'</p><br>';

//         $doc = new DOMDocument;
//         $doc->load('../Catalog.xml');

//         $node = $doc->documentElement;


//         $tags = $node->getElementsByTagName('DataBase');
//         foreach($tags as $tag){
//             $databaseName = $tag->getAttribute("dataBaseName");
//             echo $databaseName;
//            if($databaseName == $dbname) {
//                 $tableTags = $node->getElementsByTagName('Table');
//                 foreach($tableTags as $tableTag) {
//                     $tableAttr = $tableTag->getAttribute('tableName');
//                     echo "Table name: ".$tableAttr;
//                     if($tableAttr == $tblname) {
//                         $nodeToRemove = $tableTag;
//                     }
//                 }
//            } 
//         }
        
//         if ($nodeToRemove != null)
//         $node->removeChild($nodeToRemove);
//         echo "<br><pre>"; print_r($tableTag); "</pre>";

//         echo $doc->saveXML();
//         $doc->save('../Catalog.xml');
//     }
 
// }