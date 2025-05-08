<?php
require_once("../navbar/nav_user.php");
require_once("../conn.php");
session_start();
$userID = $_SESSION['userID'];

// Get POST data
$address = $_POST['address_ID'] ?? null;
$payment = $_POST['payment_method'] ?? null;
$coupon = $_POST['coupon'] ?? null;

// 1. Calculate totals from cart
$cart_sql = "SELECT c.product_ID, c.quantity, p.price FROM tbl_carts c JOIN tbl_products p ON c.product_ID = p.product_ID WHERE c.user_ID = ?";
$stmt = $conn->prepare($cart_sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$cart_result = $stmt->get_result();

$sumPrice = 0;
$cartItems = [];
while ($row = $cart_result->fetch_assoc()) {
    $cartItems[] = $row;
    $sumPrice += $row['price'] * $row['quantity'];
}
$shipping = 29;
$grandTotal = $sumPrice + $shipping;

// 2. Insert transaction
$trans_sql = "INSERT INTO tbl_transactions (user_ID, sumPrice, grandTotal, paid, transport_state) VALUES (?, ?, ?, 0, 'Pending')";
$trans_stmt = $conn->prepare($trans_sql);
$trans_stmt->bind_param("idd", $userID, $sumPrice, $grandTotal);
$trans_stmt->execute();
$trans_ID = $conn->insert_id;

// 3. Insert transaction items
$item_sql = "INSERT INTO tbl_transaction_items (trans_ID, product_ID, quantity, price) VALUES (?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_sql);
foreach ($cartItems as $item) {
    $item_stmt->bind_param("iiid", $trans_ID, $item['product_ID'], $item['quantity'], $item['price']);
    $item_stmt->execute();
}

// 4. Clear cart
$clear_sql = "DELETE FROM tbl_carts WHERE user_ID = ?";
$clear_stmt = $conn->prepare($clear_sql);
$clear_stmt->bind_param("i", $userID);
$clear_stmt->execute();

// Here you would process the order, e.g., insert into tbl_transactions, clear cart, etc.
// For now, just show a confirmation message.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Mongkol</title>
</head>
<body class="m-4 bg-soft-black">
    <div class="max-w-xl mx-auto mt-20 bg-soft-white p-8 rounded-lg shadow text-center">
        <h1 class="poppins-font text-3xl font-bold mb-4 text-green-600">Order Confirmed!</h1>
        <p class="poppins-font text-lg mb-6">Thank you for your purchase. Your order has been placed successfully.</p>
        <a href="market.php" class="inline-block bg-orange-500 text-white rounded px-6 py-3 text-lg font-bold hover:bg-orange-600 transition">Back to Market</a>
    </div>
</body>
</html> 