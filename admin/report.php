<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Dashboard</title>
    <link rel="icon" href="../img/logo.png">
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-w-full bg-[#2E282A] mr-8 ml-8">
    <?php 
    require_once("../navbar/nav_admin.php");
    require_once("../conn_w.php");
    // ดึงข้อมูลจำนวนผู้ใช้งานทั้งหมด
    $userQuery = "SELECT COUNT(*) as total FROM tbl_users WHERE role = 'user'"; 
    $userResult = $conn->query($userQuery);
    $userTotal = $userResult->fetch_assoc()['total'];
    
    // ดึงข้อมูลจำนวนผู้ดูแลทั้งหมด
    $adminQuery = "SELECT COUNT(*) as total FROM tbl_users WHERE role = 'admin'"; 
    $adminResult = $conn->query($adminQuery);
    $adminTotal = $adminResult->fetch_assoc()['total'];

    // ดึงข้อมูลจำนวนผู้ขายทั้งหมด
    $sellerQuery = "SELECT COUNT(*) as total FROM tbl_users WHERE role = 'seller'"; 
    $sellerResult = $conn->query($sellerQuery);
    $sellerTotal = $sellerResult->fetch_assoc()['total'];
    
    // คำนวณจำนวนผู้ใช้งานทั้งหมด (user + seller)
    $grandTotal = $userTotal + $sellerTotal;
    
    // ดึงข้อมูลจำนวนออเดอร์ทั้งหมด
    $orderQuery = "SELECT COUNT(*) as total FROM tbl_transactions"; 
    $orderResult = $conn->query($orderQuery);
    $orderTotal = $orderResult->fetch_assoc()['total'];
    
    // ดึงข้อมูลลูกค้าที่มีการสั่งซื้อมากที่สุด 3 อันดับแรก
    $topCustomersQuery = "SELECT u.userName, COUNT(t.trans_ID) as order_count 
                          FROM tbl_transactions t 
                          JOIN tbl_users u ON t.user_ID = u.user_ID 
                          GROUP BY t.user_ID 
                          ORDER BY order_count DESC 
                          LIMIT 3";
    $topCustomersResult = $conn->query($topCustomersQuery);
    
    // ดึงข้อมูลคูปองที่ถูกใช้งานมากที่สุด 3 อันดับแรก
    $topCouponsQuery = "SELECT c.couponCode, COUNT(t.trans_ID) as usage_count 
                        FROM tbl_transactions t 
                        JOIN tbl_coupons c ON t.coupon_ID = c.coupon_ID 
                        WHERE t.coupon_ID IS NOT NULL 
                        GROUP BY t.coupon_ID 
                        ORDER BY usage_count DESC 
                        LIMIT 3";
    $topCouponsResult = $conn->query($topCouponsQuery);

    // ดึงข้อมูลยอดขายรายเดือนในปีปัจจุบัน
    $currentYear = date('Y');
    $monthlySalesQuery = "SELECT MONTH(date) as month, SUM(grandTotal) as total_sales 
                          FROM tbl_transactions 
                          WHERE YEAR(date) = '$currentYear' AND paid = 1 
                          GROUP BY MONTH(date) 
                          ORDER BY month";
    $monthlySalesResult = $conn->query($monthlySalesQuery);
    
    // สร้าง array สำหรับเก็บข้อมูลยอดขายรายเดือน
    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $monthlySales = array_fill(0, 12, 0); // เริ่มต้นด้วย 0 สำหรับทุกเดือน

    if ($monthlySalesResult->num_rows > 0) {
        while ($row = $monthlySalesResult->fetch_assoc()) {
            $monthIndex = (int)$row['month'] - 1; // เพราะ array เริ่มจาก 0
            $monthlySales[$monthIndex] = (float)$row['total_sales'];
        }
    }
    
    // แปลงเป็น JSON สำหรับใช้ใน JavaScript
    $monthLabels = json_encode($months);
    $salesData = json_encode($monthlySales);
    
    // ดึงข้อมูล top spenders ในเดือนปัจจุบัน
    $currentMonth = date('m');
    $topSpendersQuery = "SELECT u.userName, SUM(t.grandTotal) as total_spent 
                        FROM tbl_transactions t 
                        JOIN tbl_users u ON t.user_ID = u.user_ID 
                        WHERE MONTH(t.date) = '$currentMonth' AND YEAR(t.date) = '$currentYear' AND t.paid = 1 
                        GROUP BY t.user_ID 
                        ORDER BY total_spent DESC 
                        LIMIT 5";
    $topSpendersResult = $conn->query($topSpendersQuery);

    // ดึงข้อมูลจำนวนร้านค้าทั้งหมด
    $shopQuery = "SELECT COUNT(*) as total FROM tbl_shops";
    $shopResult = $conn->query($shopQuery);
    $shopTotal = $shopResult->fetch_assoc()['total'];

    // ดึงข้อมูลยอดขายรวมทั้งหมด
    $totalSalesQuery = "SELECT SUM(grandTotal) as total FROM tbl_transactions WHERE paid = 1";
    $totalSalesResult = $conn->query($totalSalesQuery);
    $totalSales = $totalSalesResult->fetch_assoc()['total'];
    if ($totalSales === null) {
        $totalSales = 0;
    }
    $formattedTotalSales = number_format($totalSales, 2);

    // ดึงข้อมูลยอดขายแยกตามหมวดหมู่รายเดือน
    $categorySalesQuery = "SELECT c.cateName, MONTH(t.date) as month, SUM(ti.quantity * ti.price) as total_sales
                           FROM tbl_transaction_items ti
                           JOIN tbl_transactions t ON ti.trans_ID = t.trans_ID
                           JOIN tbl_products p ON ti.product_ID = p.product_ID
                           JOIN tbl_categories c ON p.cate_ID = c.cate_ID
                           WHERE YEAR(t.date) = '$currentYear' AND t.paid = 1
                           GROUP BY c.cateName, MONTH(t.date)
                           ORDER BY c.cateName, month";
    $categorySalesResult = $conn->query($categorySalesQuery);

    // สร้าง array สำหรับเก็บข้อมูลยอดขายแต่ละหมวดหมู่
    $categories = ['buddhist', 'christian', 'islamic', 'god', 'others'];
    $categorySales = [];
    foreach ($categories as $category) {
        $categorySales[$category] = array_fill(0, 12, 0);
    }

    if ($categorySalesResult && $categorySalesResult->num_rows > 0) {
        while ($row = $categorySalesResult->fetch_assoc()) {
            $category = strtolower($row['cateName']);
            $monthIndex = (int)$row['month'] - 1;
            if (isset($categorySales[$category])) {
                $categorySales[$category][$monthIndex] = (float)$row['total_sales'];
            }
        }
    }

    // แปลงเป็น JSON สำหรับใช้ใน JavaScript
    $categoryLabels = json_encode($categories);
    $categorySalesData = json_encode($categorySales);
    ?>

    <div class="text-center mt-8">
        <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Report Dashboard</h2>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 my-10">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">ผู้ใช้ทั้งหมด</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $grandTotal;  ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">ผู้ซื้อ</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $userTotal; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">ผู้ขาย</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $sellerTotal; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">ผู้ดูแล</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $adminTotal; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">รายการทั้งหมด</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $orderTotal; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-700">รายได้รวม</h3>
            <p class="text-2xl font-bold text-blue-600"><?php echo $formattedTotalSales;  ?></p>
        </div>
    </div>



    <!-- Monthly Sales Chart -->
    <div class="my-10">
        <h2 class="text-2xl font-semibold text-orange-400 mb-4">Monthly Sales (<?php echo $currentYear; ?>)</h2>
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <canvas id="monthlySalesChart" height="100"></canvas>
        </div>
    </div>

    <!-- Category Sales Chart -->
    <div class="my-10">
        <h2 class="text-2xl font-semibold text-orange-400 mb-4">Sales by Category (<?php echo $currentYear; ?>)</h2>
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <canvas id="categorySalesChart" height="100"></canvas>
        </div>
    </div>

    <!-- Top Sections -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 my-12">
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-black mb-4">Top Customers</h2>
            <ul class="list-disc list-inside space-y-2">
                <?php 
                if ($topCustomersResult->num_rows > 0) {
                    while ($customer = $topCustomersResult->fetch_assoc()) {
                        echo "<li>{$customer['userName']} - {$customer['order_count']} Orders</li>";
                    }
                } else {
                    echo "<li>No customer data available</li>";
                }
                ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-black mb-4">Top Coupon</h2>
            <ul class="list-disc list-inside space-y-2">
                <?php 
                if ($topCouponsResult->num_rows > 0) {
                    while ($coupon = $topCouponsResult->fetch_assoc()) {
                        echo "<li>{$coupon['couponCode']} - Used {$coupon['usage_count']} Times</li>";
                    }
                } else {
                    echo "<li>No coupon usage data available</li>";
                }
                ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-xl font-semibold text-black mb-4">Top Spenders (<?php echo date('F Y'); ?>)</h2>
            <ul class="list-disc list-inside space-y-2">
                <?php 
                if ($topSpendersResult->num_rows > 0) {
                    while ($spender = $topSpendersResult->fetch_assoc()) {
                        $formattedAmount = number_format($spender['total_spent'], 2);
                        echo "<li>{$spender['userName']} - ฿{$formattedAmount}</li>";
                    }
                } else {
                    echo "<li>No spender data available for this month</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
        // สร้างกราฟยอดขายรายเดือน
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $monthLabels; ?>,
                datasets: [{
                    label: 'Monthly Sales (฿)',
                    data: <?php echo $salesData; ?>,
                    backgroundColor: [
                        
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderColor: [
                        ,
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: 'black'
                        }
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'black'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'black'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // สร้างกราฟยอดขายตามหมวดหมู่
        const categoryColors = {
            'buddhist': 'rgba(255, 99, 132, 0.7)',   // สีชมพูสำหรับพุทธ
            'christian': 'rgba(54, 162, 235, 0.7)',  // สีฟ้าสำหรับคริสต์
            'islamic': 'rgba(75, 192, 192, 0.7)',   // สีเขียวสำหรับอิสลาม
            'god': 'rgba(255, 206, 86, 0.7)',      // สีเหลืองสำหรับพระเจ้า
            'others': 'rgba(153, 102, 255, 0.7)'    // สีม่วงสำหรับอื่นๆ
        };

        const ctxCategory = document.getElementById('categorySalesChart').getContext('2d');
        
        // เตรียมข้อมูลสำหรับกราฟ
        const categoriesData = <?php echo $categorySalesData; ?>;
        const categoryLabels = <?php echo $categoryLabels; ?>;
        const months = <?php echo $monthLabels; ?>;
        
        // สร้าง datasets สำหรับแต่ละหมวดหมู่
        const datasets = categoryLabels.map(category => {
            return {
                label: category.charAt(0).toUpperCase() + category.slice(1),  // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                data: categoriesData[category],
                backgroundColor: categoryColors[category],
                borderColor: categoryColors[category].replace('0.7', '1'),  // เพิ่มความทึบสำหรับเส้นขอบ
                borderWidth: 1,
                fill: false
            };
        });
        
        const categorySalesChart = new Chart(ctxCategory, {
            type: 'line',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: 'black'
                        }
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ฿' + context.raw.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'black',
                            callback: function(value) {
                                return '฿' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'black'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
