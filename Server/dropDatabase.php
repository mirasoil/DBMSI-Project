<?php
if( isset($_POST['dropBtn']))
{
    $dbname = $_POST['DBname'];
    //echo '<p>'.$dbname.'</p>';

    $doc = new DOMDocument;
    $doc->load('../Catalog.xml');

    $node = $doc->documentElement;
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
       if($db !== null)
       {
        $tags = $node->getElementsByTagName('DataBase');
        foreach($tags as $tag){
            $databaseName = $tag->getAttribute("dataBaseName");
            //echo $databaseName;
            if($databaseName == $dbname) {
                $oldtag = $node->removeChild($tag);
            }
        }
        //echo "<br><pre>"; print_r($tag); "</pre>";
    
        //echo $doc->saveXML();
        $doc->save('../Catalog.xml');
        header('Location: ../Client/dropDatabase.php?result=success');
        exit;
    }else {
        header('Location: ../Client/dropDatabase.php?result=faild');
        exit;
    }
   
       }

