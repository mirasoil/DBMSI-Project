<!DOCTYPE html>
<html lang="en">
<?php
require_once('../Server/createRoot.php');
createRootElement();
?>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Proiect</title>
</head>
<body  style="background-image: url('https://mdbootstrap.com/img/new/fluid/nature/015.jpg')">

    <h1 class="text-center" style="color: white">Actiuni</h1>
    <br />
    <hr/>
    <br/>
      <div class="col-md-12 text-center" style="margin-bottom: 30px">
      <button type="button" class="btn btn-info btn-lg" onclick="window.location.href='createDatabase.php'">Create Database</button>
      <button type="button" class="btn btn-info btn-lg"onclick="window.location.href='createTable.php'">Create Table</button>
    </div>
    <div class="col-md-12 text-center" style="margin-bottom: 30px">
      <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='dropDatabase.php'">Drop Database</button>
      <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='dropTable.php'">Drop Table</button>
    </div>
    <div class="col-md-12 text-center" style="margin-bottom: 30px">
      <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='insertRecords.php'">Insert</button>
      <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='deleteRecords.php'">Delete</button>
    </div><br>
    <div class="col-md-12 text-center">
      <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='select.php'">Select</button>
      <!-- <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.href='deleteRecords.php'">Delete</button> -->
    </div>

</body>
</html>