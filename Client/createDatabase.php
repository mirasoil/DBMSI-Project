<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Create Database</title>
</head>
<body  style="background-image: url('https://mdbootstrap.com/img/new/fluid/nature/015.jpg')">

  <form action="../Server/createDatabase.php" class="text-center" method="post">
        
        <div class="form-group text-center">
            <label class="h2" style="color: white">Create Database</label><br/>
            <hr>
            <?php 
            if(isset($_GET['result'])) {
              $result = $_GET['result'];
              if($result == "success") {
              ?>
              <span class="alert alert-success" role="alert" style="width: 30%">
                Database created successfully!
              </span>
              <br>
              <br>
              <?php
              } else if($result == "faild"){?>
                <span class="alert alert-danger" role="alert" style="width: 30%">
                The database already exists!
              </span>
              <br>
              <br><?php
              }
            }
            ?>
            
            <input type="text" name="DBname" id="DBname" class="d-flex justify-content-center" style="width: 30%" placeholder="Database Name">
        </div>
          <button type="submit" name="btnCreate" class="btn btn-success mb-2">Submit</button>
    </form>
    <button type="submit" class="btn btn-primary btn-lg" onclick="window.location.href='index.php'">Back</button>
</body>
</html>