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
        $tags1 = $node->getElementsByTagName('Tables');
        foreach($tags as $tag){
            $databaseName = $tag->getAttribute("dataBaseName");
            echo $databaseName;
            if($databaseName == $dbname){
                $db=$databaseName;
                break;
                
            }
            foreach($tags1 as $tag){
                $tabelName = $tag->getAttribute("tableName");
                echo $tabelName;
                $xpath = new DOMXPath($tabelName);
           if($tabelName == $tblname && $xpath->query("parent::*")== $db) {
                $oldtag = $node->removeChild($tag);
           }}
        }
        echo "<br><pre>"; print_r($tag); "</pre>";

        echo $doc->saveXML();
        $doc->save('../Catalog.xml');
    }
 
}