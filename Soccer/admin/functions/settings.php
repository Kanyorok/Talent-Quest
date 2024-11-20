<?php
// Start output buffering
ob_start();

// Database Connection
require_once "db.php";

// Update Password
if (isset($_POST['submit'])) {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $email = $_POST['email'];

    // Prepare the SQL query with placeholders
    $sql = "UPDATE admin SET password = :password WHERE email = :email";

    $stmt = $db->prepare($sql);

    try {
        // Bind parameters and execute the statement
        $stmt->execute(['password' => $password, 'email' => $email]);

        // Redirect to the tenants page with a success state
        header('Location: ../tenants.php?state=3.5');
        exit();
    } catch (Exception $e) {
        // Log the error and display an error message for debugging
        error_log("Error updating password: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
    }
}

// End output buffering
ob_end_flush();
?>
