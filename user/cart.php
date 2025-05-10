<?php
session_start();
require_once("../conn.php");

if (!isset($_SESSION["userID"])) {
    header("Location: ../auth/sign_in.php");
    exit();
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// echo '<pre>';
// print_r($_SESSION);
// print_r($_POST);
// echo '</pre>';

$userID = $_SESSION['userID'];
$coupon_code = '';
$discount = 0;
$error_message = '';
$success_message = '';
$discount_amount = 0;
$subtotal = 0;

// Check if a coupon is stored in the session
if (isset($_SESSION['applied_coupon'])) {
    $coupon_code = $_SESSION['applied_coupon']['code'];
    $discount = $_SESSION['applied_coupon']['discount'];
    $discount_amount = ($subtotal * $discount) / 100;
}

// Fetch cart items
$sql = "SELECT c.product_ID, c.quantity, p.productName, p.price, p.imgPath, p.quantity as stock_quantity, p.is_delete
        FROM tbl_carts c
        JOIN tbl_products p ON c.product_ID = p.product_ID
        WHERE c.user_ID = ? AND p.is_delete = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
}

$shipping = 29;
$discount_amount = ($subtotal * $discount) / 100;
$total = $subtotal + $shipping - $discount_amount;

// Check if any items are no longer available
$unavailable_items = array_filter($items, function($item) {
    return $item['is_delete'] == 1;
});

if (!empty($unavailable_items)) {
    $_SESSION['error_message'] = "Some items in your cart are no longer available. They have been removed.";
    // Remove unavailable items from cart
    foreach ($unavailable_items as $item) {
        $remove_sql = "DELETE FROM tbl_carts WHERE user_ID = ? AND product_ID = ?";
        $remove_stmt = $conn->prepare($remove_sql);
        $remove_stmt->bind_param("ii", $userID, $item['product_ID']);
        $remove_stmt->execute();
    }
    // Refresh the page to show updated cart
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>

    <div id="notification-container" class="fixed top-0 left-0 right-0 z-50">
        <?php if (isset($_SESSION["error_message"])): ?>
            <div id="error-alert" class="mt-12 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-auto my-2 max-w-3xl flex justify-between items-center">
                <span><?php echo htmlspecialchars($_SESSION["error_message"]); ?></span>
                <button onclick="closeAlert('error-alert')" class="text-red-700 hover:text-red-900 font-bold">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["success_message"])): ?>
            <div id="success-alert" class="mt-12 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-auto my-2 max-w-3xl flex justify-between items-center">
                <span><?php echo htmlspecialchars($_SESSION["success_message"]); ?></span>
                <button onclick="closeAlert('success-alert')" class="text-green-700 hover:text-green-900 font-bold">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-white mb-8">Shopping Cart</h1>

        <?php if (empty($items)): ?>
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                <p class="text-xl text-gray-400">Your cart is empty</p>
                <a href="market.php" class="inline-block mt-4 px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <form id="checkout-form" action="billing.php" method="POST">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all" class="mr-2 h-5 w-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                                    <label for="select-all" class="text-lg font-semibold">Select All</label>
                                </div>
                                <button type="button" onclick="removeSelected()" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i> Remove Selected
                                </button>
                            </div>
                            <?php foreach ($items as $item): ?>
                                <div class="flex items-center py-4 border-b last:border-b-0" data-product-id="<?php echo $item['product_ID']; ?>">
                                    <input type="checkbox" name="selected_items[]" value="<?php echo $item['product_ID']; ?>" 
                                    id="item-checkbox"class="mr-4 h-5 w-5 text-orange-500 rounded border-gray-300 focus:ring-orange-500">
                                    
                                    <img src="../<?php echo htmlspecialchars($item['imgPath']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['productName']); ?>" 
                                         class="w-24 h-24 object-cover rounded-lg">
                                    
                                    <div class="ml-4 flex-grow">
                                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($item['productName']); ?></h3>
                                        <p class="text-gray-600">฿<?php echo number_format($item['price'], 2); ?></p>
                                        <div class="flex items-center mt-2">
                                            <button type="button" onclick="updateQuantity(<?php echo $item['product_ID']; ?>, 'decrease')" 
                                                    class="px-2 py-1 bg-gray-200 rounded-l hover:bg-gray-300">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="px-4 py-1 bg-gray-100 quantity-display"><?php echo $item['quantity']; ?></span>
                                            <button type="button" onclick="updateQuantity(<?php echo $item['product_ID']; ?>, 'increase')" 
                                                    class="px-2 py-1 bg-gray-200 rounded-r hover:bg-gray-300">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-4 text-right">
                                        <p class="text-lg font-semibold">฿<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                        <button type="button" onclick="removeItem(<?php echo $item['product_ID']; ?>)" 
                                                class="text-red-500 hover:text-red-700 mt-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                            
                            <!-- Coupon Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2">Coupon Usage</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="text" id="coupon-code" placeholder="Enter coupon code" 
                                           class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                    <button onclick="applyCoupon()" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                        Apply
                                    </button>
                                </div>
                                <div id="coupon-message" class="mt-2 text-sm"></div>
                            </div>

                            <!-- Totals Section -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span id="subtotal" class="font-semibold">฿<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-semibold">฿<?php echo number_format($shipping, 2); ?></span>
                                </div>
                                <?php if ($discount > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Discount (<?php echo $discount; ?>%)</span>
                                    <span id="discount-amount" class="font-semibold text-green-600">-฿<?php echo number_format($discount_amount, 2); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold">Total</span>
                                        <span id="total" class="text-lg font-semibold">฿<?php echo number_format($total, 2); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkout Button -->
                            <button type="submit" class="w-full bg-orange-500 text-white py-3 px-4 rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Function to close alerts
        function closeAlert(alertId) {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.remove();
            }
        }

        // Function to display messages
        function displayMessage(message, type) {
            const container = document.getElementById('notification-container');
            const alertId = `${type}-alert-${Date.now()}`;
            
            const alertDiv = document.createElement('div');
            alertDiv.id = alertId;
            alertDiv.className = `mt-12 ${type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'} border px-4 py-3 rounded relative mx-auto my-2 max-w-3xl flex justify-between items-center`;
            
            alertDiv.innerHTML = `
                <span>${message}</span>
                <button onclick="closeAlert('${alertId}')" class="${type === 'error' ? 'text-red-700 hover:text-red-900' : 'text-green-700 hover:text-green-900'} font-bold">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }

        // Function to update quantity
        function updateQuantity(productId, action) {
            const quantityDisplay = event.target.closest('.flex').querySelector('.quantity-display');
            let currentQuantity = parseInt(quantityDisplay.textContent);
            
            if (action === 'increase') {
                currentQuantity++;
            } else if (action === 'decrease' && currentQuantity > 1) {
                currentQuantity--;
            }

            // Disable the buttons while updating
            const buttons = event.target.closest('.flex').querySelectorAll('button');
            buttons.forEach(button => button.disabled = true);

            // Send update request
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('product_id', productId);
            formData.append('quantity', currentQuantity);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data); // Debug log
                
                if (data.success) {
                    // Update the quantity display
                    quantityDisplay.textContent = currentQuantity;
                    
                    // Update item total
                    const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
                    if (itemElement && data.itemTotal) {
                        const totalElement = itemElement.querySelector('.text-lg.font-semibold');
                        if (totalElement) {
                            totalElement.textContent = `฿${data.itemTotal}`;
                        }
                    }
                    
                    // Update cart totals
                    if (data.subtotal) {
                        const subtotalElement = document.getElementById('subtotal');
                        if (subtotalElement) subtotalElement.textContent = `฿${data.subtotal}`;
                    }
                    if (data.total) {
                        const totalElement = document.getElementById('total');
                        if (totalElement) totalElement.textContent = `฿${data.total}`;
                    }
                    if (data.discount_amount) {
                        const discountElement = document.getElementById('discount-amount');
                        if (discountElement) discountElement.textContent = `฿${data.discount_amount}`;
                    }

                    if (data.message) {
                        displayMessage(data.message, 'success');
                    }
                } else {
                    // Revert quantity on error
                    quantityDisplay.textContent = currentQuantity - (action === 'increase' ? 1 : -1);
                    displayMessage(data.error || 'Failed to update quantity', 'error');
                }
            })
            .finally(() => {
                // Re-enable the buttons
                buttons.forEach(button => button.disabled = false);
            });
        }

        // Function to remove item
        function removeItem(productId) {
            if (!confirm('Are you sure you want to remove this item?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'remove');
            formData.append('product_id', productId);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
                    itemElement.remove();
                    updateCartTotals(data);
                    checkEmptyCart();
                    displayMessage('Item removed successfully', 'success');
                } else if (data.error) {
                    displayMessage(data.error, 'error');
                }
            })
        }

        // Function to remove selected items
        function removeSelected() {
            const selectedItems = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked'))
                .map(checkbox => checkbox.value);

            if (selectedItems.length === 0) {
                displayMessage('Please select items to remove', 'error');
                return;
            }

            if (!confirm('Are you sure you want to remove selected items?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'remove_multiple');
            formData.append('product_ids', JSON.stringify(selectedItems));

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedItems.forEach(productId => {
                        const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
                        if (itemElement) {
                            itemElement.remove();
                        }
                    });
                    updateCartTotals(data);
                    checkEmptyCart();
                    displayMessage('Selected items removed successfully', 'success');
                } else if (data.error) {
                    displayMessage(data.error, 'error');
                }
            })
        }

        // Function to update cart totals
        function updateCartTotals(data) {
            if (data.subtotal) {
                document.getElementById('subtotal').textContent = `฿${data.subtotal}`;
            }
            if (data.total) {
                document.getElementById('total').textContent = `฿${data.total}`;
            }
            if (data.discount_amount) {
                document.getElementById('discount-amount').textContent = `฿${data.discount_amount}`;
            }
        }

        // Function to check if cart is empty
        function checkEmptyCart() {
            const items = document.querySelectorAll('[data-product-id]');
            if (items.length === 0) {
                location.reload(); // Reload to show empty cart message
            }
        }

        // Handle select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedTotals();
        });

        // Handle individual checkboxes
        document.querySelectorAll('input[name="selected_items[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedTotals();
                // Update select all checkbox
                const allCheckboxes = document.querySelectorAll('input[name="selected_items[]"]');
                const selectAll = document.getElementById('select-all');
                selectAll.checked = Array.from(allCheckboxes).every(cb => cb.checked);
            });
        });

        // Function to update totals for selected items
        function updateSelectedTotals() {
            const selectedItems = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked'))
                .map(checkbox => checkbox.value);

            const formData = new FormData();
            formData.append('action', 'get_totals');
            formData.append('selected_items', JSON.stringify(selectedItems));

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartTotals(data);
                }
            })
        }

        // Handle coupon application
        document.getElementById('coupon-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });

        function applyCoupon() {
            const couponCode = document.getElementById('coupon-code').value.trim();
            if (!couponCode) {
                displayMessage('Please enter a coupon code', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'apply_coupon');
            formData.append('coupon_code', couponCode);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageElement = document.getElementById('coupon-message');
                if (data.success) {
                    messageElement.textContent = 'Coupon applied successfully!';
                    messageElement.className = 'mt-2 text-sm text-green-600';
                    updateCartTotals(data);
                    displayMessage('Coupon applied successfully', 'success');
                } else {
                    messageElement.textContent = data.error || 'Invalid coupon code';
                    messageElement.className = 'mt-2 text-sm text-red-600';
                    displayMessage(data.error || 'Invalid coupon code', 'error');
                }
            })
        }
    </script>
</body>
</html>