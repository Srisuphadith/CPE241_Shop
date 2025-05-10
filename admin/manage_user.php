<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage_user</title>
</head>
  
  <body class = "bg-w-full bg-[#2E282A] mr-8 ml-8" >
    <?php require_once("../navbar/nav_admin.php"); ?>
    <div class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Manage User</h2> 
    <!-- 🔍 Search Form -->
    <!-- Search and Filter Form -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
      <form method="GET" class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="searchUsername">
            ค้นหาตาม Username
          </label>
          <input type="text" name="searchUsername" id="searchUsername" 
                placeholder="Username"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                value="<?php echo isset($_GET['searchUsername']) ? htmlspecialchars($_GET['searchUsername']) : ''; ?>">
        </div>
        
        <div>
          <label class="block text-gray-700 text-sm font-bold mb-2" for="roleStatus">
            หน้าที่
          </label>
          <select name="roleStatus" id="roleStatus"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="">ทั้งหมด</option>
            <option value="admin" <?php echo (isset($_GET['roleStatus']) && $_GET['roleStatus'] == 'admin') ? 'selected'  : ''; ?>>admin</option>
            <option value="user" <?php echo (isset($_GET['roleStatus']) && $_GET['roleStatus'] == 'user') ? 'selected' : ''; ?>>buyer (user)</option>
            <option value="seller" <?php echo (isset($_GET['roleStatus']) && $_GET['roleStatus'] == 'seller') ? 'selected' : ''; ?>>seller</option>

          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            ค้นหา
          </button>
          <a href="manage_user.php" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            ล้างการค้นหา
          </a>
        </div>
      </form>
</div>
      <!-- Summary Cards -->
    <div class="grid md:grid-cols-4 gap-4 mb-6">
      <?php
        // Summary statistics queries
        $totalQuery = "SELECT COUNT(*) as total FROM tbl_users";
        $totalResult = $conn->query($totalQuery);
        $totalRow = $totalResult->fetch_assoc();
        
        $sellerQuery = "SELECT COUNT(*) as seller FROM tbl_users WHERE role = 'seller'";
        $sellerResult = $conn->query($sellerQuery);
        $sellerRow = $sellerResult->fetch_assoc();
        
        $buyerQuery = "SELECT COUNT(*) as buyer FROM tbl_users WHERE role = 'user'";
        $buyerResult = $conn->query($buyerQuery);
        $buyerRow = $buyerResult->fetch_assoc();
        
        $adminQuery = "SELECT COUNT(*) as admin FROM tbl_users WHERE role = 'admin'";
        $adminResult = $conn->query($adminQuery);
        $adminRow = $adminResult->fetch_assoc();
      ?>

      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ทั้งหมด</h3>
        <p class="text-2xl font-bold text-blue-600"><?php echo number_format($totalRow['total']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ผู้ขาย</h3>
        <p class="text-2xl font-bold text-green-600"><?php echo number_format($sellerRow['seller']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ผู้ซื้อ</h3>
        <p class="text-2xl font-bold text-red-600"><?php echo number_format($buyerRow['buyer']); ?></p>
      </div>

      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ผู้ดูแล</h3>
        <p class="text-2xl font-bold text-red-600"><?php echo number_format($adminRow['admin']); ?></p>
      </div>
    </div>

    <!-- 📋 Table -->
    <div class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
        <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">User ID</th>
            <th class="px-4 py-3">First Name</th>
            <th class="px-4 py-3">Middle Name</th>
            <th class="px-4 py-3">Last Name</th>
            <th class="px-4 py-3">Username</th>
            <th class="px-4 py-3">role</th>
            <th class="px-4 py-3">Action</th>
          </tr>
        </thead>
      <tbody>
        <?php
        // แก้ไขส่วนการค้นหาในโค้ด PHP
        // แทนที่โค้ดเดิมด้วยโค้ดนี้

        // รับค่าจากฟอร์มค้นหา
        $searchUsername = isset($_GET['searchUsername']) ? $_GET['searchUsername'] : '';
        $roleStatus = isset($_GET['roleStatus']) ? $_GET['roleStatus'] : '';

        // ป้องกันการโจมตีแบบ SQL Injection
        $searchUsername = $conn->real_escape_string($searchUsername);
        $roleStatus = $conn->real_escape_string($roleStatus);

        // สร้างคำสั่ง SQL พื้นฐาน
        $sql = "SELECT * FROM tbl_users WHERE 1=1";

        // เพิ่มเงื่อนไขการค้นหาตาม username ถ้ามีการกรอก
        if (!empty($searchUsername)) {
            $sql .= " AND userName LIKE '%$searchUsername%'";
        }

        // เพิ่มเงื่อนไขการค้นหาตาม role ถ้ามีการเลือก
        if (!empty($roleStatus)) {
            $sql .= " AND role = '$roleStatus'";
        }

        // เรียงลำดับผลลัพธ์ (ถ้าต้องการ)
        $sql .= " ORDER BY user_ID ASC";

        // ทำการ query และแสดงผล
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr class='border-b hover:bg-gray-100'>";

                // ID
                echo "<td class='px-4 py-2'>{$row['user_ID']}</td>";

                // First Name
                echo "<td class='px-4 py-2'>
                        <input type='text' value=\"{$row['firstName']}\" id='fname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                      </td>";

                // Middle Name
                $midName = $row['midName'] !== NULL ? $row['midName'] : '';
                echo "<td class='px-4 py-2'>
                        <input type='text' value=\"$midName\" id='mname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                      </td>";

                // Last Name
                echo "<td class='px-4 py-2'>
                        <input type='text' value=\"{$row['lastName']}\" id='lname-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                      </td>";

                // Username
                echo "<td class='px-4 py-2'>{$row['userName']}</td>";

                // role
                echo "<td class='px-4 py-2'>
                        <input type='text' value=\"{$row['role']}\" id='role-{$row['user_ID']}' disabled class='border rounded px-2 py-1'>
                      </td>";

                // Buttons
                echo "<td class='px-4 py-2 flex gap-2'>
                        <button onclick='editUser({$row['user_ID']})' class='bg-orange-400 hover:bg-orange-500 text-white px-3 py-1 rounded text-xs'>Edit</button>
                        <button onclick='saveUser({$row['user_ID']})' class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs'>Save</button>
                      </td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center px-4 py-3 text-gray-500'>ไม่พบข้อมูล</td></tr>";
        }
        ?>
          
        </tbody>
      </table>
    </div>
  </body>
  <script >
    function editUser(userID) {
      document.getElementById(`fname-${userID}`).disabled = false;
      document.getElementById(`mname-${userID}`).disabled = false;
      document.getElementById(`lname-${userID}`).disabled = false;
      document.getElementById(`role-${userID}`).disabled = false;
    }

    function saveUser(userID) {
      const firstName = document.getElementById(`fname-${userID}`).value;
      const midName = document.getElementById(`mname-${userID}`).value;
      const lastName = document.getElementById(`lname-${userID}`).value;
      const role = document.getElementById(`role-${userID}`).value;


      fetch('updateUser.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${userID}&firstName=${encodeURIComponent(firstName)}&midName=${encodeURIComponent(midName)}&lastName=${encodeURIComponent(lastName)}&role=${encodeURIComponent(role)}`

      })
      .then(response => response.text())
      .then(result => {
        alert(result);
        location.reload(); // อัปเดตข้อมูลใหม่หลังเซฟ
      });
    }

  // ฟังก์ชันสำหรับลบผู้ใช้ตาม userID ที่ส่งเข้ามา
  function deleteUser(userID) {
    // แสดงกล่องยืนยันก่อนลบ
    if (confirm("Are you sure you want to delete this user?")) {

      // เรียกไปที่ delete.php พร้อมส่ง userID เป็นพารามิเตอร์ผ่าน GET
      fetch(`delete.php?id=${userID}`, { method: 'GET' })
        .then(response => {
          // ตรวจสอบว่า server ตอบกลับด้วยสถานะ 200 (OK) หรือไม่
          if (response.ok) {
            // โหลดหน้าใหม่เพื่อแสดงผลลัพธ์หลังจากลบเสร็จ
            location.reload();
          } else {
            // ถ้าไม่ใช่ 200 ให้แสดงข้อความ error จาก server
            return response.text().then(text => {
              alert('Error deleting user: ' + text);
            });
          }
        })
        .catch(error => {
          // ถ้า fetch เจอปัญหา เช่น server ไม่ตอบกลับ
          alert('Network error: ' + error);
        });
    }
  }
  </script>



</html>