<?php 

// Database Connection
require_once "db.php";

// UPDATE PASSWORD
if (isset($_POST['submit'])) {

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));
    $email = $_POST["email"];

    // Prepare the SQL query with placeholders
    $sql = "UPDATE admin SET password = :password WHERE email = :email";

    $stmt = $db->prepare($sql);

    try {
        // Bind parameters and execute the statement
        $stmt->execute(['password' => $password, 'email' => $email]);
        header('Location: ../tenants.php?state=3.5');
        exit(); // Make sure to exit after header redirection
    } catch (Exception $e) {
        // Display the error message
        echo "Error: " . $e->getMessage();
    }
}
?>
