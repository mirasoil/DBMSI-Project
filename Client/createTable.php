<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Create Table</title>
</head>
<body  style="background-image: url('https://mdbootstrap.com/img/new/fluid/nature/015.jpg')">

    <form action="../Server/createTable.php" class="text-center" method="post">
        
        <div class="form-group text-center">
            <label class="h2" style="color: white">Create Table</label><br/>
            <hr>
            <?php 
            if(isset($_GET['result'])) {
              $result = $_GET['result'];
              if($result == "success") {
              ?>
              <span class="alert alert-success" role="alert" style="width: 30%">
                Table created successfully!
              </span>
              <br>
              <br>
              <?php
              }
            }
            ?>
            <input type="text" name="tableName" id="tableName" class="d-flex justify-content-center" style="width: 30%" placeholder="Table Name"><br /> <br />
            <input type="text" name="currentDB" id="currentDB" class="d-flex justify-content-center" style="width: 30%" placeholder="Database Name"><br /><hr>
            <input type="text" name="attributeName" class="d-flex justify-content-center" style="width: 30%" placeholder="Attribute Name"> <br />
            <label class="h4" style="color: white">Data Type</label>
            <select id="dataType" name="dataType">
                <option value="integer">int</option>
                <option value="varchar">varchar</option>
            </select><br />
            <label class="h4" style="color: white">Length</label>
            <select id="lengthInput" name="lengthInput">
                <option value="3">3</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select><br />
            <input type="checkbox" name="primaryKeyValue" class="form-check-input ">
            <label class="form-check-label h4"  style="color: white">Primary Key</label><br />
            <input type="checkbox" name="uniqueKeyValue" class="form-check-input">
            <label class="form-check-label h4" style="color: white">Unique Key</label><br />
            <input type="checkbox" name="isnullValue" class="form-check-input">
            <label class="form-check-label h4" style="color: white">Allow Null</label>
        </div>
          <button type="submit" class="btn btn-success mb-2" name="btnCreateTable">Submit</button>
    </form>
    <button type="submit" class="btn btn-primary btn-lg" onclick="window.location.href='index.php'">Back</button>
</body>
</html>