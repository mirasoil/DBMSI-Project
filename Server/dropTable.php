<?php

function dropTable() {

    if( isset($_POST['dropTableBtn']))
    {
        $dbname = $_POST['dropTableDBname'];
        $tblname = $_POST['dropTableName'];
        echo '<p>'.$dbname.'</p><br>';
        echo '<p>'.$tblname.'</p><br>';

        $doc = new DOMDocument;
        $doc->load('../Catalog.xml');

        $node = $doc->documentElement;


        $tags = $node->getElementsByTagName('DataBase');
        foreach($tags as $tag){
            $databaseName = $tag->getAttribute("dataBaseName");
            echo $databaseName;
           if($databaseName == $dbname) {
                $oldtag = $node->removeChild($tag);
           } else {
               echo "This db does not exist!";
           }
        }
        echo "<br><pre>"; print_r($tag); "</pre>";

        echo $doc->saveXML();
        $doc->save('../Catalog.xml');
    }
 
}