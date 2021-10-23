<?php

if( isset($_POST['dropTableBtn']))
 {
     $tblname = $_POST['dropTableName'];
    //  echo '<p>'.$tblname.'</p>';
     $dbname = $_POST['dropTableDBname'];

     $doc = new DOMDocument;
     $doc->load('../Catalog.xml');

     $node = $doc->documentElement;
     $tags = $node->getElementsByTagName('DataBase');
     $tags1 = $node->getElementsByTagName('Table');
     $tags2 = $node->getElementsByTagName('Tables');

     foreach($tags as $tag)
     {
        $databaseName = $tag->getAttribute("dataBaseName");
        if($databaseName == $dbname){
            $db=$databaseName;
            echo $db;
            break;
        }
        
    }
    if($db !== null){

    foreach($tags1 as $tag1)
    {
        $tabelName = $tag1->getAttribute("tableName");
        //echo $tabelName.'<br />';
        if($tabelName == $tblname && $databaseName == $db) {
            // $tabel = $node->getElementsByTagName('Table')->item(0);
            // $oldtag = $node->removeChild($tag1);
            $parent = $tag1->parentNode;
            $parent->removeChild($tag1); 

        } else {
            header('Location: ../Client/dropTable.php?result=faildTable');
            exit;
        }
    }} else {
        header('Location: ../Client/dropTable.php?result=faildData');
        exit;
    }
    // echo 'tabelname: '.$tblname.'</br />';
    // echo 'db: '.$db.'<br/>';
    // echo 'databaseName'.$databaseName.'<br />';
    // echo 'tabelName: '.$tabelName.'<br />';

    $doc->save('../Catalog.xml');
    header('Location: ../Client/dropTable.php?result=success');

    exit;
}