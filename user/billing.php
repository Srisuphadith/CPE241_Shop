<?php
session_start();
require_once("../conn.php");

if (!isset($_SESSION["userID"])) {
    header("Location: ../auth/sign_in.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST['submitForm'])) {
    $userID = $_SESSION['userID'];
    $selected_items = $_POST['selected_items'] ?? [];
    $items_quantity = $_POST['pQ'] ?? [];
    $items_price = $_POST['pP'] ?? [];
    $shipping_address = $_POST['shipping_address'] ?? '';
    $coupon_code = $_SESSION['applied_coupon']['code'] ?? '';
    $total = $_POST['total'] ?? 0;
    $subtotal = isset($_POST['subtotal']) ? ($_POST['subtotal'] + 29) : 0;
    // Start transaction
    $conn->begin_transaction();

    $coupon_ID = null;
    if (!empty($coupon_code)) {
        $coupon_sql = "SELECT coupon_ID FROM tbl_coupons WHERE couponCode = ?";
        $stmt = $conn->prepare($coupon_sql);
        $stmt->bind_param("s", $coupon_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $coupon_ID = $result->fetch_assoc()['coupon_ID'] ?? null;
    }
    
    if ($coupon_ID !== null) {
        $update_coupon = "UPDATE tbl_coupons SET remain = remain - 1 WHERE coupon_ID = ?";
        $coupon_stmt = $conn->prepare($update_coupon);
        $coupon_stmt->bind_param("i", $coupon_ID);
        $coupon_stmt->execute();
        $coupon_stmt->close();
    }


    // Create order
    $order_sql = "INSERT INTO tbl_transactions (user_ID, sumPrice, coupon_ID, grandTotal, paid, transport_state) 
                VALUES (?, ?, ?, ?, 0, 'Pending')";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("idid", $userID, $subtotal, $coupon_ID, $total);
    $order_stmt->execute();
    $orderID = $conn->insert_id;
    $order_stmt->close();

    for ($i = 0; $i < count($selected_items); $i++) {
        $product_ID = $selected_items[$i];
        $quantity = $items_quantity[$i];
        $price = $items_price[$i];
        
        $trans_sql = "INSERT INTO tbl_transaction_items (trans_ID, product_ID, quantity, price) VALUES (?, ?, ?, ?)";
        $trans_stmt = $conn->prepare($trans_sql);
        $trans_stmt->bind_param("iiid", $orderID, $product_ID, $quantity, $price);
        $trans_stmt->execute();
        $trans_stmt->close();

        // Fixed table name from tbl_products_stats to tbl_product_stats (matching your schema)
        $sold_check_sql = "SELECT COUNT(*) as count FROM tbl_product_stats WHERE product_ID = ?";
        $sold_check_stmt = $conn->prepare($sold_check_sql);
        if (!$sold_check_stmt) {
            // Handle error - print the mysqli error
            echo "Error preparing statement: " . $conn->error;
            $conn->rollback();
            exit();
        }
        $sold_check_stmt->bind_param("i", $product_ID);
        $sold_check_stmt->execute();
        $sold_result = $sold_check_stmt->get_result();
        $sold_column = $sold_result->fetch_assoc();
        $sold_check_stmt->close();
        
        if ($sold_column['count'] == 0) {
            // Fixed table name from tbl_products_stats to tbl_product_stats
            $insert_sql = "INSERT INTO tbl_product_stats (product_ID, numSold) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            if (!$insert_stmt) {
                echo "Error preparing insert statement: " . $conn->error;
                $conn->rollback();
                exit();
            }
            $insert_stmt->bind_param("ii", $product_ID, $quantity);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        
        // Fixed table name from tbl_products_stats to tbl_product_stats
        $sold = "UPDATE tbl_product_stats SET numSold = numSold + ? WHERE product_ID = ?";
        $sold_stmt = $conn->prepare($sold);
        if (!$sold_stmt) {
            echo "Error preparing update statement: " . $conn->error;
            $conn->rollback();
            exit();
        }
        $sold_stmt->bind_param("ii", $quantity, $product_ID);
        $sold_stmt->execute();
        $sold_stmt->close();

        // Changed stock_quantity to quantity to match your schema
        $stock = "UPDATE tbl_products SET quantity = quantity - ? WHERE product_ID = ?";
        $stock_stmt = $conn->prepare($stock);
        if (!$stock_stmt) {
            echo "Error preparing stock update statement: " . $conn->error;
            $conn->rollback();
            exit();
        }
        $stock_stmt->bind_param("ii", $quantity, $product_ID);
        $stock_stmt->execute();
        $stock_stmt->close();

        $cart_sql = "DELETE FROM tbl_carts WHERE user_ID = ? AND product_ID = ?";
        $cart_stmt = $conn->prepare($cart_sql);
        $cart_stmt->bind_param("ii", $userID, $product_ID);
        $cart_stmt->execute();
        $cart_stmt->close();
    }
    $conn->commit();

    $_SESSION['success'] = "Order placed successfully!";
    header("Location: order_success.php?order_id=" . $orderID);
    exit();
}

$userID = $_SESSION['userID'];
$selected_items = $_POST['selected_items'] ?? [];
$coupon_code = $_SESSION['applied_coupon']['code'] ?? '';

if (empty($selected_items)) {
    header("Location: cart.php");
    exit();
}

// Fetch coupon if applied
$discount = 0;
if (!empty($coupon_code)) {
    $coupon_sql = "SELECT * FROM tbl_coupons WHERE couponCode = ? AND expDate > NOW() AND remain > 0 AND is_delete = 0";
    $coupon_stmt = $conn->prepare($coupon_sql);
    $coupon_stmt->bind_param("s", $coupon_code);
    $coupon_stmt->execute();
    $coupon_result = $coupon_stmt->get_result();
    
    if ($coupon_result->num_rows > 0) {
        $coupon = $coupon_result->fetch_assoc();
        $discount = $coupon['discount'];
    }
}

// Fetch selected cart items
$placeholders = implode(',', array_fill(0, count($selected_items), '?'));
$types = str_repeat('s', count($selected_items));
$stmt = $conn->prepare("SELECT c.product_ID, c.quantity, p.productName, p.price, p.imgPath
        FROM tbl_carts c
        JOIN tbl_products p ON c.product_ID = p.product_ID
        WHERE c.user_ID = ? AND c.product_ID IN ($placeholders)");
$stmt->bind_param("s" . $types, $userID, ...$selected_items);
$stmt->execute();
$result = $stmt->get_result();
$items = [];
$subtotal = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
}

// Fetch user addresses
$addr_sql = "SELECT * FROM tbl_address WHERE user_ID = ?";
$addr_stmt = $conn->prepare($addr_sql);
$addr_stmt->bind_param("s", $userID);
$addr_stmt->execute();
$addr_result = $addr_stmt->get_result();
$addresses = [];

while ($row = $addr_result->fetch_assoc()) {
    $addresses[] = $row;
}

$shipping = 29;
$discount_amount = ($subtotal * $discount) / 100;
$total = $subtotal + $shipping - $discount_amount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>

    <form action="billing.php" method="POST" class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-white mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Shipping & Payment -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                    
                    <?php if (empty($addresses)): ?>
                        <p class="text-gray-600 mb-4">No addresses found. Please add a shipping address.</p>
                        <a href="manage_address.php" class="inline-block px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                            Add Address
                        </a>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($addresses as $addr): ?>
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="shipping_address" value="<?php echo $addr['buildingNumber']; ?>" 
                                           class="mt-1" <?php echo $addr['is_primary'] ? 'checked' : ''; ?>>
                                    <div class="ml-3">
                                        <p class="font-medium">
                                            <?php echo htmlspecialchars($addr['buildingNumber']); ?>, 
                                            <?php echo htmlspecialchars($addr['subdistrict']); ?>, 
                                            <?php echo htmlspecialchars($addr['district']); ?>, 
                                            <?php echo htmlspecialchars($addr['province']); ?>, 
                                            <?php echo htmlspecialchars($addr['zip_code']); ?>
                                        </p>
                                        <?php if ($addr['txt']): ?>
                                            <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($addr['txt']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <a href="manage_address.php" class="inline-block mt-4 text-orange-500 hover:text-orange-600">
                            Manage Addresses
                        </a>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <?php foreach ($items as $item): ?>
                            <div class="flex items-center">
                                <img src="../<?php echo htmlspecialchars($item['imgPath']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['productName']); ?>" 
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="ml-4 flex-grow">
                                    <p class="font-medium"><?php echo htmlspecialchars($item['productName']); ?></p>
                                    <span class="text-sm text-gray-600">QTY: </span>
                                    <input name="pQ[]" class="text-sm text-gray-600 w-8" value="<?php echo $item['quantity']; ?>" readonly>
                                    <input type="hidden" name="pP[]" value="<?php echo $item['price']; ?>">
                                </div>
                                <p class="font-medium">฿<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>฿<?php echo number_format($subtotal, 2); ?></span>
                            <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span>฿<?php echo number_format($shipping, 2); ?></span>
                        </div>
                        <?php if ($discount > 0): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount (<?php echo $discount; ?>%)</span>
                                <span id="discount-amount" class="font-semibold text-green-600">-฿<?php echo number_format($discount_amount, 2); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="border-t pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold">Total</span>
                                <span id="total" class="text-lg font-semibold">฿<?php echo number_format($total, 2); ?></span>
                                <input type="hidden" name="total" value="<?php echo $total; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <?php foreach ($selected_items as $pid): ?>
                            <input type="hidden" name="selected_items[]" value="<?php echo $pid; ?>">
                        <?php endforeach; ?>
                        <?php if (!empty($coupon_code)): ?>
                            <input type="hidden" name="coupon_code" value="<?php echo htmlspecialchars($coupon_code); ?>">
                        <?php endif; ?>
                        <button type="submit" name="submitForm" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>