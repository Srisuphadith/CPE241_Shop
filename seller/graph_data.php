<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CPE241_SHOP"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();
$sql_5 = "SELECT W.cateName,R.price_amount FROM tbl_categories W JOIN (SELECT B.cate_ID ,SUM(A.price) price_amount FROM `tbl_transaction_items` A JOIN tbl_products B ON A.product_ID = B.product_ID WHERE B.shop_ID = ? GROUP BY B.cate_ID ORDER BY price_amount DESC) R ON W.cate_ID = R.cate_ID";
$stmt = $conn->prepare($sql_5);
$stmt->bind_param("s",$_SESSION['shop_ID']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_all();

$json_data = json_encode($row);
echo $json_data;

?>
