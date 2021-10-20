<?php 
function createRootElement() {

//check if the xml file exists
    $filename = '../Catalog.xml';
    if(file_exists($filename)){
    $message = "The file $filename exists";
    } else {
    $dom = new DOMDocument();

    $dom->encoding = 'utf-8';

    $dom->xmlVersion = '1.0';

    $dom->formatOutput = true;

    $xml_file_name = '../Catalog.xml';

    $root = $dom->createElement('Databases');

    $dom->appendChild($root);

    $dom->save($xml_file_name);
    }

}
