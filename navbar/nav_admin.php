<?php 
require_once("../conn.php");
require_once("setup.php");
session_start(); 
    if(!(isset($_SESSION["userID"]))){
        header("Location: ../admin/sign_in.php");
        exit();
      }
?>
  <!-- Navbar (Top dark bar) -->
  <div class="w-full bg-[#2E282A] py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6">
      <!-- Logo -->
        <a href="#" class="flex items-center space-x-2">
          <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1369961059695460412/image.png?ex=6821b7a9&is=68206629&hm=40fb05839f3ca1c774fa902f4af955e9aceb39e4977c8e4e0cc4e4efb89fb7cc&" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl font-white font-bold text-orange-500">MONGKOL SUPERADMIN</span>
        </a>

      <!-- Admin name & Logout -->
      <div class="flex items-center space-x-4">
        <p class="text-white">Hello, <?php echo $_SESSION["firstName"]; ?></p>
        <a href="../sign_out.php" class="text-orange-400 hover:text-red-500 font-semibold">Logout</a>
      </div>
    </div>
  </div>

  <!-- Menu Bar (Bottom orange bar) -->
  <div class="w-full bg-orange-500 py-3">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between space-x-20 mx-8">
        <a href="../admin/manage_user.php" class="text-white poppins-font hover:text-gray-300 transition">Users Management</a>
        <a href="../admin/manage_coupon.php" class="text-white poppins-font hover:text-gray-300 transition">Coupons Management</a>
        <a href="../admin/payment.php" class="text-white poppins-font hover:text-gray-300 transition">Payment</a>
        <a href="../admin/report.php" class="text-white poppins-font hover:text-gray-300 transition">Reports</a>
      </div>
    </div>
  </div>

