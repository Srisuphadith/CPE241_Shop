<?php
require_once("../conn_w.php");
// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (isset($_POST['id'])) {
    $coupon_ID = (int)$_POST['id'];
    
    // ตรวจสอบว่ามีคูปองนี้อยู่จริงหรือไม่
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_coupons WHERE coupon_ID = ?");
    $checkStmt->bind_param("i", $coupon_ID);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    
    if ($count == 0) {
        echo "Error: Coupon not found";
        exit;
    }
    
    // เตรียมคำสั่ง SQL สำหรับลบคูปอง
    $stmt = $conn->prepare("DELETE FROM tbl_coupons WHERE coupon_ID = ?");
    $stmt->bind_param("i", $coupon_ID);
    
    // Execute และตอบกลับ
    if ($stmt->execute()) {
        echo "ลบคูปองเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "Error: Missing coupon ID";
}
?>