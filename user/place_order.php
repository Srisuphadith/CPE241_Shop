<?php
session_start();
require_once("../conn.php");

if (!isset($_SESSION["userID"])) {
    // header("Location: ../auth/sign_in.php");
    exit();
}

// echo '<pre>';
// print_r($_SESSION);
// print_r($_POST);
// echo '</pre>';
// if ($_SERVER["REQUEST_METHOD"] != "POST") {
//     header("Location: cart.php");
//     exit();
// }

$userID = $_SESSION['userID'];
$selected_items = $_POST['selected_items'] ?? [];
$items_quantity = $_POST['pQ'] ?? [];
$items_price = $_POST['pP'] ?? [];
$shipping_address = $_POST['shipping_address'] ?? '';
$coupon_code = $_SESSION['applied_coupon']['code'] ?? '';
$total = $_POST['total'] ?? 0;
$subtotal = $_POST['sub_total'] + 29 ?? 0;

echo "ID : ".$userID . '<br>';

for ($i = 0; $i < count($selected_items); $i++) {
    echo "ITEMS".$i." : ".$selected_items[$i] . ' - ' . $items_quantity[$i] . "-" . $items_price . '<br>';
}
echo "Total : ".$total . '<br>';
echo "Shipping : ".$shipping_address . '<br>';
echo "Coupon : ".$coupon_code . '<br>';


// if (empty($selected_items) || empty($payment_method)) {
//     $_SESSION['error'] = "Please fill in all required fields.";
//     header("Location: billing.php");
//     exit();
// }

try {
    // Start transaction
    $conn->begin_transaction();
    for ($i = 0; $i < count($selected_items); $i++) {
        $product_ID = $selected_items[$i];
        $quantity = $items_quantity[$i];
        $price = $items_price[$i];
        $trans_sql = "INSERT INTO tbl_transactions_items (product_ID, quantity, price) 
                    VALUES (?, ?, ?)";
        $trans_stmt = $conn->prepare($trans_sql);
        $trans_stmt->bind_param("iid", $product_ID, $quantity, $price);
        $trans_stmt->execute();
        $trans_stmt->close();

        $sold = "UPDATE tbl_products_stats SET sold = sold + ? WHERE product_ID = ?";
        $sold_stmt = $conn->prepare($sold);
        $sold_stmt->bind_param("is", $quantity, $product_ID);
        $sold_stmt->execute();
        $sold_stmt->close();

        $stock = "UPDATE tbl_products SET stock_quantity = stock_quantity - ? WHERE product_ID = ?";
        $stock_stmt = $conn->prepare($stock);
        $stock_stmt->bind_param("is", $quantity, $product_ID);
        $stock_stmt->execute();
        $stock_stmt->close();

        $cart_sql = "DELETE FROM tbl_carts WHERE user_ID = ? AND product_ID = ?";
        $cart_stmt = $conn->prepare($cart_sql);
        $cart_stmt->bind_param("ss", $userID, $product_ID);
        $cart_stmt->execute();
        $cart_stmt->close();
    }
    
    // Create order
    $order_sql = "INSERT INTO tbl_transactions (user_ID, sumPrice, coupon_ID, grandTotal, paid, transport_state) 
                VALUES (?, ?, ?, ?, 0, 'Pending')";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("sdsd", $userID, $subtotal, $coupon_code, $total); // last one is double
    $order_stmt->execute();
    $orderID = $conn->insert_id;
    $order_stmt->close();
    $conn->commit();

    $_SESSION['success'] = "Order placed successfully!";
    header("Location: order_success.php?order_id=" . $orderID);
    exit();
    
}catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
    exit();
}
?> 