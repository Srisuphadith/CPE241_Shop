<?php
// Start session and check for seller role
session_start();
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'seller') {
//     echo "Access denied.";
//     exit;
// }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coupon Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/navbar/nav_admin.php"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Coupon Management</h1>
      <nav class="text-sm text-gray-500 mt-1">Dashboard > Coupons</nav>
    </div>
    <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      + Create Coupon
    </button>
  </header>

  <!-- Main content -->
  <main class="p-6">
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <h2 class="text-lg font-semibold mb-4">Your Coupons</h2>
      <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2">Coupon Code</th>
            <th class="px-4 py-2">Discount</th>
            <th class="px-4 py-2">Start Date</th>
            <th class="px-4 py-2">End Date</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody id="couponTable" class="bg-white">
          <!-- Coupon rows go here -->
        </tbody>
      </table>
    </div>
  </main>

  <!-- Coupon Modal -->
  <div id="couponModal" class="fixed inset-0 bg-black bg-opacity-30 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg w-full max-w-xl relative">
      <h3 class="text-xl font-bold mb-4" id="modalTitle">Create New Coupon</h3>
      <form id="couponForm" onsubmit="submitCoupon(event)">
        <div class="mb-3">
          <label class="block mb-1">Coupon Code</label>
          <input type="text" name="code" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-3">
          <label class="block mb-1">Discount Type</label>
          <select name="type" class="w-full border rounded p-2">
            <option value="%">Percentage (%)</option>
            <option value="fixed">Fixed Amount</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="block mb-1">Discount Value</label>
          <input type="number" name="value" class="w-full border rounded p-2" required>
        </div>
        <div class="flex gap-4 mb-3">
          <div class="w-1/2">
            <label class="block mb-1">Start Date</label>
            <input type="date" name="start" class="w-full border rounded p-2" required>
          </div>
          <div class="w-1/2">
            <label class="block mb-1">End Date</label>
            <input type="date" name="end" class="w-full border rounded p-2" required>
          </div>
        </div>
        <div class="mb-4">
          <label class="block mb-1">Max Usage Limit (Optional)</label>
          <input type="number" name="limit" class="w-full border rounded p-2">
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded">Cancel</button>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
      </form>
      <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 text-xl">&times;</button>
    </div>
  </div>

  <!-- JS Script -->
  <script>
    let editIndex = null;
    const coupons = [];

    function openModal(index = null) {
      editIndex = index;
      const modal = document.getElementById("couponModal");
      const form = document.getElementById("couponForm");
      document.getElementById("modalTitle").innerText = index === null ? "Create New Coupon" : "Edit Coupon";
      form.reset();

      if (index !== null) {
        const c = coupons[index];
        form.code.value = c.code;
        form.type.value = c.type;
        form.value.value = c.value;
        form.start.value = c.start;
        form.end.value = c.end;
        form.limit.value = c.limit || '';
      }

      modal.classList.remove("hidden");
    }

    function closeModal() {
      document.getElementById("couponModal").classList.add("hidden");
      editIndex = null;
    }

    function submitCoupon(e) {
      e.preventDefault();
      const form = e.target;
      const newCoupon = {
        code: form.code.value,
        type: form.type.value,
        value: form.value.value,
        start: form.start.value,
        end: form.end.value,
        limit: form.limit.value,
      };

      if (editIndex !== null) {
        coupons[editIndex] = newCoupon;
      } else {
        coupons.push(newCoupon);
      }

      renderCoupons();
      closeModal();
    }

    function deleteCoupon(index) {
      if (confirm("Are you sure you want to delete this coupon?")) {
        coupons.splice(index, 1);
        renderCoupons();
      }
    }

    function renderCoupons() {
      const tbody = document.getElementById("couponTable");
      tbody.innerHTML = "";

      coupons.forEach((c, i) => {
        const now = new Date().toISOString().split('T')[0];
        const status = c.end >= now ? "Active" : "Expired";
        const row = `
          <tr class="border-t">
            <td class="px-4 py-2">${c.code}</td>
            <td class="px-4 py-2">${c.type === '%' ? c.value + '%' : '$' + c.value}</td>
            <td class="px-4 py-2">${c.start}</td>
            <td class="px-4 py-2">${c.end}</td>
            <td class="px-4 py-2">
              <span class="${status === 'Active' ? 'text-green-600' : 'text-red-500'} font-medium">${status}</span>
            </td>
            <td class="px-4 py-2">
              <button onclick="openModal(${i})" class="text-blue-600 hover:underline mr-2">Edit</button>
              <button onclick="deleteCoupon(${i})" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>`;
        tbody.innerHTML += row;
      });
    }

    // Initial dummy render
    renderCoupons();
  </script>
</body>
</html>