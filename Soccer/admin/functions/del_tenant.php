<?php
// Start output buffering
ob_start();

require_once "db.php";

if (isset($_POST["deleteTenant"])) {
    // Collecting data
    $tenid = $_POST["tenID"];

    $sq_tenants = "DELETE FROM `players` WHERE `players`.`playerID` = '$tenid'";

    $mysqli->autocommit(FALSE);
    $status = true;

    // Execute queries
    $mysqli->query($sq_tenants) ? null : $status = false;

    if ($status) {
        // Successful, commit changes
        $mysqli->commit();

        // Redirect with success state
        header('Location: ../tenants.php?deleted');
        exit();
    } else {
        // Rollback changes
        $mysqli->rollback();

        // Redirect with error state
        header('Location: ../tenants.php?del_error');
        exit();
    }
} else {
    // Redirect if no deleteTenant POST data
    header('Location: ../tenants.php?del_error');
    exit();
}

// End output buffering
ob_end_flush();
?>
