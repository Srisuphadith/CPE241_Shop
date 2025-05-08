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
  ?> 

  <div class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-semibold mb-6 text-center text-orange-500">Manage Coupon</h2> 

    <!-- 🔍 Search Form -->
    <form method="GET" class="mb-6 flex justify-center">
      <input type="text" name="couponCode" placeholder="กรอก Coupon Code"
            class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-400"
            value="<?php echo isset($_GET['couponCode']) ? htmlspecialchars($_GET['couponCode']) : '' ?>">
      <button type="submit"
              class="px-4 py-2 bg-orange-500 text-white rounded-r-md hover:bg-orange-600 transition">
        ค้นหา
      </button>
    </form>

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
          $searchCode = $conn->real_escape_string($searchCode);

          $sql = "SELECT * FROM tbl_coupons";
          if (!empty($searchCode)) {
              $sql .= " WHERE couponCode LIKE '%$searchCode%'";
          }

          $result = $conn->query($sql);
          if ($result && $result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $id = $row['coupon_ID'];
                  echo "<tr class='border-b hover:bg-gray-100'>";

                  echo "<td class='px-4 py-2'>{$id}</td>";

                  echo "<td class='px-4 py-2'>
                          <input type='text' id='couponCode-{$id}' value=\"{$row['couponCode']}\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='discount-{$id}' value=\"{$row['discount']}\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='minOrderValue-{$id}' value=\"{$row['minOrderValue']}\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='number' id='remain-{$id}' value=\"{$row['remain']}\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2'>
                          <input type='date' id='expDate-{$id}' value=\"{$row['expDate']}\" disabled class='border rounded px-2 py-1'>
                        </td>";

                  echo "<td class='px-4 py-2 flex gap-2'>
                          <button onclick='editCoupon({$id})' class='bg-orange-400 hover:bg-orange-500 text-white px-3 py-1 rounded text-xs'>Edit</button>
                          <button onclick='saveCoupon({$id})' class='bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs'>Save</button>
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
  </div>

  <script>
    function editCoupon(coupon_ID) {
      document.getElementById(`couponCode-${coupon_ID}`).disabled = false;
      document.getElementById(`discount-${coupon_ID}`).disabled = false;
      document.getElementById(`minOrderValue-${coupon_ID}`).disabled = false;
      document.getElementById(`remain-${coupon_ID}`).disabled = false;
      document.getElementById(`expDate-${coupon_ID}`).disabled = false;
    }

    function saveCoupon(coupon_ID) {
      const couponCode = document.getElementById(`couponCode-${coupon_ID}`).value;
      const discount = document.getElementById(`discount-${coupon_ID}`).value;
      const minOrderValue = document.getElementById(`minOrderValue-${coupon_ID}`).value;
      const remain = document.getElementById(`remain-${coupon_ID}`).value;
      const expDate = document.getElementById(`expDate-${coupon_ID}`).value;

      fetch('updateCoupon.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${coupon_ID}&couponCode=${encodeURIComponent(couponCode)}&discount=${encodeURIComponent(discount)}&minOrderValue=${encodeURIComponent(minOrderValue)}&remain=${encodeURIComponent(remain)}&expDate=${encodeURIComponent(expDate)}`
      })
      .then(response => response.text())
      .then(result => {
        alert(result);
        location.reload(); // อัปเดตข้อมูลใหม่หลังเซฟ
      });
    }
  </script>
</body>
</html>
