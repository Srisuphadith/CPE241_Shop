<?php
session_start();

if (!isset($_SESSION['success'])) {
    // Redirect if accessed directly without placing an order
    header("Location: index.php");
    exit();
}

$orderID = $_GET['order_id'] ?? null;
$successMessage = $_SESSION['success'] ?? "Order completed.";

unset($_SESSION['success']); // Clear message after showing
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 40px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success {
            color: green;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .order-id {
            font-size: 18px;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="success">✅ <?php echo htmlspecialchars($successMessage); ?></div>
    
    <?php if ($orderID): ?>
        <div class="order-id">Your Order ID is <strong>#<?php echo htmlspecialchars($orderID); ?></strong></div>
    <?php else: ?>
        <div class="order-id">We couldn't retrieve your order ID.</div>
    <?php endif; ?>

    <a href="market.php">Return to Home</a>
</div>
</body>
</html>
