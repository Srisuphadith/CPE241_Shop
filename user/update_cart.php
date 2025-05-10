<?php
session_start();
require_once("../conn.php");

if (!isset($_SESSION["userID"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userID = $_SESSION['userID'];
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Update quantity
    if ($action === 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];
        
        if ($quantity < 1) {
            $quantity = 1;
        }
        
        // Check product availability first
        $check_sql = "SELECT quantity, is_delete FROM tbl_products WHERE product_ID = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $product_id);
        $check_stmt->execute();
        $stock_result = $check_stmt->get_result();
        
        if ($row = $stock_result->fetch_assoc()) {
            if ($row['is_delete'] == 1) {
                $response['error'] = 'Product is no longer available';
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
            
            $available_stock = $row['quantity'];
            
            // Limit quantity to available stock
            if ($quantity > $available_stock) {
                $quantity = $available_stock;
                $response['message'] = 'Quantity adjusted to match available stock';
            }
            
            // Update cart quantity
            $update_sql = "UPDATE tbl_carts SET quantity = ? WHERE user_ID = ? AND product_ID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $quantity, $userID, $product_id);
            
            if ($update_stmt->execute()) {
                // Get updated item price
                $price_sql = "SELECT p.price FROM tbl_products p WHERE p.product_ID = ? AND p.is_delete = 0";
                $price_stmt = $conn->prepare($price_sql);
                $price_stmt->bind_param("i", $product_id);
                $price_stmt->execute();
                $price_result = $price_stmt->get_result();
                
                if ($price_row = $price_result->fetch_assoc()) {
                    $item_price = $price_row['price'];
                    $item_total = $item_price * $quantity;
                    $response['itemTotal'] = number_format($item_total, 2);
                    
                    // Calculate new cart totals
                    $totals_sql = "SELECT SUM(c.quantity * p.price) as subtotal 
                                  FROM tbl_carts c 
                                  JOIN tbl_products p ON c.product_ID = p.product_ID 
                                  WHERE c.user_ID = ? AND p.is_delete = 0";
                    $totals_stmt = $conn->prepare($totals_sql);
                    $totals_stmt->bind_param("i", $userID);
                    $totals_stmt->execute();
                    $totals_result = $totals_stmt->get_result();
                    
                    if ($totals_row = $totals_result->fetch_assoc()) {
                        $subtotal = $totals_row['subtotal'] ?? 0;
                        $shipping = 29; // This should match your cart page setting
                        
                        // Get coupon discount if exists
                        $discount = 0;
                        $discount_amount = 0;
                        if (isset($_SESSION['applied_coupon'])) {
                            $discount = $_SESSION['applied_coupon']['discount'];
                            $discount_amount = ($subtotal * $discount) / 100;
                        }
                        
                        $total = $subtotal + $shipping - $discount_amount;
                        
                        $response['subtotal'] = number_format($subtotal, 2);
                        $response['total'] = number_format($total, 2);
                        $response['discount_amount'] = number_format($discount_amount, 2);
                        $response['has_coupon'] = !empty($_SESSION['applied_coupon']);
                        $response['discount_percentage'] = $discount;
                    }
                }
                
                $response['success'] = true;
            } else {
                $response['error'] = 'Failed to update quantity';
            }
        } else {
            $response['error'] = 'Product not found';
        }
    }
    
    // Remove single item
    elseif ($action === 'remove' && isset($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        
        $remove_sql = "DELETE FROM tbl_carts WHERE user_ID = ? AND product_ID = ?";
        $remove_stmt = $conn->prepare($remove_sql);
        $remove_stmt->bind_param("ii", $userID, $product_id);
        
        if ($remove_stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Failed to remove item';
        }
    }
    
    // Remove multiple items
    elseif ($action === 'remove_multiple' && isset($_POST['product_ids'])) {
        $product_ids = json_decode($_POST['product_ids']);
        
        if (is_array($product_ids) && count($product_ids) > 0) {
            // Build placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
            $types = str_repeat('i', count($product_ids));
            
            $remove_sql = "DELETE FROM tbl_carts WHERE user_ID = ? AND product_ID IN ($placeholders)";
            $remove_stmt = $conn->prepare($remove_sql);
            
            // Bind userID first, then all product IDs
            $bind_params = array_merge([$types . 'i', $userID], $product_ids);
            call_user_func_array([$remove_stmt, 'bind_param'], $bind_params);
            
            if ($remove_stmt->execute()) {
                $response['success'] = true;
                $response['removed_count'] = $remove_stmt->affected_rows;
            } else {
                $response['error'] = 'Failed to remove items';
            }
        } else {
            $response['error'] = 'Invalid product IDs';
        }
    }
    
    // Add this at the bottom inside the POST handler
    elseif ($action === 'get_totals') {
        $selected_items = isset($_POST['selected_items']) ? json_decode($_POST['selected_items']) : [];
        
        if (empty($selected_items)) {
            $response = [
                'success' => true,
                'subtotal' => '0.00',
                'total' => '0.00',
                'discount_amount' => '0.00',
                'has_coupon' => false,
                'discount_percentage' => 0
            ];
        } else {
            // Build placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($selected_items), '?'));
            $types = str_repeat('i', count($selected_items));
            
            // Calculate new cart totals for selected items
            $totals_sql = "SELECT SUM(c.quantity * p.price) as subtotal 
                          FROM tbl_carts c 
                          JOIN tbl_products p ON c.product_ID = p.product_ID 
                          WHERE c.user_ID = ? AND c.product_ID IN ($placeholders) AND p.is_delete = 0";
            $totals_stmt = $conn->prepare($totals_sql);
            
            // Bind userID first, then all product IDs
            $bind_params = array_merge([$types . 'i', $userID], $selected_items);
            call_user_func_array([$totals_stmt, 'bind_param'], $bind_params);
            
            $totals_stmt->execute();
            $totals_result = $totals_stmt->get_result();

            if ($totals_row = $totals_result->fetch_assoc()) {
                $subtotal = $totals_row['subtotal'] ?? 0;
                
                // Apply coupon discount if exists
                $discount = 0;
                $discount_amount = 0;
                if (isset($_SESSION['applied_coupon'])) {
                    $discount = $_SESSION['applied_coupon']['discount'];
                    $discount_amount = ($subtotal * $discount) / 100;
                }

                $shipping = 29;
                $total = $subtotal + $shipping - $discount_amount;

                $response = [
                    'success' => true,
                    'subtotal' => number_format($subtotal, 2),
                    'discount_amount' => number_format($discount_amount, 2),
                    'total' => number_format($total, 2),
                    'has_coupon' => !empty($_SESSION['applied_coupon']),
                    'discount_percentage' => $discount
                ];
            } else {
                $response = [
                    'success' => false,
                    'error' => 'Could not calculate totals'
                ];
            }
        }
    }

    // Add this at the bottom inside the POST handler
    elseif ($action === 'apply_coupon') {
        if (!isset($_POST['coupon_code'])) {
            $response['error'] = 'Coupon code is required';
        } else {
            $coupon_code = $_POST['coupon_code'];
            
            // Check if coupon exists and is valid
            $coupon_sql = "SELECT * FROM tbl_coupons WHERE couponCode = ? AND is_delete = 0 AND expDate >= CURDATE() AND remain > 0";
            $coupon_stmt = $conn->prepare($coupon_sql);
            $coupon_stmt->bind_param("s", $coupon_code);
            $coupon_stmt->execute();
            $coupon_result = $coupon_stmt->get_result();
            
            if ($coupon = $coupon_result->fetch_assoc()) {
                // Calculate cart subtotal
                $totals_sql = "SELECT SUM(c.quantity * p.price) as subtotal 
                              FROM tbl_carts c 
                              JOIN tbl_products p ON c.product_ID = p.product_ID 
                              WHERE c.user_ID = ? AND p.is_delete = 0";
                $totals_stmt = $conn->prepare($totals_sql);
                $totals_stmt->bind_param("i", $userID);
                $totals_stmt->execute();
                $totals_result = $totals_stmt->get_result();
                
                if ($totals_row = $totals_result->fetch_assoc()) {
                    $subtotal = $totals_row['subtotal'] ?? 0;
                    
                    // Check if order meets minimum value requirement
                    if ($subtotal >= $coupon['minOrderValue']) {
                        // Store coupon in session
                        $_SESSION['applied_coupon'] = [
                            'code' => $coupon['couponCode'],
                            'discount' => $coupon['discount'],
                            'coupon_id' => $coupon['coupon_ID']
                        ];
                        
                        $discount_amount = ($subtotal * $coupon['discount']) / 100;
                        $shipping = 29;
                        $total = $subtotal + $shipping - $discount_amount;
                        
                        $response = [
                            'success' => true,
                            'subtotal' => number_format($subtotal, 2),
                            'discount_amount' => number_format($discount_amount, 2),
                            'total' => number_format($total, 2),
                            'has_coupon' => true,
                            'discount_percentage' => $coupon['discount']
                        ];
                    } else {
                        $response['error'] = 'Order does not meet minimum value requirement';
                    }
                } else {
                    $response['error'] = 'Could not calculate order total';
                }
            } else {
                $response['error'] = 'Invalid or expired coupon code';
            }
        }
    }

    else {
        $response['error'] = 'Invalid action';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>