<?php
    $pgnm="TQS: View Mazingira Tournament Registered  Players";
    $error=' ';

    //require the global file for errors
    require_once "functions/errors.php";
    
    ob_start();
    require_once "functions/db.php";

    // Initialize the session

    session_start();

    // If session variable is not set it will redirect to login page

    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

      header("location: login.php");

      exit;
    }
    if (is_logged_in_temporary()) {
        #allow access
    

    $email = $_SESSION['email'];

   /* $sql = "SELECT `tenantID`,`houseNumber`,`tenant_name`,`email`,`ID_number`,`profession`,`phone_number`,`dateAdmitted`,`agreement_file`, `house_name`,`number_of_rooms`,`house_status`,`rent_amount`,`houseID` FROM `tenants`LEFT join `houses` ON `tenants`.`houseNumber`=`houses`.`houseID`";
   */
   $sql="select * from `players`";

    $query = mysqli_query($connection, $sql);
    
    /*******************************************************
                    introduce the admin header
    *******************************************************/
    require "admin_header0.php";

    /*******************************************************
                    Add the left panel
    *******************************************************/
    require "admin_left_panel.php";
?>

    

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"> Jambo <?php echo $username;?>,</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="index.php">Dashboard</a></li>
                            <li><a href="#" class="active">Players</a></li>
                            <li><a href="new-tenant.php">New Player</a></li>
                            
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                   
                    
                    <div class="col-sm-12">
                        <div class="white-box">

                        		<?php
                                echo $error;

                                if (isset($_GET["success"])) {
                                        echo 
                                        '<div class="alert alert-success" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>DONE!! </strong><p> The new player has been added successfully.</p>
                                        </div>'
                                        ;
                                    }
                                    elseif (isset($_GET["deleted"])) {
                                        echo 
                                        '<div class="alert alert-warning" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>DELETED!! </strong><p> The player records have been successfully deleted.</p>
                                        </div>'
                                        ;
                                    }
                                    elseif (isset($_GET["del_error"])) {
                                        echo 
                                        '<div class="alert alert-danger" >
                                              <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                             <strong>ERROR!! </strong><p> There was an error during the deletion of this record. Please try again.</p>
                                        </div>'
                                        ;
                                    }
								?>	

                            <h3 class="box-title m-b-0">Current players listing ( <x style="color: orange;"><?php echo @mysqli_num_rows($query);?></x> )</h3>
                            <p class="text-muted m-b-30">Export data to Copy, CSV, Excel, PDF & Print</p>
                            <div class="table-responsive">
                                <table id="example23" class="display nowrap" cellspacing="0" width="100%">

                                    <?php 

                                    if (@mysqli_num_rows($query)==0) {
                                                    echo "<i style='color:brown;'>No Players to Display:( </i> ";
                                                }
                                                else{

                                                    echo '
                                                    <thead>
                                                    <tr>
                                                        <th>Player Name</th>
                                                        <th>D.O.B.</th>
                                                        <th>School</th>
                                                        <th>Grade</th>
                                                        <th>Passport/Birth Cert</th>
                                                        <th>Club</th>
                                                        <th>Player Category</th>
                                                        <th>Birth Cert Image</th>
                                                        <th>Admission Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>

                                                <tfoot>
                                                    <tr>
                                                        <th>Player Name</th>
                                                        <th>D.O.B.</th>
                                                        <th>School</th>
                                                        <th>Grade</th>
                                                        <th>Passport/Birth Cert</th>
                                                        <th>Club</th>
                                                        <th>Player Category</th>
                                                        <th>Birth Cert Image</th>
                                                        <th>Admission Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    ';
                                                }

                                        while ($row = @mysqli_fetch_array($query)) {
                                            

                                    echo '
                                    

                                        <tr>
                                    <td>'.$row["player_name"].'</td>
                                    <td>'.date('Y-m-d', strtotime($row["dob"])).'</td>
                                    <td>'.$row["school_name"].'</td>
                                    <td>'.$row["class_grade"].'</td>
                                    <td>'.$row["identification_number"].'</td>
                                    <td>'.$row["club"].'</td>
                                    <td>'.$row["player_category"].'</td>
                                    <td><img src="'.$row["image_path"].'" alt="Player Image" style="width:100px;height:auto;"/></td>
                                    <td>'.$row["created_date"].'</td>';
                                    // Conditional logic for displaying the delete and update actions
                                    if ($userRole !== 'level-0') {
                                        echo '
                                            <td>
                                                <a href="update_record.php?id=' . $row["playerID"] . '">
                                                    <i class="fa fa-edit" title="update" style="color:blue; margin-right:10px;"></i>
                                                </a>
                                                <a href="#">
                                                    <i class="fa fa-trash" data-toggle="modal" data-target="#responsive-modal' . $row["playerID"] . '" title="remove" style="color:red;"></i>
                                                </a>
                                            </td>';
                                    }

                                       echo '

                                            <!-- /.modal -->
                                            <div id="responsive-modal'.$row["playerID"].'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h4 class="modal-title">Are you really sure you want to permanently delete '.$row["player_name"].'\'s record?</h4>
                                                            </div>
                                                        <div class="modal-footer">

                                                        <form action="functions/del_tenant.php" method="post">
                                                        <input type="hidden" name="tenID" value="'.
                                                        $row["playerID"].'"/>
                                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="deleteTenant" class="btn btn-danger waves-effect waves-light">Delete and Forget</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <!-- End Modal -->

                                            <!-- Modal to edit. -->

                                         </tr>
                                    ';

                                    }

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


             


                <!-- /.row -->
                                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul>
                                <li><b>Layout Options</b></li>
                                <li>
                                    <div class="checkbox checkbox-info">
                                        <input id="checkbox1" type="checkbox" class="fxhdr">
                                        <label for="checkbox1"> Fix Header </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-warning">
                                        <input id="checkbox2" type="checkbox" checked="" class="fxsdr">
                                        <label for="checkbox2"> Fix Sidebar </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="checkbox checkbox-success">
                                        <input id="checkbox4" type="checkbox" class="open-close">
                                        <label for="checkbox4"> Toggle Sidebar </label>
                                    </div>
                                </li>
                            </ul>
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
                                <li><b>With Dark sidebar</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                            </div>
                    </div>
                </div>
                <!-- /.right-sidebar -->
            </div>
            <?php require "admin_footer.php"; ?>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
<?php
}
else{
    header('location:index.php');
}
?>