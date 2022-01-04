<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/DBMSI-Project/app/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/DBMSI-Project/app/favicon.ico" type="image/x-icon">

    <title>DBMSI</title>
    <link href="/DBMSI-Project/app/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/css/font-awesome.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/css/custom.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/plugins/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/plugins/jGrowl/jquery.jgrowl.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/plugins/select2/select2.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/plugins/select2/select2-bootstrap.css" rel="stylesheet">
    <link href="/DBMSI-Project/app/assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!--<link href="<?php /*echo Flight::get('base'); */ ?>/assets/plugins/summernote/summernote.css" rel="stylesheet">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>
    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <a class="sidebar-brand" href="/"><i class="fa fa-database"></i> <?php //echo Flight::get('appname'); ?></a>

            <label for="database">Select Database</label><br />
            <!-- <select name="database" id="database" class="form-control" style="width: 230px; margin-right: 20px;">
            <?php ?>
        </select> -->
            <select name="database" id="database" class="form-control" style="width: 230px; margin-right: 20px;">

            </select>
            <br />

            <select name="collection" id="collection" class="form-control" style="width: 230px; margin-right: 20px;">

            </select>

            <ul class="sidebar-nav">
                <?php //echo Flight::get('tables'); ?>
            </ul>
        </div>


        <!-- End Sidebar -->

        <!-- Page content -->
        <div id="page-content-wrapper">

            <div class="content-header">
                <div class="pull-left">
                    <h1><i class="glyphicon fa fa-table"></i><span id="querySelectedColl"></span> </h1>
                </div>

                <div class="pull-right" id="addbuttoncontainer">
                    <button rel="hover_popover" data-content="Build Visual Query" class="btn btn-primary btn-lg"
                        data-toggle="modal" data-target="#modal-visual-query" data-original-title="" title="">
                        <i class="fa fa-database"></i> Visual Query
                    </button>

                    <button rel="hover_popover" data-content="Type Custom Query" class="btn btn-primary btn-lg"
                        data-toggle="modal" data-target="#modal-custom-query" data-original-title="" title="">
                        <i class="fa fa-pencil-square-o"></i> Custom Query
                    </button>
                </div>

                <div class="clearfix"></div>
            </div>

            <div id="header_stips" class="progress">
                <div class="progress-bar progress-bar-primary" style="width: 25%;"></div>
                <div class="progress-bar progress-bar-success" style="width: 25%;"></div>
                <div class="progress-bar progress-bar-warning" style="width: 25%;"></div>
                <div class="progress-bar progress-bar-danger" style="width: 25%;"></div>
            </div>


            <div class="page-content inset">
                <div class="row" style="margin-top: -40px; margin-bottom: 20px;" id="tabledata">
                    <h3>Visual Query Designer</h3>
                    <div class="clearfix">&nbsp;</div>

                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline" role="grid">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length" id="DataTables_Table_0_length"><label>
                                        <div class="select2-container form-control input-sm" id="s2id_autogen2"><a
                                                href="javascript:void(0)" onclick="return false;" class="select2-choice"
                                                tabindex="-1"> <span class="select2-chosen">10</span><abbr
                                                    class="select2-search-choice-close"></abbr> <span
                                                    class="select2-arrow"><b></b></span></a><input
                                                class="select2-focusser select2-offscreen" type="text"
                                                id="s2id_autogen3">
                                            <div class="select2-drop select2-display-none select2-with-searchbox">
                                                <div class="select2-search"> <input type="text" autocomplete="off"
                                                        autocorrect="off" autocapitalize="off" spellcheck="false"
                                                        class="select2-input"> </div>
                                                <ul class="select2-results"> </ul>
                                            </div>
                                        </div><select name="DataTables_Table_0_length"
                                            aria-controls="DataTables_Table_0"
                                            class="form-control input-sm select2-offscreen" tabindex="-1">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> records per page
                                    </label></div>
                            </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div>
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info"
                                style="width: 505px;">
                                <thead>
                                    <tr role="row" style="height: 0px;" id="tableHead">
                                        
                                    </tr>
                                </thead>
                                <tbody id="dynamic_field">

                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_info" id="infoRecords" role="alert" aria-live="polite"
                                    aria-relevant="all"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <h3>Query Log</h3>
            <div class="footer">
                <pre
                    style="color: black; background-color: white; position: relative;"><span style="font-weight:bold;">SELECT</span> 
  <span>*</span> 
<span style="font-weight:bold;">FROM</span> 
  <span id="queryTableName" style="color: purple;"></span><div class="open_grepper_editor" title="Edit &amp; Save To Grepper"></div></pre>
            </div>
            <span class="alert-warning timetaken"></span>
            <br>
            <br>
            <div id="printArray">
            </div>

            <br><br>

        </div>
<!-- delete confirm modal start -->
<div class="modal fade" id="modal-delete-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-check-square-o"></i> <span
                       class="text-white bold">Delete</span></h4>
            </div>
            <div class="modal-body">
                <p class="pull-left" style="margin-right: 10px;"><i
                       class="glyphicon-4x glyphicon glyphicon-question-sign"></i></p>

                <p>You are about to delete, this procedure is irreversible.</p>

                <p>Do you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close
                </button>
                <button type="button" class="btn btnDelete btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- delete confirm modal end -->

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-success">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-info-sign"></i> <span
                       class="text-white bold">Todo Details</span></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close
                </button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-custom-query">
    <div class="modal-dialog">
        <form action="" method="post">

            <div class="modal-content">
                <div class="modal-header label-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="text-white fa fa-pencil-square-o"></i> <span
                           class="text-white bold">Custom Query</span></h4>
                </div>

                <div class="modal-body">
                    <div id="ace"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnCustomQuery" class="btn btn-success"><i class="fa fa-play"></i>
                        Run Query
                    </button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close
                    </button>
                </div>

            </div>

            <input type="hidden" id="cquery" name="cquery"/>

        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-visual-query">
    <div class="modal-dialog">
        <form action="" method="post" class="form-horizontal" role="form">

            <div class="modal-content">
                <div class="modal-header label-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="text-white fa fa-database"></i> <span
                           class="text-white bold">Visual Query</span></h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <button style="margin-bottom: 10px !important;" type="button" id="btnJoinTable" class="btn btn-primary" rel="hover_popover" data-content="Join a table">
                            <i class="glyphicon glyphicon-plus-sign"></i> Join Table
                        </button>
                        <br/>
                        <a style="display: none;" href="#" id="addjoinedtablefields"><i class="fa fa-refresh"></i> Click
                            to add Joined Table Fields</a>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="fields">Select Fields</label>

                        <div class="controls">
                            <select name="fields[]" id="fields" multiple class="fields form-control col-lg-8">
                                
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button style="margin-bottom: 10px !important;" type="button" id="btnAddWhere" class="btn btn-primary" rel="hover_popover" data-content="Add WHERE clause conditions">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Condition
                        </button>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnOrderby" class="btn btn-primary" rel="hover_popover" data-content="Add ORDER BY clause fields">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Order
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="orderby">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <select name="orderfields[]" id="orderfields" multiple class="orderfields form-control" style="width: 400px;">
                                
                            </select>
                        </div>
                        <div class="pull-left">
                            &nbsp;&nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="checkbox" id="chkDescending" name="chkDescending"/>
                            <label class="control-label" for="chkDescending" class="form-control">Descending</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnGroup" class="btn btn-primary" rel="hover_popover" data-content="Add GROUP BY clause fields">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Group Field
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="group">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <select name="groupfields[]" id="groupfields" multiple class="groupfields form-control" style="width: 400px;">
                                
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="btnLimit" class="btn btn-primary" rel="hover_popover" data-content="Add LIMIT clause details">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Limit
                        </button>
                    </div>

                    <div class="form-group parent" style="display: none;" id="limit">
                        <div class="pull-left">
                            <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
                        </div>
                        <div class="pull-left" style="margin: 3px;">
                            &nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="number" id="limitStart" name="limitStart" class="form-control" placeholder="Starting Row ID"/>
                        </div>
                        <div class="pull-left">
                            &nbsp;&nbsp;
                        </div>
                        <div class="controls pull-left">
                            <input type="number" id="limitNumRows" name="limitNumRows" class="form-control" placeholder="Number of Rows"/>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="checkbox" id="printArray" name="printArray"/>
                    <label for="printArray">Print POST Array</label>
                    &nbsp;&nbsp;

                    <button type="submit" id="btnVisualQuery" class="btn btn-success"><i class="fa fa-play"></i>
                        Run Query
                    </button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>
                        Close
                    </button>
                </div>

            </div>

            <input type="hidden" name="vquery"/>

        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="fieldClone" class="parent" style="display: none; margin: 3px;">
    <div class="pull-left">
        <a href="#" class="remove"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="ftype[]" class="form-control" style="width: 70px;">
            <option value="AND">AND</option>
            <option value="OR">OR</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="fname[]" placeholder="Field Name" class="fname form-control" style="width: 250px;">
            
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <input type="text" name="fvalue[]" placeholder="Operator + Value eg = 5 or != 10" class="form-control" style="height: 28px; width: 250px;">
    </div>
    <div class="clearfix"></div>
</div>

<div id="fieldCloneTable" class="parent" style="margin: 3px;">
    <div class="pull-left">
        <a href="#" class="remove removeme"><i class="glyphicon glyphicon-trash glyphicon-2x" style="margin-top: 5px;"></i></a>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="jointype[]" class="form-control" style="width: 150px;" id="jointype">
            <option value="INNER JOIN">INNER JOIN</option>
            <option value="LEFT JOIN">LEFT JOIN</option>
            <option value="RIGHT JOIN">RIGHT JOIN</option>
            <option value="FULL JOIN">FULL JOIN</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="jointable[]" class="jointable form-control" style="width: 160px;" id="jointable">
            <option value="">Joining Table</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <div class="pull-left">
        <select name="joinfield[]" class="joinfieldselected form-control" style="width: 160px;" id="joinfield">
            <option value="">Joining Field</option>
        </select>
    </div>
    <div class="pull-left" style="margin: 3px;">
        &nbsp;
    </div>
    <button class="btn btn-primary" id="execute">Execute</button>
    <div class="clearfix"></div>
</div>

</body>
<script>
$(document).ready(function() {
    //get all databases from mongo
    $.ajax({
        url: "../Server/selectDBS.php",
        context: document.body,
        success: function(encodedData) {
            var data = JSON.parse(encodedData);
            var sel = $('#database');
            sel.empty();
            for (var i = 0; i < data['databases'].length; i++) {
                sel.append('<option value="' + data.databases[i].name + '">' + data.databases[i]
                    .name + '</option>');
            }
        }
    });
});

//send the selected database 
$("#database").on('change', function() {
    var selectedDB = $(this).children("option:selected").val();
    // console.log(selectedDB);
    $.ajax({
        url: "../Server/selectCollections.php",
        method: 'POST',
        data: {
            db: selectedDB
        },
        success: function(encodedData) {
            var data = JSON.parse(encodedData);
            // console.log(data);
            var sel = $('#collection');
            sel.empty();
            for (var i = 0; i < data.length; i++) {
                sel.append('<option value="' + data[i] + '">' + data[i] + '</option>');
            }
            // add the collections from the selected db to the join field also
            var join = $('#jointable');
            join.empty();
            for (var i = 0; i < data.length; i++) {
                join.append('<option value="' + data[i] + '">' + data[i] + '</option>');
            }
        }
    });
});

//send the selected collection and populate the table 
$("#collection").on('change', function() {
    var selectedDB = $("#database").children("option:selected").val();
    var selectedColl = $(this).children("option:selected").val();
    // console.log(selectedColl);

    $.ajax({
        url: "../Server/selectCollectionData.php",
        method: 'POST',
        data: {
            db: selectedDB,
            coll: selectedColl
        },
        success: function(encodedData) {
            var data = JSON.parse(encodedData);
            console.log(data);
            var tableHead = $('#tableHead');
            tableHead.empty();
            var table = $('#dynamic_field');
            table.empty();
            var dataLength = Object.keys(data).length;
            for(var i = 0; i < data[dataLength - 1]; i++) {
                for(let prop in data[i]) {

                    tableHead.append(`
                        <th 
                            style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 10.5px;">
                            ${prop}
                        </th>`);
                }
                
            } 
            let hasValue = false;
            for(let x = 0; x < data[dataLength - 1]; x++) {
                if(data[x].hasOwnProperty('value')) {
                    hasValue = true;
                    break;
                }
            }
            if(hasValue) {
                for(let j = 0; j < data[dataLength-1]*2; j=j+2) {
                
                        table.append(`
                            <tr>
                                <td style="white-space: nowrap !important;">
                                    ${Object.values(data[j])}
                                </td>
                                <td style="white-space: nowrap !important;">
                                    ${Object.values(data[j+1])}
                                </td>
                            </tr>`);
                }
            } else {
                for(let j = 0; j < data[dataLength - 1]*3; j=j+3) {
                    table.append(`
                        <tr>
                            <td style="white-space: nowrap !important;">
                                ${Object.values(data[j])}
                            </td>
                            <td style="white-space: nowrap !important;">
                                ${Object.values(data[j+1])}
                            </td>
                            <td style="white-space: nowrap !important;">
                                ${Object.values(data[j+2])}
                            </td>
                        </tr>`);
                }
            }
            let newArray = Object.entries(data);
            $('#queryTableName').html(selectedColl);
            $('#infoRecords').html(`Showing ${dataLength-1} of ${dataLength-1} results`);
            $('.timetaken').html(`Time Taken: <strong> ${data[dataLength-1]} </strong> second(s)!`);
            $('#querySelectedColl').html(selectedColl);
            // also add the fields on the join field section
            var join = $('#joinfield');
            join.empty();
            join.append('<option value="id">_id</option>');
            join.append('<option value="value">value</option>');
        }
    });
});



//JOIN - execute the join
$('#execute').on('click', function() {
    var db = $("#database").children("option:selected").val();
    var coll1 = $("#collection").children("option:selected").val();
    var joinType = $("#jointype").children("option:selected").val();
    var joinTable = $("#jointable").children("option:selected").val();
    var joinField = $("#joinfield").children("option:selected").val();
    console.log(joinType, joinTable, joinField);

    $.ajax({
        url: "../Server/join.php",
        method: 'POST',
        data: {
            db: db,
            coll1: coll1,
            joinType: joinType,
            joinTable: joinTable,
            joinField: joinField
        },
        success: function(encodedData) {
            var data = JSON.parse(encodedData);
            console.log(data);
            var table = $('#dynamic_field');
            table.empty();
            var length = data.length;
            console.log(data.length)
            for (var i = 0; i < length - 1; i++) {
                // console.log(data[i])
                if(data[i].collection) {
                    table.append(`
                        <tr>
                            <td style="white-space: nowrap !important;">
                                <b> Collection ${data[i].collection} </b>
                            </td>
                            <td style="white-space: nowrap !important;">
                                <b> Collection ${data[i].collection} </b>
                            </td>
                        </tr>`);
                } else if(data[i].collectionJoin) {
                    table.append(`
                        <tr>
                            <td style="white-space: nowrap !important;">
                                <b> Collection  ${data[i].collectionJoin} </b>
                            </td>
                            <td style="white-space: nowrap !important;">
                                <b> Collection  ${data[i].collectionJoin} </b>
                            </td>
                        </tr>`);
                } else {
                    table.append(`
                        <tr>
                            <td style="white-space: nowrap !important;">
                                ${data[i]._id}
                            </td>
                            <td style="white-space: nowrap !important;">
                                ${data[i].value}
                            </td>
                        </tr>`);
                }
            }
            // $('#queryTableName').html(selectedColl);
            // $('#infoRecords').html(`Showing ${length-1} of ${length-1} results`);
            // $('.timetaken').html(`Time Taken: <strong> ${data[length-1]} </strong> second(s)!`);
            // $('#querySelectedColl').html(selectedColl);
        }
    });
})


</script>
<!-- <script src="/DBMSI-Project/app/assets/js/jquery-1.10.2.js"></script>
<script src="/DBMSI-Project/app/assets/js/bootstrap.min.js"></script>
<script src="/DBMSI-Project/app/assets/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/DBMSI-Project/app/assets/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="/DBMSI-Project/app/assets/plugins/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/DBMSI-Project/app/assets/plugins/jGrowl/jquery.jgrowl.js"></script>
<script src="/DBMSI-Project/app/assets/plugins/ace/src-min-noconflict/ext-language_tools.js"></script>
<script>
    var base = "/DBMSI-Project/app";
    var controller = "table/customer";
    var basePath = "/DBMSI-Project/app/";
    var lastSegment = "customer";
</script>
<script src="/DBMSI-Project/app/assets/js/custom.js?v=1639134576"></script> -->
</html>