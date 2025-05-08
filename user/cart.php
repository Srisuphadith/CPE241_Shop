<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Cart</title>
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>
    <?php
    // Fetch cart items for the logged-in user
    $userID = $_SESSION['userID'];
    $sql = "SELECT c.product_ID, c.quantity, p.productName, p.imgPath, p.price FROM tbl_carts c JOIN tbl_products p ON c.product_ID = p.product_ID WHERE c.user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = [];
    $subtotal = 0;
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $subtotal += $row['price'] * $row['quantity'];
    }
    $shipping = 29;
    $total = $subtotal + $shipping;
    ?>
    <form action="billing.php" method="post" class="max-w-6xl mx-auto mt-8 grid grid-cols-[60%_40%] gap-4 h-[600px]">
    <!-- Left Column (60%) -->
    <div class="space-y-4 bg-soft-white p-4 overflow-auto rounded-lg">
        <p class="poppins-font text-2xl font-bold mb-4">Your cart</p>
        <div class="bg-white px-2 py-4 rounded-lg">
            <?php if (count($cartItems) === 0): ?>
                <div class="poppins-font text-lg text-gray-500 text-center">Your cart is empty.</div>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="flex items-center space-x-4 p-4 rounded-lg mb-2 bg-gray-100">
                        <img src="<?php echo htmlspecialchars($item['imgPath']); ?>" alt="<?php echo htmlspecialchars($item['productName']); ?>" class="w-16 h-16 object-cover rounded" />
                        <div class="flex-1">
                            <span class="poppins-font text-lg font-bold"><?php echo htmlspecialchars($item['productName']); ?></span>
                            <div class="poppins-font text-sm text-gray-600">Quantity: <?php echo $item['quantity']; ?></div>
                            <div class="poppins-font text-sm text-gray-600">Unit price: THB <?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <span class="poppins-font text-lg font-bold">THB <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column (40%) -->
    <div class="flex flex-col bg-gray-200 p-6 rounded-lg">
        <div class="flex-1">
            <p class="poppins-font text-2xl font-bold mb-4">Summary</p>
            <div class="flex justify-between mb-2 poppins-font text-lg">
                <span>Subtotal</span>
                <span>THB <?php echo number_format($subtotal, 2); ?></span>
            </div>
            <div class="flex justify-between mb-2 poppins-font text-lg">
                <span>Shipping</span>
                <span>THB <?php echo number_format($shipping, 2); ?></span>
            </div>
            <div class="flex items-center mb-4 poppins-font text-lg">
                <span class="mr-2">Discount coupon</span>
                <input type="text" name="coupon" class="rounded px-2 py-1 text-base flex-1" style="max-width: 140px;" placeholder="" />
            </div>
            <div class="flex justify-between mt-6 poppins-font text-xl font-bold">
                <span>Total</span>
                <span>THB <?php echo number_format($total, 2); ?></span>
            </div>
        </div>
        <button type="submit" class="mt-6 w-full bg-orange-500 text-white rounded text-xl font-bold py-3 hover:bg-orange-600 transition">Checkout</button>
    </div>
</form>


</body>
</html>