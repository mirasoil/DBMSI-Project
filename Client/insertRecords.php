<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Insert Records</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Create Database</title>
</head>

<body style="background-image: url('https://mdbootstrap.com/img/new/fluid/nature/015.jpg')">
    <div class="form-group text-center">
        <label class="h2" style="color: white">Insert Records</label><br />
        <hr>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="form-group text-center">
                    <form name="add_name" id="add_name" action="../Server/insertValidations.php" class="text-center"
                        method="post">
                        <input type="text" name="currentDB" id="currentDB" class="d-flex justify-content-center"
                            style="width: 80%" placeholder="Database Name" required><br /><br />
                        <input type="text" name="tableName" id="tableName" class="d-flex justify-content-center"
                            style="width: 80%" placeholder="Table Name" required><br /><br>
                        <hr> <br />
                        <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />

                    </form>
                    <form name="add_columns" id="add_columns" action="../Server/insert.php" class="text-center"
                        method="post">
                        <div class="table-responsive d-flex justify-content-center">
                            <table class="table table-bordered text-center" id="dynamic_field">

                            </table>
                            <input type="button" name="submit" id="submit2" class="btn btn-info" value="Submit"
                                style="display:none;" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-md-offset-4">
        <br>
        <br>
        <br>
        <button type="submit" class="btn btn-primary btn-lg" onclick="window.location.href='index.php'">Back</button>
    </div>
</body>

</html>
<script>
$(document).ready(function() {
    var i = 1;
    $('#add').click(function() {
        i++;
        $('#dynamic_field').append('<tr id="row' + i +
            '"><td><input type="text" name="name[]" placeholder="Enter Column Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="' +
            i + '" class="btn btn-danger btn_remove">X</button></td></tr>');
    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });
    $('#submit').click(function() {
        $.ajax({
            url: "../Server/insertValidations.php",
            method: "POST",
            data: $('#add_name').serialize(),
            success: function(data) {
                // alert(data);
                console.log(JSON.parse(data));
                response = JSON.parse(data);
                // $('#add_name')[0].reset();
                // $('#dynamic_field').append(data);
                if (response === "noSuchDB") {
                    triggerError("noSuchDB");
                } else if (response === "noSuchTable") {
                    triggerError("noSuchTable");
                } else {
                    var i = 1;
                    $.each(response, function(key, val) {
                        $('#dynamic_field').append('<tr id="row' + i +
                            '"><td><p style="color: white;">Column name: ' + key +
                            '</p><input type="text" name="col_name[]" placeholder="Type: ' +
                            val + ' " class="form-control name_list" /></tr><br>'
                        );
                    });
                    $('#submit').hide();
                    $('#submit2').show();
                }
            }
        });
    });
    $('#submit2').click(function() {
        let dbname = $('#currentDB').val();
        let tablename = $('#tableName').val();
        console.log(tablename)
        $.ajax({
            url: "../Server/insert.php?dbname="+dbname+"&tablename="+tablename+"",
            method: "POST",
            data: $('#add_columns').serialize(),
            success: function(data) {
                alert('Successfully inserted! Check mongoDB for further details');
                // console.log(data);
                // console.log(JSON.parse(data));
                // response = JSON.parse(data);
                $('#add_columns')[0].reset();
                $('#add_columns').hide();
                $('#submit').show();
            }
        });
    });
});
function triggerError(error) {
   if (error === "noSuchDB") {
      alert("There is no such DB!");
   } else if (error === "noSuchTable") {
      alert("There is not such a table in this DB!");
   }
}
</script>