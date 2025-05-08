<?php
require_once("../conn.php");

if (isset($_POST['id'])) {
    $coupon_ID = $_POST['id'];
    $couponCode = $_POST['couponCode'];
    $discount = $_POST['discount'];
    $minOrderValue = $_POST['minOrderValue'];
    $remain = $_POST['remain'];
    $expDate = $_POST['expDate'];

    // เตรียมคำสั่ง SQL
    $sql = "UPDATE tbl_coupons 
            SET couponCode = ?, discount = ?, minOrderValue = ?, remain = ?, expDate = ? 
            WHERE coupon_ID = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error;
        exit;
    }

    // ผูก parameter (string, string, double, int, string, int)
    $stmt->bind_param("sddisi", $couponCode, $discount, $minOrderValue, $remain, $expDate, $coupon_ID);

    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดต: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>
