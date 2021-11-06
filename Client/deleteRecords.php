<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Drop Table</title>
</head>
<body  style="background-image: url('https://mdbootstrap.com/img/new/fluid/nature/015.jpg')">

    <form action="../Server/delete.php" class="text-center" method="post">
        
        <div class="form-group text-center">
            <label class="h2" style="color: white">Delete Record by ID</label><br/>
            <hr>
            <?php 
            if(isset($_GET['result'])) {
              $result = $_GET['result'];
              if($result == "success") {
              ?>
              <span class="alert alert-success" role="alert" style="width: 30%">
                Record deleted!
              </span>
              <br>
              <br>
              <?php
              } else if($result == "faildData") {?>
              <span class="alert alert-danger" role="alert" style="width: 30%">
              The database does not exist!
              </span>
              <br>
              <br>
              <?php

            }else if($result == "faildTable") {?>
            <span class="alert alert-danger" role="alert" style="width: 30%">
            The table does not exist!
              </span>
              <br>
              <br>
              <?php
              }
          }
            ?>
            <input type="text" name="deleteTableName" class="d-flex justify-content-center" style="width: 30%" placeholder="Table Name"> <br/><br/>
            <input type="text" name="deleteColumnName" class="d-flex justify-content-center" style="width: 30%" placeholder="ID">
        </div>
          <button type="submit" name="dropTableBtn"  class="btn btn-success mb-2 ">Submit</button>
    </form>
    <button type="submit" class="btn btn-primary btn-lg" onclick="window.location.href='index.php'">Back</button>
</body>
</html>