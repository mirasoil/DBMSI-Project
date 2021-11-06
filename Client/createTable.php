<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="rowsAdd.js">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Create Table</title>
  <script>
  
      $(document).ready(function() {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
       // var i = 0;
        var i = 0; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
         // i++;
        e.preventDefault();
            if(i < max_fields){ //max input box allowed
                i++; //text box increment
                $(wrapper).append('<input type="text" name="attributeName' + i + ' " class="d-flex justify-content-center" style="width: 30%" placeholder="Attribute Name"> <br /><br />  <label class="h4" style="color: white">Data Type</label> <select id="dataType' + i + '" name="dataType' + i + '" > <option value="integer">int</option> <option value="varchar">varchar</option> </select><br /> <label class="h4" style="color: white">Length</label> <select id="lengthInput' + i + '" name="lengthInput' + i + '"> <option value="3">3</option> <option value="20">20</option> <option value="50">50</option> </select><br /><br /> <label class="h4" style="color: white">Allow Null</label> <select id="isnullValue' + i + '" name="isnullValue' + i + '"> <option value="0">0</option> <option value="1">1</option> </select><br /><br />'); //add input box
            }
        });
        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').parent('div').remove(); i--;
            })
      });
    </script>
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
              } else if($result == "failedDB") { ?>
                <span class="alert alert-danger" role="alert" style="width: 30%">
                  This database does not exist yet! Try another one or create it first!
                </span>
              <br>
              <br><?php
              } else if($result == "failedTable") { ?>
                <span class="alert alert-danger" role="alert" style="width: 30%">
                  This table already exists! Try using another name!
                </span>
              <br>
              <br><?php
              }
              else if($result == "failedRefTable") { ?>
                <span class="alert alert-danger" role="alert" style="width: 30%">
                  The referenced table does not exist in this database !
                </span>
              <br>
              <br><?php
              } else if($result == "failedRefAttr") { ?>
                <span class="alert alert-danger" role="alert" style="width: 30%">
                  The referenced attribute does not exist in the referenced table !
                </span>
              <br>
              <br><?php
              }
            }
            ?>
            <input type="text" name="tableName" id="tableName" class="d-flex justify-content-center" style="width: 30%" placeholder="Table Name" required><br /> <br />
            <input type="text" name="currentDB" id="currentDB" class="d-flex justify-content-center" style="width: 30%" placeholder="Database Name" required><br /><hr>
            <div class="input_fields_wrap">
            <button class="add_field_button">Add More Fields</button>
            <input type="text" name="attributeName[]" class="d-flex justify-content-center" style="width: 30%" placeholder="Attribute Name"> <br /><br />
            <label class="h4" style="color: white">Data Type</label>
            <select id="dataType" name="dataType[]">
                <option value="integer">int</option>
                <option value="varchar">varchar</option>
            </select><br />
            <label class="h4" style="color: white">Length</label>
            <select id="lengthInput" name="lengthInput[]">
                <option value="3">3</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select><br /><br />
            <label class="h4" style="color: white">Allow Null</label>
            <select id="isnullValue" name="isnullValue[]">
                <option value="0">0</option>
                <option value="1">1</option>
            </select><br /><br />  
          </div>
           
            <input type="text" name="primaryKeyValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Primary Key" required><br /> <br />
            <input type="text" name="foreignKeyValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Foreign Key (Optional)"><br /> <br />
            <input type="text" name="refTableValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Referenced table (Optional)"><br /> <br />
            <input type="text" name="refAttrValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Referenced attribute (Optional)"><br /> <br />
            <input type="text" name="uAttrValue" class="d-flex justify-content-center" style="width: 30%" placeholder="Unique Key (Optional)"><br /> <br />
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