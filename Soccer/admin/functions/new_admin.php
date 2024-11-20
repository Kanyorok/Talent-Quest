<?php
// Start output buffering
ob_start();

/* DATABASE CONNECTION */
require "db.php";
/* DATABASE CONNECTION */

if (isset($_POST['submit'])) {
    //-- Get Employee Data --//
    $email = is_email($_POST['email']);
    $uname = is_username($_POST['uname']);
    $role = uncrack($_POST['role']);

    // CHECK IF EMPLOYEE EMAIL EXISTS //
    $sql = "SELECT `id` FROM `admin` WHERE `email` = '$email'";
    $stmt = mysqli_query($conn, $sql);

    if (mysqli_num_rows($stmt) > 0) {
        // email already EXISTS
        echo "Oops...This email already exists!";
        exit(); // Stop script to avoid header issues
    }
    // END OF - CHECK IF EMPLOYEE EMAIL EXISTS //

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        echo $emailErr;
        exit(); // Stop script to avoid header issues
    }

    //-- Insert Data Into DB --//
    $sql = "INSERT INTO `admin` (`email`, `password`, `name`, `role`)
            VALUES ('$email', '$password', '$uname', '$role')";

    // Use mysqli to execute
    try {
        $stmt = mysqli_query($conn, $sql);

        if ($stmt) {
            header('Location: ../users.php?added');
            exit(); // Always exit after header
        } else {
            echo "Database insert failed!";
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}

// End output buffering
ob_end_flush();
?>
