<?php
require_once("../conn_w.php");


if (isset($_POST['id'])) {
    $userID = $_POST['id'];
    $firstName = $_POST['firstName'];
    $midName = $_POST['midName'];
    $lastName = $_POST['lastName'];
    $role = $_POST['role'];

    $sql = "UPDATE tbl_users SET firstName=?, midName=?, lastName=?, role=? WHERE user_ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $firstName, $midName, $lastName, $role, $userID);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>
