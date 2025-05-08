<?php
session_start();
require_once('../conn.php');

// Redirect if not logged in or not an admin
if (!isset($_SESSION["userID"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/sign_in.php");
    exit;
}

// Get date range from query parameters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Get sales statistics
$sales_sql = "SELECT 
    COUNT(DISTINCT o.order_ID) as total_orders,
    SUM(o.totalAmount) as total_revenue,
    AVG(o.totalAmount) as average_order_value,
    COUNT(DISTINCT o.user_ID) as unique_customers
    FROM tbl_orders o
    WHERE o.orderDate BETWEEN ? AND ?";
$stmt = $conn->prepare($sales_sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$sales_stats = $stmt->get_result()->fetch_assoc();

// Get top selling products
$products_sql = "SELECT 
    p.productName,
    SUM(od.quantity) as total_quantity,
    SUM(od.quantity * od.price) as total_revenue
    FROM tbl_orderdetails od
    JOIN tbl_products p ON od.product_ID = p.product_ID
    JOIN tbl_orders o ON od.order_ID = o.order_ID
    WHERE o.orderDate BETWEEN ? AND ?
    GROUP BY p.product_ID
    ORDER BY total_quantity DESC
    LIMIT 5";
$stmt = $conn->prepare($products_sql);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$top_products = $stmt->get_result();

// Get user statistics
$users_sql = "SELECT 
    COUNT(*) as total_users,
    SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as regular_users,
    SUM(CASE WHEN role = 'seller' THEN 1 ELSE 0 END) as sellers
    FROM tbl_users";
$users_stats = $conn->query($users_sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>

    <div class="max-w-6xl mx-auto mt-8">
        <h1 class="text-2xl font-bold text-white mb-6">Sales Report</h1>

        <!-- Date Range Filter -->
        <form method="GET" class="mb-8 bg-white p-4 rounded-lg shadow-md">
            <div class="flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 transition">
                    Update Report
                </button>
            </div>
        </form>

        <!-- Sales Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Orders</h3>
                <p class="text-2xl font-bold text-orange-500"><?php echo number_format($sales_stats['total_orders']); ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Total Revenue</h3>
                <p class="text-2xl font-bold text-orange-500">฿<?php echo number_format($sales_stats['total_revenue'], 2); ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Average Order Value</h3>
                <p class="text-2xl font-bold text-orange-500">฿<?php echo number_format($sales_stats['average_order_value'], 2); ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700">Unique Customers</h3>
                <p class="text-2xl font-bold text-orange-500"><?php echo number_format($sales_stats['unique_customers']); ?></p>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Top Selling Products</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($product = $top_products->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($product['productName']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($product['total_quantity']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">฿<?php echo number_format($product['total_revenue'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">User Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                    <p class="text-2xl font-bold text-orange-500"><?php echo number_format($users_stats['total_users']); ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Regular Users</h3>
                    <p class="text-2xl font-bold text-orange-500"><?php echo number_format($users_stats['regular_users']); ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Sellers</h3>
                    <p class="text-2xl font-bold text-orange-500"><?php echo number_format($users_stats['sellers']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>