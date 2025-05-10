<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Management</title>
</head>

<body class="bg-w-full bg-[#2E282A] mr-8 ml-8">
  <?php 
    require_once("../navbar/nav_admin.php");
    require_once("../conn.php"); 
  ?>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Payment Management</h2>
    
    <!-- Search and Filter Form -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
      <form method="GET" class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="searchUsername">
            ค้นหาตาม Username
          </label>
          <input type="text" name="searchUsername" id="searchUsername" 
                placeholder="Username ลูกค้า"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="<?php echo isset($_GET['searchUsername']) ? htmlspecialchars($_GET['searchUsername']) : ''; ?>">
        </div>
        
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="paymentStatus">
            สถานะการชำระเงิน
          </label>
          <select name="paymentStatus" id="paymentStatus"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="">ทั้งหมด</option>
            <option value="paid" <?php echo (isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 'paid') ? 'selected'  : ''; ?>>ชำระแล้ว</option>
            <option value="unpaid" <?php echo (isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 'unpaid') ? 'selected' : ''; ?>>ยังไม่ชำระ</option>
          </select>
        </div>
        
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="transportStatus">
            สถานะการขนส่ง
          </label>
          <select name="transportStatus" id="transportStatus"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="">ทั้งหมด</option>
            <option value="pending" <?php echo (isset($_GET['transportStatus']) && $_GET['transportStatus'] == 'pending') ? 'selected' : ''; ?>>รอดำเนินการ</option>
            <option value="processing" <?php echo (isset($_GET['transportStatus']) && $_GET['transportStatus'] == 'processing') ? 'selected' : ''; ?>>กำลังจัดส่ง</option>
            <option value="delivered" <?php echo (isset($_GET['transportStatus']) && $_GET['transportStatus'] == 'delivered') ? 'selected' : ''; ?>>จัดส่งแล้ว</option>
            <option value="canceled" <?php echo (isset($_GET['transportStatus']) && $_GET['transportStatus'] == 'canceled') ? 'selected' : ''; ?>>ยกเลิก</option>
          </select>
        </div>
        
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="startDate">
            วันที่เริ่มต้น
          </label>
          <input type="date" name="startDate" id="startDate" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="<?php echo isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : ''; ?>">
        </div>
        
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="endDate">
            วันที่สิ้นสุด
          </label>
          <input type="date" name="endDate" id="endDate" 
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="<?php echo isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : ''; ?>">
        </div>
        
        <div class="flex items-end">
          <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            ค้นหา
          </button>
          <a href="payment.php" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            ล้างการค้นหา
          </a>
        </div>
      </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid md:grid-cols-4 gap-4 mb-6">
      <?php
        // Summary statistics queries
        $totalQuery = "SELECT COUNT(*) as total FROM tbl_transactions";
        $totalResult = $conn->query($totalQuery);
        $totalRow = $totalResult->fetch_assoc();
        
        $paidQuery = "SELECT COUNT(*) as paid FROM tbl_transactions WHERE paid = 1";
        $paidResult = $conn->query($paidQuery);
        $paidRow = $paidResult->fetch_assoc();
        
        $unpaidQuery = "SELECT COUNT(*) as unpaid FROM tbl_transactions WHERE paid = 0";
        $unpaidResult = $conn->query($unpaidQuery);
        $unpaidRow = $unpaidResult->fetch_assoc();
        
        $totalAmountQuery = "SELECT SUM(grandTotal) as total_amount FROM tbl_transactions WHERE paid = 1";
        $totalAmountResult = $conn->query($totalAmountQuery);
        $totalAmountRow = $totalAmountResult->fetch_assoc();
      ?>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">รายการทั้งหมด</h3>
        <p class="text-2xl font-bold text-blue-600"><?php echo number_format($totalRow['total']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ชำระแล้ว</h3>
        <p class="text-2xl font-bold text-green-600"><?php echo number_format($paidRow['paid']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ยังไม่ชำระ</h3>
        <p class="text-2xl font-bold text-red-600"><?php echo number_format($unpaidRow['unpaid']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">รายได้รวม</h3>
        <p class="text-2xl font-bold text-green-600">฿<?php echo number_format($totalAmountRow['total_amount'] ?? 0, 2); ?></p>
      </div>
    </div>
    
    <!-- Payment Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              รหัสลูกค้า
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Username
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              ชื่อลูกค้า
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              สถานะการขนส่ง
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              ยอดรวม
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              สถานะการชำระเงิน
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              วันที่ทำรายการ
            </th>
            <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              การจัดการ
            </th> -->
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php
            // Base query - fixed column name from transaction_ID to trans_ID
            $sql = "SELECT U.user_ID, U.userName, CONCAT(COALESCE(U.firstName, ''), ' ', COALESCE(U.midName, ''), ' ', COALESCE(U.lastName, '')) AS fullname,
                   T.trans_ID, T.transport_state, T.grandTotal, T.paid, T.date
                   FROM tbl_users AS U
                   JOIN tbl_transactions AS T ON U.user_ID = T.user_ID";
            
            // Filter conditions
            $conditions = [];
            $params = [];
            $types = "";
            
            // Filter by username
            if (isset($_GET['searchUsername']) && !empty($_GET['searchUsername'])) {
                $conditions[] = "U.userName LIKE ?";
                $searchUsername = '%' . $_GET['searchUsername'] . '%';
                $params[] = $searchUsername;
                $types .= "s";
            }
            
            // Filter by payment status
            if (isset($_GET['paymentStatus']) && !empty($_GET['paymentStatus'])) {
                if ($_GET['paymentStatus'] == 'paid') {
                    $conditions[] = "T.paid = 1";
                } elseif ($_GET['paymentStatus'] == 'unpaid') {
                    $conditions[] = "T.paid = 0";
                }
            }
            
            // Filter by transport status
            if (isset($_GET['transportStatus']) && !empty($_GET['transportStatus'])) {
                $conditions[] = "LOWER(T.transport_state) = LOWER(?)";
                $params[] = $_GET['transportStatus'];
                $types .= "s";
            }
            
            // Filter by date range
            if (isset($_GET['startDate']) && !empty($_GET['startDate'])) {
                $conditions[] = "DATE(T.date) >= ?";
                $params[] = $_GET['startDate'];
                $types .= "s";
            }
            
            if (isset($_GET['endDate']) && !empty($_GET['endDate'])) {
                $conditions[] = "DATE(T.date) <= ?";
                $params[] = $_GET['endDate'];
                $types .= "s";
            }
            
            // Add conditions to query
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            // Order by date (newest first)
            $sql .= " ORDER BY T.date DESC";
            
            // Prepare and execute
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['user_ID']) . "</td>";
                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['userName']) . "</td>";
                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['fullname']) . "</td>";
                    
                    // Transport status with badge
                    echo "<td class='px-6 py-4 whitespace-nowrap'>";
                    $transportState = strtolower($row['transport_state']);
                    switch ($transportState) {
                        case 'pending':
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800'>รอดำเนินการ</span>";
                            break;
                        case 'processing':
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800'>กำลังจัดส่ง</span>";
                            break;
                        case 'delivered':
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>จัดส่งแล้ว</span>";
                            break;
                        case 'canceled':
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'>ยกเลิก</span>";
                            break;
                        default:
                            echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800'>" . htmlspecialchars($row['transport_state']) . "</span>";
                    }
                    echo "</td>";
                    
                    // Grand total
                    echo "<td class='px-6 py-4 whitespace-nowrap'>฿" . number_format($row['grandTotal'], 2) . "</td>";
                    
                    // Payment status
                    echo "<td class='px-6 py-4 whitespace-nowrap'>";
                    if ($row['paid'] == 1) {
                        echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>ชำระแล้ว</span>";
                    } else {
                        echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'>ยังไม่ชำระ</span>";
                    }
                    echo "</td>";
                    
                    // Date
                    echo "<td class='px-6 py-4 whitespace-nowrap'>" . date("d/m/Y H:i", strtotime($row['date'])) . "</td>";
                    
                }
            } else {
                echo "<tr><td colspan='8' class='px-6 py-4 text-center text-gray-500'>ไม่พบข้อมูลการชำระเงิน</td></tr>";
            }
            
            // Close statement
            $stmt->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
  
  
</body>
</html>