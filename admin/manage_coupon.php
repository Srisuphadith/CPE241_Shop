<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Coupon</title>
</head>

<body class="bg-w-full bg-[#2E282A] mr-8 ml-8">
  <?php 
    require_once("../navbar/nav_admin.php");
    require_once("../conn.php"); 
  ?> 
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Create Coupon</h2>
  
    <div class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
        <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">Coupon Code</th>
            <th class="px-4 py-3">Discount</th>
            <th class="px-4 py-3">Min Order</th>
            <th class="px-4 py-3">Remain</th>
            <th class="px-4 py-3">Exp Date</th>
            <th class="px-4 py-3 text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50 border-t">
            <td class="px-4 py-2">
              <input type="text" id="newCouponCode" placeholder="กรอกรหัส Code" class="border rounded px-2 py-1 w-full">
            </td>
            <td class="px-4 py-2">
              <input type="number" id="newDiscount" placeholder="%" min="0" max="100" class="border rounded px-2 py-1 w-full">
            </td>
            <td class="px-4 py-2">
              <input type="number" id="newMinOrderValue" placeholder="Min ฿" min="0" class="border rounded px-2 py-1 w-full">
            </td>
            <td class="px-4 py-2">
              <input type="number" id="newRemain" placeholder="จำนวน" min="1" class="border rounded px-2 py-1 w-full">
            </td>
            <td class="px-4 py-2">
              <input type="date" id="newExpDate" class="border rounded px-2 py-1 w-full">
            </td>
            <td class="px-4 py-2 text-center">
              <button onclick="createCoupon()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs transition duration-200">
                Create
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Manage Coupon</h2> 

    <!-- 🔍 Search Form -->
    <form method="GET" class="mb-6 flex justify-center">
      <input type="text" name="couponCode" placeholder="ค้นหา Coupon Code"
            class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-400"
            value="<?php echo isset($_GET['couponCode']) ? htmlspecialchars($_GET['couponCode']) : '' ?>">
      <button type="submit"
              class="px-4 py-2 bg-orange-500 text-white rounded-r-md hover:bg-orange-600 transition">
        ค้นหา
      </button>
      
    </form>
    <!-- Summary Cards -->
    <div class="grid md:grid-cols-4 gap-4 mb-6">
      <?php
        // Summary statistics queries
        $totalQuery = "SELECT COUNT(*) as total FROM tbl_coupons";
        $totalResult = $conn->query($totalQuery);
        $totalRow = $totalResult->fetch_assoc();
        
        $expireQuery = "SELECT COUNT(*) as expired FROM tbl_coupons WHERE expDate < CURRENT_DATE";
        $expireResult = $conn->query($expireQuery);
        $expireRow = $expireResult->fetch_assoc();
        
        $activeQuery = "SELECT COUNT(*) as active FROM tbl_coupons WHERE expDate > CURRENT_DATE";
        $activeResult = $conn->query($activeQuery);
        $activeRow = $activeResult->fetch_assoc();
        
      ?>

      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">คูปองทั้งหมด</h3>
        <p class="text-2xl font-bold text-blue-600"><?php echo number_format($totalRow['total']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">ใช้งานอยู่</h3>
        <p class="text-2xl font-bold text-green-600"><?php echo number_format($activeRow['active']); ?></p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-700">หมดอายุ</h3>
        <p class="text-2xl font-bold text-red-600"><?php echo number_format($expireRow['expired']); ?></p>
      </div>
    </div>
    <!-- 📋 Table -->
    <div class="overflow-x-auto shadow-md rounded-lg">
      <table class="min-w-full text-sm text-left text-gray-700 bg-white border border-gray-200">
        <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">Coupon ID</th>
            <th class="px-4 py-3">Coupon Code</th>
            <th class="px-4 py-3">Discount</th>
            <th class="px-4 py-3">Min Order</th>
            <th class="px-4 py-3">Remain</th>
            <th class="px-4 py-3">Exp Date</th>
            <th class="px-4 py-3">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $searchCode = isset($_GET['couponCode']) ? $_GET['couponCode'] : '';
          
          // Using prepared statement for search to prevent SQL injection
          $sql = "SELECT * FROM tbl_coupons";
          if (!empty($searchCode)) {
              $sql .= " WHERE couponCode LIKE ?";
              $stmt = $conn->prepare($sql);
              $searchParam = "%$searchCode%";
              $stmt->bind_param("s", $searchParam);
              $stmt->execute();
              $result = $stmt->get_result();
          } else {
              $result = $conn->query($sql);
          }

          if ($result && $result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $id = $row['coupon_ID'];
                  echo "<tr class='border-b hover:bg-gray-100'>";

                  echo "<td class='px-4 py-2'>" . htmlspecialchars($id) . "</td>";

                  echo "<td class='px-4 py-2'>
                          <input type='text' id='couponCode-{$id}' value=\"" . htmlspecialchars($row['couponCode']) . "\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='discount-{$id}' value=\"" . htmlspecialchars($row['discount']) . "\" min='0' max='100' disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='minOrderValue-{$id}' value=\"" . htmlspecialchars($row['minOrderValue']) . "\" min='0' disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='remain-{$id}' value=\"" . htmlspecialchars($row['remain']) . "\" min='0' disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='date' id='expDate-{$id}' value=\"" . htmlspecialchars($row['expDate']) . "\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2 flex gap-2'>
                          <button onclick='editCoupon({$id})' class='bg-orange-400 hover:bg-orange-500 text-white px-3 py-1 rounded text-xs'>Edit</button>
                          <button onclick='saveCoupon({$id})' id='save-{$id}' class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs hidden'>Save</button>
                          <button onclick='deleteCoupon({$id})' class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs'>Delete</button>
                        </td>";

                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='7' class='text-center px-4 py-3 text-gray-500'>ไม่พบข้อมูล</td></tr>";
          }
          
          // Close statement if it exists
          if (isset($stmt)) {
              $stmt->close();
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function editCoupon(coupon_ID) {
      document.getElementById(`couponCode-${coupon_ID}`).disabled = false;
      document.getElementById(`discount-${coupon_ID}`).disabled = false;
      document.getElementById(`minOrderValue-${coupon_ID}`).disabled = false;
      document.getElementById(`remain-${coupon_ID}`).disabled = false;
      document.getElementById(`expDate-${coupon_ID}`).disabled = false;
      document.getElementById(`save-${coupon_ID}`).classList.remove('hidden');
    }

    function saveCoupon(coupon_ID) {
      const couponCode = document.getElementById(`couponCode-${coupon_ID}`).value;
      const discount = document.getElementById(`discount-${coupon_ID}`).value;
      const minOrderValue = document.getElementById(`minOrderValue-${coupon_ID}`).value;
      const remain = document.getElementById(`remain-${coupon_ID}`).value;
      const expDate = document.getElementById(`expDate-${coupon_ID}`).value;

      // Validation
      if (!couponCode || !discount || !minOrderValue || !remain || !expDate) {
        alert("กรุณากรอกข้อมูลให้ครบทุกช่อง");
        return;
      }

      if (discount < 0 || discount > 100) {
        alert("กรุณากรอกข้อมูลส่วนลด (discount) ในช่วง 0-100");
        return;
      }

      if (minOrderValue < 0) {
        alert("มูลค่าขั้นต่ำต้องไม่น้อยกว่า 0");
        return;
      }

      if (remain < 0) {
        alert("จำนวนคงเหลือต้องไม่น้อยกว่า 0");
        return;
      }

      // Confirm before saving
      if (confirm("ยืนยันการบันทึกข้อมูล?")) {
        fetch('updateCoupon.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${coupon_ID}&couponCode=${encodeURIComponent(couponCode)}&discount=${encodeURIComponent(discount)}&minOrderValue=${encodeURIComponent(minOrderValue)}&remain=${encodeURIComponent(remain)}&expDate=${encodeURIComponent(expDate)}`
        })
        .then(response => response.text())
        .then(result => {
          alert(result);
          location.reload(); // อัปเดตข้อมูล
        })
        .catch(error => {
          alert("Error: " + error);
        });
      }
    }
    
    function deleteCoupon(coupon_ID) {
      if (confirm("ยืนยันการลบคูปอง?")) {
        fetch('deleteCoupon.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${coupon_ID}`
        })
        .then(response => response.text())
        .then(result => {
          alert(result);
          location.reload(); // อัปเดตข้อมูล
        })
        .catch(error => {
          alert("Error: " + error);
        });
      }
    }
    
    function createCoupon() {
      const data = {
        couponCode: document.getElementById('newCouponCode').value,
        discount: document.getElementById('newDiscount').value,
        minOrderValue: document.getElementById('newMinOrderValue').value,
        remain: document.getElementById('newRemain').value,
        expDate: document.getElementById('newExpDate').value,
      };

      // ตรวจสอบค่าว่าง
      if (!data.couponCode || !data.discount || !data.minOrderValue || !data.remain || !data.expDate) {
        alert("กรุณากรอกข้อมูลให้ครบทุกช่อง");
        return;
      }

      if (data.discount < 0 || data.discount > 100) {
        alert("กรุณากรอกข้อมูลส่วนลด (discount) ในช่วง 0-100");
        return;
      }
      
      if (data.minOrderValue < 0) {
        alert("มูลค่าขั้นต่ำต้องไม่น้อยกว่า 0");
        return;
      }

      if (data.remain < 1) {
        alert("จำนวนคงเหลือต้องมากกว่า 0");
        return;
      }

      fetch("createCoupon.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data),
      })
      .then(response => response.text())
      .then(result => {
        if (result === "success") {
          alert("สร้างคูปองเรียบร้อยแล้ว");
          location.reload(); // รีเฟรชหน้า
        } else {
          alert("เกิดข้อผิดพลาด: " + result);
        }
      })
      .catch(error => {
        alert("Error: " + error);
      });
    }
    
    // Set minimum date for expiration date inputs to today
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('newExpDate').min = today;
      
      // Set minimum date for all existing expDate inputs
      const expDateInputs = document.querySelectorAll('input[id^="expDate-"]');
      expDateInputs.forEach(input => {
        input.min = today;
      });
    });
  </script>
</body>
</html>