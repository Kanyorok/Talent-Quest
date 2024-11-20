<?php
// Start output buffering to avoid headers already sent issues
ob_start();

// Connect to your database
require_once "functions/db.php";

// Initialize the session
session_start();

// If session variable is not set, redirect to login page
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $playerID = $_GET['id'];

    // Retrieve the current data for the player
    $query = "SELECT * FROM players WHERE playerID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $playerID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a record is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit;
    }

    // Handle form submission for updating record
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $playerName = $_POST['player_name'];
        $dob = $_POST['dob'];
        $schoolName = $_POST['school_name'];
        $classGrade = $_POST['class_grade'];
        $identificationNumber = $_POST['identification_number'];
        $club = $_POST['club'];
        $playerCategory = $_POST['player_category'];
        $imagePath = $_POST['image_path'];

        // Update query
        $updateQuery = "UPDATE players SET player_name = ?, dob = ?, school_name = ?, class_grade = ?, identification_number = ?, club = ?, player_category = ?, image_path = ? WHERE playerID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssssssssi", $playerName, $dob, $schoolName, $classGrade, $identificationNumber, $club, $playerCategory, $imagePath, $playerID);

        if ($updateStmt->execute()) {
            header('Location: tenants.php?state=3.6');
            exit;
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title>Company Admin | Update Record</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
                <div class="top-left-part"><a class="logo" href="new-tenant.php"><b><img src="../plugins/images/icon.png" style="width: 30px; height: 30px;" alt="home" /></b><span class="hidden-xs"><b>Company</b></span></a></div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">

                    <!-- /.dropdown -->



                    <li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <!-- input-group -->
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
                                <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span>
                        </div>
                        <!-- /input-group -->
                    </li>
                    <li class="user-pro">
                        <a href="#" class="waves-effect active"><img src="../plugins/images/user.jpg" alt="user-img" class="img-circle"> <span class="hide-menu"> Account<span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li><a href="settings.php"><i class="ti-settings"></i> Account Setting</a></li>
                            <li><a href="login.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>




                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $email; ?></h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Account</a></li>
                            <li class="active">Update Record</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!--.row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Change User Records</h3>
                            <p class="text-muted m-b-30 font-13"> (Update User Record) </p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="white-box">
                                        <h3 class="box-title m-b-0">Change User Records</h3>
                                        <p class="text-muted m-b-30 font-13">(Update User Record)</p>
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <h2 class="text-center">Update Player Record</h2>
                                                <form method="POST" action="" class="form-horizontal">
                                                    <div class="form-group">
                                                        <label for="player_name" class="col-sm-3 control-label">Player Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="player_name" name="player_name" class="form-control" value="<?php echo $row['player_name']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="dob" class="col-sm-3 control-label">Date of Birth:</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['dob'])); ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="school_name" class="col-sm-3 control-label">School Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="school_name" name="school_name" class="form-control" value="<?php echo $row['school_name']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="class_grade" class="col-sm-3 control-label">Class Grade:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="class_grade" name="class_grade" class="form-control" value="<?php echo $row['class_grade']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="identification_number" class="col-sm-3 control-label">Identification Number:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="identification_number" name="identification_number" class="form-control" value="<?php echo $row['identification_number']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="club" class="col-sm-3 control-label">Club:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="club" name="club" class="form-control" value="<?php echo $row['club']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="player_category" class="col-sm-3 control-label">Player Category:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="player_category" name="player_category" class="form-control" value="<?php echo $row['player_category']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="image_path" class="col-sm-3 control-label">Image Path:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" id="image_path" name="image_path" class="form-control" value="<?php echo $row['image_path']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group text-center">
                                                        <div class="col-sm-offset-3 col-sm-9">
                                                            <input type="submit" value="Update Record" class="btn btn-primary">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
            <footer class="footer text-center"> 2024 &copy; TQS Admin </footer>
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
</body>

</html>