<?php require_once("../conn.php"); ?>
<?php session_start(); 
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
          <img src="../img/logo2.png" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl font-bold text-orange-500">MONGKOL</span>
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
        <a href="../admin/manage_user.php" class="text-white font-medium hover:text-gray-300 transition">Users Management</a>
        <a href="../admin/coupon.php" class="text-white font-medium hover:text-gray-300 transition">Coupons Management</a>
        <a href="#" class="text-white font-medium hover:text-gray-300 transition">Payment</a>
        <a href="#" class="text-white font-medium hover:text-gray-300 transition">Reports</a>
      </div>
    </div>
  </div>

