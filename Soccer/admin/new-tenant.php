<?php

//a page name
$pgnm = 'TQS: Admit a Player';
$error = ' ';

//start sessions 
ob_start();

//require a connector
require_once "functions/db.php";

//require the global file for errors
require_once "functions/errors.php";


// Initialize the session
session_start();

// If user is not logged in, redirect to index otherwise, allow access
if (is_logged_in_temporary()) {
    //allow access

    //take requests & actions

    /*****************************************************
                               action add a tenant
     ***************************************************/
    if (isset($_POST['admitTenant'])) {
        //admin requests to admit a tenant. 
        //This is a transaction involving table tenants and houses
        //STEPS
        /* 1. collect the information supplied
                           2. insert to table tenants
                           3. update table houses, set number_of_rooms -=1
                           4. if current number_of_rooms == 1 (1 room remaining), update status = occupied

                        */

        //gather the data
        $tname = is_username($_POST['tname']);
        $firstName = substr($tname, 0, strpos($tname, ' ')); //first name

        $dateofbirth = uncrack($_POST['dob']);
        $tschool = uncrack($_POST['school']);
        $tgrade = uncrack($_POST['classGrade']);
        $idnum = uncrack($_POST['idnum']);
        $category = uncrack($_POST['category_group']);
        $house = uncrack($_POST['location']); //a combination of room ID and number of rooms

        // Handle file upload
        $targetDir = "uploads/"; // Define a directory to save uploaded files
        $imageFileType = strtolower(pathinfo($_FILES["player_image"]["name"], PATHINFO_EXTENSION));
        $targetFile = $targetDir . $idnum . '.' . $imageFileType; 
        $uploadOk = 1;

        // Check if image file is a real image or fake image
        $check = getimagesize($_FILES["player_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["player_image"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats (optional but recommended for security)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["player_image"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["player_image"]["name"])) . " has been uploaded.";

                // Use the image path ($targetFile) in your SQL insertion
                $sq_tenants = "INSERT INTO `players` (`player_name`, `dob`, `school_name`, `class_grade`, `identification_number`, `player_category`, `club`, `image_path`) 
                           VALUES ('$tname', '$dateofbirth', '$tschool', '$tgrade', '$idnum', '$category', '$house', '$targetFile');";

                // Continue with database insertion...
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        //Start with disabling autocommit
        $mysqli->autocommit(FALSE);
        //set a status flag. we shall flag it to 'false' if any of the transactions fails
        $status = true;

        //EXECUTE QUERRIES
        $mysqli->query($sq_tenants) ? null : $status = false;

        if ($status) {
            #successful, commit changes
            $mysqli->commit();
            //head to tenants table and report as an error state code 3. refer errors.php
            header('location:tenants.php?state=3');
        } else {
            //rollback changes
            $mysqli->rollback();
            //return back to page with error state 4
            header('location:new-tenant.php?state=4');
        }
    }


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
                    <h4 class="page-title"><?php echo 'Hey ' . $username . '!'; ?></h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="tenants.php">Players</a></li>
                        <li class="active">New Player</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!--.row-->
            <div class="row">
                <div class="col-md-12">
                    <div style="">
                        <?php
                        echo $error;
                        ?>
                    </div>
                    <div class="white-box">
                        <h3 class="box-title m-b-0"><i class="fa fa-user fa-3x"></i> Admit A New Player</h3>
                        <p class="text-muted m-b-30 font-13"> Fill in the form below: </p>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <form action="new-tenant.php" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="hname">Player's Full Name: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-pencil"></i></div>
                                            <input type="text" name="tname" class="form-control" id="hname" placeholder="Enter Player's name" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dob">Date of Birth: </label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="date" name="dob" class="form-control" id="dob" placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="idnum">Birth Cert/Passport No: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                            <input type="number" min="1000" required name="idnum" class="form-control" id="idnum" placeholder="Birth Cert/Passport Number...">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="image">Player's Birth Cert Image:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-file-image-o"></i></div>
                                            <input type="file" name="player_image" class="form-control" id="image" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="school">School Name: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-university"></i></div>
                                            <input type="text" name="school" class="form-control" id="school" placeholder="Enter School Name" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="classGrade">Class Grade: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-graduation-cap"></i></div>
                                            <input type="text" name="classGrade" class="form-control" id="classGrade" placeholder="Enter Class Grade" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="category_group">Player Category: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                                            <select id="category_group" name="category_group" class="form-control">
                                                <option value="">**Select Player Category**</option>
                                                <option value="U7">U7</option>
                                                <option value="U9">U9</option>
                                                <option value="U11">U11</option>
                                                <option value="U13">U13</option>
                                                <option value="U15">U15</option>
                                                <option value="U17">U17</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="location">Club: *</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                                            <select id="location" name="location" class="form-control">
                                                <option value="">**Select Club**</option>
                                                <?php
                                                $sq0 = "SELECT * FROM `clubs` order by `id` asc";
                                                $rec = mysqli_query($conn, $sq0);
                                                while ($row = mysqli_fetch_array($rec, MYSQLI_BOTH)) {
                                                    $place = $row['club_name'];
                                                    echo "<option value=\"$place\"> $place </option> ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" name="admitTenant" class="btn btn-success btn-lg waves-effect waves-light m-r-10 center">
                                        <i class="fa fa-plus-circle fa-lg"></i> Admit This Player
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--./row-->



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
                            <br />
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
        <!-- /.container-fluid -->
        <footer class="footer text-center"> 2024 &copy; T.Q.S. Admin </footer>
    </div>
    <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/jasny-bootstrap.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>

    <!-- Local Javascript -->
    <script type="text/javascript">

    </script>
    <!--END of local JS -->

    </body>

    </html>
<?php
} else {
    header('location:../index.php');
}
?>