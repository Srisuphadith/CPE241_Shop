<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Billing</title>
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

    // Fetch addresses for the user
    $address_sql = "SELECT * FROM tbl_address WHERE user_ID = ? ORDER BY `type`, is_primary DESC";
    $address_stmt = $conn->prepare($address_sql);
    $address_stmt->bind_param("i", $userID);
    $address_stmt->execute();
    $address_result = $address_stmt->get_result();
    $addresses = [];
    while ($row = $address_result->fetch_assoc()) {
        $addresses[] = $row;
    }
    ?>
    <form action="order_confirm.php" method="post" class="max-w-6xl mx-auto mt-8 grid grid-cols-[60%_40%] gap-4 h-[600px]">
    <!-- Left Column (60%) -->
    <div class="space-y-4 bg-soft-white p-4 overflow-auto rounded-lg">
        <p class="poppins-font text-2xl font-bold mb-4">Payment Method</p>
        <div class="bg-white px-2 py-4 rounded-lg mb-6">
            <label class="block mb-2">
                <input type="radio" name="payment_method" value="cod" class="mr-2" checked> <span class="poppins-font">Cash on Delivery</span>
            </label>
            <label class="block mb-2">
                <input type="radio" name="payment_method" value="credit" class="mr-2"> <span class="poppins-font">Credit/Debit Card</span>
            </label>
            <label class="block">
                <input type="radio" name="payment_method" value="bank" class="mr-2"> <span class="poppins-font">Bank Transfer</span>
            </label>
        </div>

        <p class="poppins-font text-2xl font-bold mb-2">Shipping Address</p>
        <div class="bg-white px-2 py-4 rounded-lg">
            <?php
            if (count($addresses) > 0) {
                $shownTypes = [];
                $first = true;
                foreach ($addresses as $row) {
                    $type = $row['type'];
                    $label = $type === 'house' ? 'House' : 'Office';
                    if (!in_array($type, $shownTypes)) {
                        echo '<h2 class="text-xl font-bold mb-2 mt-6 poppins-font">' . $label . '</h2>';
                        $shownTypes[] = $type;
                    }
                    $fullAddress = "{$row['buildingNumber']} {$row['district']} {$row['province']} {$row['subdistrict']} {$row['country']} {$row['zip_code']}";
                    $isChecked = ($row['is_primary'] == 1 || $first) ? 'checked' : '';
                    $first = false;
                    ?>
                    <label class="block mb-4 p-4 border border-gray-600 rounded-lg cursor-pointer hover:bg-gray-200">
                        <input type="radio" name="address_ID" value="<?php echo htmlspecialchars($row['buildingNumber']); ?>" class="mr-3" <?php echo $isChecked; ?>>
                        <span class="poppins-font text-base"><?php echo htmlspecialchars($fullAddress); ?></span><br>
                        <span class="poppins-font text-xs text-gray-400"><?php echo htmlspecialchars($row['txt']); ?></span>
                    </label>
                    <?php
                }
            } else {
                echo '<p class="text-gray-500">No addresses found.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Right Column (40%) -->
    <div class="flex flex-col bg-gray-200 p-6 rounded-lg">
        <div class="flex-1">
            <p class="poppins-font text-2xl font-bold mb-4">Order Summary</p>
            <?php if (count($cartItems) === 0): ?>
                <div class="poppins-font text-lg text-gray-500 text-center">Your cart is empty.</div>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="flex items-center space-x-4 p-4 rounded-lg mb-2 bg-gray-100">
                        <img src="<?php echo htmlspecialchars($item['imgPath']); ?>" alt="<?php echo htmlspecialchars($item['productName']); ?>" class="w-12 h-12 object-cover rounded" />
                        <div class="flex-1">
                            <span class="poppins-font text-base font-bold"><?php echo htmlspecialchars($item['productName']); ?></span>
                            <div class="poppins-font text-xs text-gray-600">Qty: <?php echo $item['quantity']; ?> | Unit: THB <?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <span class="poppins-font text-base font-bold">THB <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="flex justify-between mb-2 poppins-font text-lg mt-4">
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
        <button type="submit" class="mt-6 w-full bg-blue-500 text-white rounded text-xl font-bold py-3 hover:bg-blue-600 transition">Confirm Order</button>
        <a href="cart.php" class="mt-2 w-full block text-center bg-gray-400 text-white rounded text-lg font-semibold py-2 hover:bg-gray-500 transition">Back to Cart</a>
    </div>
</form>


</body>
</html>