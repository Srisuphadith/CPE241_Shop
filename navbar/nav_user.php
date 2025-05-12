<?php 
require_once("../conn.php");
require_once("setup.php");
session_start();
    if(!(isset($_SESSION["userID"]))){
        header("Location: ../auth/sign_in.php");
        exit();
    }

    if($_SESSION["role"] == 'seller'){
?>
  <!-- Navbar (Top dark bar) -->
  <div class="w-full bg-[#2E282A] py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6">
      <!-- Logo -->
        <a href="../user/market.php" class="flex items-center space-x-2">
          <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1371177503816679495/logo.png?ex=68223010&is=6820de90&hm=344b78b1e38f9ddbec7e069893b22b2fbf523c3a9f16b794017c09b98a1ed7fd&" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl font-bold text-orange-500">MONGKOL</span>
        </a>

    <div>
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if (in_array($currentPage, ["market.php", "cart.php", "product.php", "product_detail.php"])) { 
        ?>
        <form id="search-form" class="relative flex items-center w-full max-w-xs" action="../user/market.php" method="GET">
            <?php if (isset($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
            <?php endif; ?>
            <input type="text" id="search-input" name="query" placeholder="Search" class="px-4 py-2 rounded-sm" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
            <button type="submit" class="absolute right-3">
                <i class="fas fa-search text-gray-400"></i>
            </button>
        </form>
        <?php } ?>
    </div>
      <!-- Admin name & Logout -->
      <div class="flex items-center space-x-4">
        <a href="../sign_out.php" class="text-orange-400 hover:text-red-500 font-semibold pl-4">Logout</a>
      </div>
    </div>
  </div>

  <!-- Menu Bar (Bottom orange bar) -->
  <div class="w-full bg-orange-500 py-2 mb-8">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between space-x-20 mx-8 items-center">
        <div class="flex justify-around space-x-20">
        <a href="../seller/" class="text-black font-medium hover:text-gray-300 transition">
          <?php
            $stmt = $conn->prepare("SELECT shop_ID,shopName FROM tbl_shops WHERE user_ID = ?");
            $stmt->bind_param("s",$_SESSION['userID']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            echo $user['shopName']." Shop";
            $shopID = $user['shop_ID'];
            $result -> free_result();
          ?>
        </a>
        <a href="../seller/order_list.php" class="text-black font-medium hover:text-gray-300 transition">Order List</a>
        <a href="../seller/report_seller.php" class="text-black font-medium hover:text-gray-300 transition">Report</a>
        <a href="../seller/add_product.php" class="text-black font-medium hover:text-gray-300 transition">Add Product</a>
        </div>
        <!-- <a href="#" class="text-white font-medium hover:text-gray-300 transition"></a>
        <a href="#" class="text-white font-medium hover:text-gray-300 transition">Reports</a> -->
      </div>
    </div>
  </div>



<?php
//------------------------------------------------------------------------------------------------------------------------------------------------------
    }else{
?>
  <!-- Navbar (Top dark bar) -->
  <div class="w-full bg-[#2E282A] py-4 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6">
      <!-- Logo -->
        <a href="../user/market.php" class="flex items-center space-x-2">
          <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1369961059695460412/image.png?ex=681f14a9&is=681dc329&hm=19a29a779e8a01fe436f7e255a46191c7987c12d8c32d72f14a92936bdcbb8d8&" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl ibm-plex-sans-thai-bold text-orange-500">MONGKOL</span>
        </a>

    <div>
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if (in_array($currentPage, ["market.php", "cart.php", "product.php", "product_detail.php"])) { 
        ?>
        <form id="search-form" class="relative flex items-center w-full max-w-xs" action="../user/market.php" method="GET">
            <?php if (isset($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
            <?php endif; ?>
            <input type="text" id="search-input" name="query" placeholder="Search" class="px-4 ibm-plex-sans-thai-light py-2 rounded-sm" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
            <button type="submit" class="absolute right-3">
                <i class="fas fa-search text-gray-400"></i>
            </button>
        </form>
        <?php } ?>
    </div>
      <!-- Admin name & Logout -->
      <div class="flex items-center space-x-4">
        <p class="text-white ibm-plex-sans-thai-medium items-center font-medium">Hello, <?php echo $_SESSION["firstName"]; ?></p>
        <a href="../user/cart.php" class="relative inline-block text-white px-4"><i class="fas fa-shopping-cart"></i></a>
        <a href="../sign_out.php" class="text-orange-400 hover:text-red-500 ibm-plex-sans-thai-semibold pl-4">Logout</a>
      </div>
    </div>
  </div>

  <!-- Menu Bar (Bottom orange bar) -->
  <div class="w-full bg-orange-500 py-2 mb-8">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between space-x-20 mx-8 items-center">
        <div class="flex justify-around space-x-20">
        <a href="market.php" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">ทั้งหมด</a>
        <a href="market.php?category=buddhist" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">พุทธ</a>
        <a href="market.php?category=christian" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">คริสต์</a>
        <a href="market.php?category=islamic" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">อิสลาม</a>
        <a href="market.php?category=god" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">เทพเจ้า</a>
        <a href="market.php?category=others" class="text-l ibm-plex-sans-thai-medium hover:text-gray-300 transition">อื่น ๆ</a>
        </div>
        <div class="flex justify-end space-x-20">
        <a href="#" class="text-l ibm-plex-sans-thai-regular hover:text-gray-300 transition bg-white px-3 py-1 rounded-lg">Order</a>
        </div>
        <!-- <a href="#" class="text-white font-medium hover:text-gray-300 transition"></a>
        <a href="#" class="text-white font-medium hover:text-gray-300 transition">Reports</a> -->
      </div>
    </div>
  </div>
<?php } ?>
