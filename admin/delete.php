<?php
require_once("../conn.php");

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    $sql = "DELETE FROM tbl_users WHERE user_ID = $userID";

    if ($conn->query($sql) === TRUE) {
        http_response_code(200); // ส่งรหัสสถานะ OK
        echo "success";
    } else {
        http_response_code(500); // ส่งรหัส error
        echo "Error deleting record: " . $conn->error;
    }
} else {
    http_response_code(400); // Bad request
    echo "No ID specified.";
}
?>
