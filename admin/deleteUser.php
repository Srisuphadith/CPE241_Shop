<?php
require_once("../conn_w.php");

// ตรวจสอบว่ามีการส่ง id มาหรือไม่
if (isset($_GET['id'])) {
    // ดึงค่า id และทำให้ปลอดภัยด้วยการแปลงเป็น integer
    $userID = intval($_GET['id']);

    // เตรียมคำสั่ง SQL ด้วย prepared statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("DELETE FROM tbl_address WHERE user_ID = ?");
    $stmt = $conn->prepare("DELETE FROM tbl_users WHERE user_ID = ?");
    $stmt->bind_param("i", $userID);

    // ทำการ execute คำสั่ง SQL
    if ($stmt->execute()) {
        http_response_code(200); // ส่งรหัสสถานะ OK
        echo "success";
    } else {
        http_response_code(500); // ส่งรหัส error
        echo "Error deleting record: " . $stmt->error;
    }

    // ปิด statement
    $stmt->close();
} else {
    http_response_code(400); // Bad request
    echo "No ID specified.";
}
?>
