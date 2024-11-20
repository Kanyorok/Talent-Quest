<?php
// Start output buffering
ob_start();

require_once "db.php";

if (isset($_POST["id"])) {
    $id = $_POST["id"];

    $sql = "DELETE FROM admin WHERE id=?";
    $stmt = $db->prepare($sql);

    try {
        $stmt->execute([$id]);
        header('Location: ../users.php?deleted');
        exit(); // Stop execution after redirect
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    header('Location: ../users.php?del_error');
    exit();
}

// End output buffering
ob_end_flush();
?>
