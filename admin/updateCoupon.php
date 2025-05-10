<?php
require_once("../conn.php"); // เชื่อมต่อ DB

// ตรวจสอบข้อมูลที่ส่งมา
if (
    isset($_POST['id']) &&
    isset($_POST['couponCode']) &&
    isset($_POST['discount']) &&
    isset($_POST['minOrderValue']) &&
    isset($_POST['remain']) &&
    isset($_POST['expDate'])
) {
    // เตรียมข้อมูล
    $coupon_ID = (int)$_POST['id'];
    $couponCode = trim($_POST['couponCode']);
    $discount = (int)$_POST['discount'];
    $minOrderValue = (int)$_POST['minOrderValue'];
    $remain = (int)$_POST['remain'];
    $expDate = $_POST['expDate'];
    
    // ตรวจสอบรูปแบบข้อมูล
    if (empty($couponCode)) {
        echo "Error: Coupon code cannot be empty";
        exit;
    }
    
    if ($discount < 0 || $discount > 100) {
        echo "Error: Discount must be between 0 and 100";
        exit;
    }
    
    if ($minOrderValue < 0) {
        echo "Error: Minimum order value cannot be negative";
        exit;
    }
    
    if ($remain < 0) {
        echo "Error: Remain count cannot be negative";
        exit;
    }
    
    // ตรวจสอบวันที่
    $currentDate = date('Y-m-d');
    if ($expDate < $currentDate) {
        echo "Error: Expiration date must be today or in the future";
        exit;
    }
    
    // ตรวจสอบว่ามีรหัสคูปองซ้ำหรือไม่ (ยกเว้นรหัสของตัวเอง)
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_coupons WHERE couponCode = ? AND coupon_ID != ?");
    $checkStmt->bind_param("si", $couponCode, $coupon_ID);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    
    if ($count > 0) {
        echo "Error: Coupon code already exists";
        exit;
    }

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("UPDATE tbl_coupons SET couponCode = ?, discount = ?, minOrderValue = ?, remain = ?, expDate = ? WHERE coupon_ID = ?");
    $stmt->bind_param("siiisi", $couponCode, $discount, $minOrderValue, $remain, $expDate, $coupon_ID);

    // Execute และตอบกลับ
    if ($stmt->execute()) {
        echo "อัปเดตข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Missing required fields";
}
?>