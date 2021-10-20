<?php

function dropDatabase() {

    if( isset($_POST['dropBtn']))
    {
        $dbname = $_POST['DBname'];
        echo '<p>'.$dbname.'</p>';

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