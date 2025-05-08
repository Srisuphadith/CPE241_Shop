<?php
session_start();
require_once('../conn.php');

// Redirect if not logged in
if (!isset($_SESSION["userID"])) {
    header("Location: ../auth/login.php");
    exit;
}

// Get user's orders
$user_id = $_SESSION["userID"];
$sql = "SELECT o.order_ID, o.orderDate, o.totalAmount, o.status,
        GROUP_CONCAT(CONCAT(p.productName, ' (', od.quantity, ')') SEPARATOR ', ') as products
        FROM tbl_orders o
        JOIN tbl_orderdetails od ON o.order_ID = od.order_ID
        JOIN tbl_products p ON od.product_ID = p.product_ID
        WHERE o.user_ID = ?
        GROUP BY o.order_ID
        ORDER BY o.orderDate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>

    <div class="max-w-6xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-white mb-6">Order History</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-lg font-semibold">Order #<?php echo $order['order_ID']; ?></h2>
                                <p class="text-gray-600">Date: <?php echo date('F j, Y', strtotime($order['orderDate'])); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold">฿<?php echo number_format($order['totalAmount'], 2); ?></p>
                                <span class="px-3 py-1 rounded-full text-sm
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
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
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
                <p class="text-gray-600">You haven't placed any orders yet.</p>
                <a href="market.php" class="inline-block mt-4 bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                    Start Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 