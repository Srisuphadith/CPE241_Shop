<?php
require_once("../conn_w.php");
// รับข้อมูล JSON ที่ส่งมาจาก fetch
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบข้อมูล
if (
    isset($data['couponCode']) &&
    isset($data['discount']) &&
    isset($data['minOrderValue']) &&
    isset($data['remain']) &&
    isset($data['expDate'])
) {
    // เตรียมข้อมูล
    $couponCode = trim($data['couponCode']);
    $discount = (int)$data['discount'];
    $minOrderValue = (int)$data['minOrderValue'];
    $remain = (int)$data['remain'];
    $expDate = $data['expDate'];
    
    // ตรวจสอบรูปแบบข้อมูล
    if (empty($couponCode)) {
        http_response_code(400);
        echo "error: Coupon code cannot be empty";
        exit;
    }
    
    if ($discount < 0 || $discount > 100) {
        http_response_code(400);
        echo "error: Discount must be between 0 and 100";
        exit;
    }
    
    if ($minOrderValue < 0) {
        http_response_code(400);
        echo "error: Minimum order value cannot be negative";
        exit;
    }
    
    if ($remain < 1) {
        http_response_code(400);
        echo "error: Remain count must be at least 1";
        exit;
    }
    
    // ตรวจสอบวันที่
    $currentDate = date('Y-m-d');
    if ($expDate < $currentDate) {
        http_response_code(400);
        echo "error: Expiration date must be today or in the future";
        exit;
    }
    
    // ตรวจสอบว่ามีรหัสคูปองซ้ำหรือไม่
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_coupons WHERE couponCode = ?");
    $checkStmt->bind_param("s", $couponCode);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();
    
    if ($count > 0) {
        http_response_code(400);
        echo "error: Coupon code already exists";
        exit;
    }

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("INSERT INTO tbl_coupons (couponCode, discount, minOrderValue, remain, expDate) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiis", $couponCode, $discount, $minOrderValue, $remain, $expDate);

    // Execute และตอบกลับ
    if ($stmt->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo "error: " . $stmt->error;
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo "error: missing required fields";
}

?>