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
                    var j = 0;
                    var found = false;
                    var foundFK = false;
                    var i1 = 1;
                    var j1 = 0;
                    var foundRefTable = false;
                    var i2 = 1;
                    var j2 = 0;
                    if(response.hasOwnProperty('uniqueKey0')) {
                        found = true;
                    }
                    if(response.hasOwnProperty('foreignKey0')) {
                        foundFK = true;
                    }
                    if(response.hasOwnProperty('refTable')) {
                        foundRefTable = true;
                    }
                    $.each(response, function(key, val) {
                        if(!key.includes('uniqueKey') && !key.includes('foreignKey') && !key.includes('refTable')) {
                            $('#dynamic_field').append('<tr id="row' + i +
                                '"><td><p style="color: white;">Column name: ' + key +
                                '</p><input type="text" name="col_name[]" placeholder="Type: ' +
                                val + ' " class="form-control name_list" /></td></tr><br>'
                            );
                        }
                    });
                    if(found == true) {
                        while(response.hasOwnProperty('uniqueKey'+j)) {
                            $('#dynamic_field').append(`<br><p class="p-tags-for-unique-index" style="color: white;font-size: 18px;">Unique Key: <span id='uniq${+j}'><b> ${response['uniqueKey'+j]} </b><span></p><br>`)
                            j++
                        }
                        
                    }
                    if(foundFK == true) {
                        while(response.hasOwnProperty('foreignKey'+j1)) {
                            $('#dynamic_field').append(`<br><p class="p-tags-for-foreign-key" style="color: white;font-size: 18px;">Foreign Key: <span id='fk${+j1}'><b> ${response['foreignKey'+j1]} </b><span></p><br>`)
                            j1++
                        }
                        
                    }
                    if(foundRefTable == true) {
                        $('#dynamic_field').append(`<br><p class="p-tags-for-ref-table" style="color: white;font-size: 18px;">Referenced table: <span id='refTable${+j2}'><b> ${response['refTable']} </b><span></p><br>`)
               
                    }
                    $('#submit').hide();
                    $('#submit2').show();
                }
            }
        });
    });
    $('#submit2').click(function() {
        let dbname = $('#currentDB').val();
        let tablename = $('#tableName').val();
        let uniqueIndexes = '';
        let i = 0;
        while(i<document.querySelectorAll('.p-tags-for-unique-index').length){
            uniqueIndexes += '&uniqueIndex[]='+$('#uniq'+i).text().split(" ").join("")
            i++
        }
        let foreignKey = '';
        let i1 = 0;
        while(i1<document.querySelectorAll('.p-tags-for-foreign-key').length){
            foreignKey += '&foreignKey[]='+$('#fk'+i1).text().split(" ").join("")
            i1++
        }
        let refTable = '';
        let i2 = 0;
        while(i2<document.querySelectorAll('.p-tags-for-ref-table').length){
            refTable += '&refTable='+$('#refTable'+i2).text().split(" ").join("")
            i2++
        }

        $.ajax({
            url: "../Server/insert.php?dbname="+dbname+"&tablename="+tablename+uniqueIndexes+foreignKey+refTable,
            method: "POST",
            data: $('#add_columns').serialize(),
            success: function(data) {
                if (data.includes("success") && data.includes('Error! Duplicate key error!')) {
                    alert('Error! Duplicate key error! Try inserting something else!');
                } else if (data.includes('succes')) {
                    alert('Successfully inserted! Check mongoDB for further details');
                } else if (data.includes('No such id in parent table!')) {
                    alert('No such id in parent table!');
                } else if (data.includes('There was a problem. Please try again!')) {
                    alert('There was a problem. Please try again!');
                } else if (data.includes('duplicate key error collection')) {
                    alert('Error! Duplicate key error! Try inserting something else!');
                }
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