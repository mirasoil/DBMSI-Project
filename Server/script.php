<?php
require_once('../Server/createRoot.php');
require_once('../Server/createDatabase.php');
require_once('../Server/createTable.php');
require_once('../Server/dropDatabase.php');
require_once('../Server/dropTable.php');

//Create the root element of the xml file
createRootElement();

//create the database
createDatabase();

//create the table
createTable();

//drop the database
dropDatabase();

dropTable();

