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
            <input type="text" name="attributeName" class="d-flex justify-content-center" style="width: 30%" placeholder="Attribute Name"> <br /><br />
            <input type="text" name="primaryKeyValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Primary Key (Optional)"><br /> <br />
            <input type="text" name="foreignKeyValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Foreign Key (Optional)"><br /> <br />
            <input type="text" name="refTableValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Referenced table"><br /> <br />
            <input type="text" name="refAttrValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Referenced attribute"><br /> <br />
            <input type="text" name="uAttrValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Unique Key"><br /> <br />
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
            </select><br /><br />
            <label class="h4" style="color: white">Allow Null</label>
            <select id="isnullValue" name="isnullValue">
                <option value="0">0</option>
                <option value="1">1</option>
            </select><br /><br />
            <input type="text" name="indexTypeValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Index Type"><br /> <br />
            <label class="h4" style="color: white">Is Unique</label>
            <select id="isUniqueValue" name="isUniqueValue">
                <option value="0">0</option>
                <option value="1">1</option>
            </select><br /><br />
            <input type="number" name="indexKeyLengthValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Key Length"><br /> <br />
            <input type="text" name="indexNameValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Index Name"><br /> <br />
            <input type="text" name="IAttributeValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Index Attribute Value"><br /> <br />
        </div>
          <button type="submit" class="btn btn-success mb-2" name="btnCreateTable">Submit</button>
    </form>
    <button type="submit" class="btn btn-primary btn-lg" onclick="window.location.href='index.php'">Back</button>
</body>
</html>