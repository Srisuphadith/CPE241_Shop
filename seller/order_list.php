<?php
session_start();
require_once('../conn.php');

// Redirect if not logged in or not a seller
if (!isset($_SESSION["userID"]) || $_SESSION["role"] !== "seller") {
    header("Location: ../auth/sign_in.php");
    exit;
}

// Get seller's shop ID
$seller_id = $_SESSION["userID"];
$shop_sql = "SELECT shop_ID FROM tbl_shops WHERE user_ID = ?";
$stmt = $conn->prepare($shop_sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$shop_result = $stmt->get_result();
$shop = $shop_result->fetch_assoc();
$shop_id = $shop['shop_ID'];

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $update_sql = "UPDATE tbl_orders SET status = ? WHERE order_ID = ? AND shop_ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sii", $status, $order_id, $shop_id);
    $update_stmt->execute();
}

// Get orders for this shop
$sql = "SELECT o.order_ID, o.orderDate, o.totalAmount, o.status, u.firstName, u.lastName,
        GROUP_CONCAT(CONCAT(p.productName, ' (', od.quantity, ')') SEPARATOR ', ') as products
        FROM tbl_orders o
        JOIN tbl_orderdetails od ON o.order_ID = od.order_ID
        JOIN tbl_products p ON od.product_ID = p.product_ID
        JOIN tbl_users u ON o.user_ID = u.user_ID
        WHERE p.shop_ID = ?
        GROUP BY o.order_ID
        ORDER BY o.orderDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>

    <div class="max-w-6xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-white mb-6">Order List</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-lg font-semibold">Order #<?php echo $order['order_ID']; ?></h2>
                                <p class="text-gray-600">Date: <?php echo date('F j, Y', strtotime($order['orderDate'])); ?></p>
                                <p class="text-gray-600">Customer: <?php echo htmlspecialchars($order['firstName'] . ' ' . $order['lastName']); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold">฿<?php echo number_format($order['totalAmount'], 2); ?></p>
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_ID']; ?>">
                                    <select name="status" onchange="this.form.submit()" class="px-3 py-1 rounded-full text-sm
                                        <?php
                                        switch($order['status']) {
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'processing':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'completed':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'cancelled':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="border-t pt-4">
                            <h3 class="font-medium mb-2">Products:</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($order['products']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600">No orders found.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
