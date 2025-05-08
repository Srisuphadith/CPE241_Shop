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
          <img src="https://media.discordapp.net/attachments/1369959680247463977/1369961059695460412/image.png?ex=681dc329&is=681c71a9&hm=59345d2633f4c3372fcb6ba6ae095a60e712d6ac3315ac963443c0cdd3a4da07&=&format=webp&quality=lossless" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl font-bold text-orange-500">MONGKOL</span>
        </a>

    <div>
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if (in_array($currentPage, ["market.php", "cart.php", "product.php", "product_detail.php"])) { 
        ?>
        <form id="search-form" class="relative flex items-center w-full max-w-xs">
            <input type="text" id="search-input" name="query" placeholder="Search" class="px-4 py-2 rounded-sm" required>
            <i class="fas fa-search absolute right-3 text-gray-400"></i>
        </form>
        <?php } ?>
        <script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: { query: query },
                    success: function(response) {
                        $('#search-results').html(response).show();
                        $('.main-product-grid').hide();
                    },
                    error: function() {
                        $('#search-results').html('<p>An error occurred. Please try again.</p>').show();
                    }
                });
            } else {
                $('#search-results').hide();
                $('.main-product-grid').show();
            }
        });
    });
</script>
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
        <a href="#" class="text-black font-medium hover:text-gray-300 transition">Product Management</a>
        <a href="#" class="text-black font-medium hover:text-gray-300 transition">Discount Management</a>
        <a href="#" class="text-black font-medium hover:text-gray-300 transition">Report</a>
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
          <img src="https://media.discordapp.net/attachments/1369959680247463977/1369961059695460412/image.png?ex=681dc329&is=681c71a9&hm=59345d2633f4c3372fcb6ba6ae095a60e712d6ac3315ac963443c0cdd3a4da07&=&format=webp&quality=lossless" alt="Logo" class="w-10 h-10" />
          <span class="text-2xl font-bold text-orange-500">MONGKOL</span>
        </a>

    <div>
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if (in_array($currentPage, ["market.php", "cart.php", "product.php", "product_detail.php"])) { 
        ?>
        <form id="search-form" class="relative flex items-center w-full max-w-xs">
            <input type="text" id="search-input" name="query" placeholder="Search" class="px-4 py-2 rounded-sm" required>
            <i class="fas fa-search absolute right-3 text-gray-400"></i>
        </form>
        <?php } ?>
        <script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: { query: query },
                    success: function(response) {
                        $('#search-results').html(response).show();
                        $('.main-product-grid').hide();
                    },
                    error: function() {
                        $('#search-results').html('<p>An error occurred. Please try again.</p>').show();
                    }
                });
            } else {
                $('#search-results').hide();
                $('.main-product-grid').show();
            }
        });
    });
</script>

    </div>
      <!-- Admin name & Logout -->
      <div class="flex items-center space-x-4">
        <p class="text-white flex items-center font-medium">Hello, <?php echo $_SESSION["firstName"]; ?></p>
        <a href="../user/cart.php" class="relative inline-block text-white px-4"><i class="fas fa-shopping-cart"></i></a>
        <a href="../sign_out.php" class="text-orange-400 hover:text-red-500 font-semibold pl-4">Logout</a>
      </div>
    </div>
  </div>

  <!-- Menu Bar (Bottom orange bar) -->
  <div class="w-full bg-orange-500 py-2 mb-8">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between space-x-20 mx-8 items-center">
        <div class="flex justify-around space-x-20">
        <a href="market.php" class="text-black font-medium hover:text-gray-300 transition">ทั้งหมด</a>
        <a href="market.php?category=buddhist" class="text-black font-medium hover:text-gray-300 transition">พุทธ</a>
        <a href="market.php?category=christian" class="text-black font-medium hover:text-gray-300 transition">คริสต์</a>
        <a href="market.php?category=islamic" class="text-black font-medium hover:text-gray-300 transition">อิสลาม</a>
        <a href="market.php?category=god" class="text-black font-medium hover:text-gray-300 transition">เทพเจ้า</a>
        <a href="market.php?category=others" class="text-black font-medium hover:text-gray-300 transition">อื่น ๆ</a>
        </div>
        <div class="flex justify-end space-x-20">
        <a href="order_history.php" class="text-black font-medium hover:text-gray-300 transition bg-white px-4 py-2 rounded-lg">Order</a>
        </div>
        <!-- <a href="#" class="text-white font-medium hover:text-gray-300 transition"></a>
        <a href="#" class="text-white font-medium hover:text-gray-300 transition">Reports</a> -->
      </div>
    </div>
  </div>
<?php } ?>
