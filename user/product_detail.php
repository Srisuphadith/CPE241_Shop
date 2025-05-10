<?php
    session_start();

require_once('../conn.php');

// Redirect if not logged in
if (!isset($_SESSION["userID"])) {
    header("Location: ../auth/login.php");
    exit;
}


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// echo '<pre>';
// print_r($_SESSION);
// print_r($_POST);
// echo '</pre>';
// Handle form submission
if (isset($_POST['review']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $starRate = $_POST['starRate'] ?? 0;
    $txt = trim($_POST['txt']);
    $stmt = $conn->prepare("INSERT INTO tbl_reviews (product_ID, user_ID, starRate, txt) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $product_id, $userID, $starRate, $txt);
    $stmt->execute();
    $review_id = $conn->insert_id;

    // Handle image upload
    if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['review_image']['name']);
        $target_dir = "../img/reviews/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES['review_image']['tmp_name'], $target_file)) {
            $relative_path = substr($target_file, 3); // trim "../"
            $img_stmt = $conn->prepare("INSERT INTO tbl_review_images (review_ID, image_number, imgPath) VALUES (?, 1, ?)");
            $img_stmt->bind_param("is", $review_id, $relative_path);
            $img_stmt->execute();
        }
    }

    // header("Location: ?product_id=" . $product_id);
    // exit();
}


// Handle Add to Cart Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_ID"], $_POST["quantity"])) {
    // Fix: Ensure we use the correct session variable name
    $user_id = $_SESSION["userID"]; // Changed from user_ID to match line 9
    $product_id = filter_input(INPUT_POST, "product_ID", FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
    
    // Validate inputs
    if ($product_id === false || $product_id <= 0 || $quantity === false || $quantity <= 0) {
        // Handle invalid input
        $_SESSION["error_message"] = "Invalid product or quantity.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Get product stock
    $stmt = $conn->prepare("SELECT quantity FROM tbl_products WHERE product_ID = ? AND is_delete = 0");
    
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        $_SESSION["error_message"] = "Product not found.";
        header("Location: market.php");
        exit;
    }

    if ($quantity > $product["quantity"]) {
        $_SESSION["error_message"] = "Requested quantity exceeds available stock.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?product_ID=$product_id");
        exit;
    }

    // Check if product already exists in cart
    $stmt = $conn->prepare("SELECT quantity FROM tbl_carts WHERE user_ID = ? AND product_ID = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    $stmt->close();


    if ($cart_result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE tbl_carts SET quantity = quantity + ? WHERE user_ID = ? AND product_ID = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO tbl_carts (user_ID, product_ID, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        // Reduce stock in tbl_products
        $stmt = $conn->prepare("UPDATE tbl_products SET quantity = quantity - ? WHERE product_ID = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();
        $stmt = $conn->prepare("UPDATE tbl_product_stats SET addToCart = addToCart + ? WHERE product_ID = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION["success_message"] = "Product added to cart!";
    } else {
        $_SESSION["error_message"] = "Failed to add product to cart.";
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?product_ID=$product_id");
    exit;
}

// Fetch product info
if (isset($_GET["product_ID"])) {
    $product_id = filter_input(INPUT_GET, "product_ID", FILTER_VALIDATE_INT);
    if ($product_id === false || $product_id <= 0) {
        // Handle invalid product ID
        header("Location: market.php"); // Redirect to products page
        exit;
    }
    $_SESSION["product_ID"] = $product_id;
    $product_stat_check = $conn->prepare("SELECT COUNT(*) FROM tbl_product_stats WHERE product_ID = ?");
    $product_stat_check->bind_param("i", $product_id);
    $product_stat_check->execute();
    $product_stat_result = $product_stat_check->get_result();
    $product_stat_check->close();
    // echo $product_stat_result->num_rows;
    if ($product_stat_result->num_rows === 0) {
        // Product stats not found, insert a new record
        $stmt = $conn->prepare("INSERT INTO tbl_product_stats (product_ID) VALUES ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
    }
    $sql = "UPDATE tbl_product_stats SET visit = visit + 1 WHERE product_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close(); 
} elseif (isset($_SESSION["product_ID"])) {
    $product_id = $_SESSION["product_ID"];
} else {
    // No product ID available
    header("Location: market.php"); // Redirect to products page
    exit;
}

// Fix: Use prepared statement for product query
$sql = "SELECT p.productName, p.imgPath, ROUND(IFNULL(AVG(r.starRate), 0), 1) AS STAR, p.price, p.description 
        FROM tbl_products p 
        LEFT JOIN tbl_reviews r ON p.product_ID = r.product_ID 
        WHERE p.product_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Product not found
    header("Location: market.php");
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongkol | <?php echo htmlspecialchars($product["productName"]); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #notification-container {
            padding-top: 10px;
            transition: all 0.3s ease;
        }
        #error-alert, #success-alert {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="m-4 bg-soft-black">
<!-- Remove GET param -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const url = new URL(window.location);
    if (url.searchParams.has("product_ID")) {
        url.searchParams.delete("product_ID");
        window.history.replaceState({}, document.title, url.toString());
    }
});
</script>

<?php require_once("../navbar/nav_user.php"); ?>

<!-- Display messages - Fixed position at top -->
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

<script>
function closeAlert(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.display = 'none';
        }, 300);
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('#error-alert, #success-alert');
    alerts.forEach(alert => {
        if (alert) {
            // Add transition for smooth fade out
            alert.style.transition = 'opacity 0.2s ease';
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                closeAlert(alert.id);
            }, 5000);
        }
    });
});
</script>

<div class="max-w-6xl mx-auto mt-8 grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Product Images -->
    <div class="space-y-4">
        <img src="../<?php echo htmlspecialchars($product["imgPath"]); ?>" alt="<?php echo htmlspecialchars($product["productName"]); ?>" class="rounded-lg w-full max-w-md mx-auto shadow-lg text-soft-white">
    </div>

    <!-- Product Info -->
    <div>
        <h1 class="poppins-font text-2xl font-bold mb-2 text-soft-white"><?php echo htmlspecialchars($product["productName"]); ?></h1>

        <!-- Rating -->
        <div class="flex items-center mb-2 text-yellow-400 text-sm">
            <?php
                $rating = round($product["STAR"] * 2) / 2;
                for ($i = 1; $i <= 5; $i++) {
                    if ($rating >= $i) {
                        echo '<i class="fas fa-star"></i>';
                    } elseif ($rating >= ($i - 0.5)) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
            ?>
            <span class="poppins-font text-gray-400 text-xs ml-2">(<?php echo number_format($product["STAR"], 1); ?>)</span>
        </div>

        <!-- Price -->
        <p class="poppins-font text-2xl font-semibold text-white mb-4">฿<?php echo number_format($product["price"]); ?></p>

        <!-- Quantity + Button -->
        <form method="POST" action="product_detail.php" class="flex items-center mb-6 space-x-2">
            <input type="hidden" name="product_ID" value="<?php echo $product_id; ?>">
            <button type="button" onclick="changeQty(-1)" class="bg-white px-3 py-1 rounded">-</button>
            <input id="qtyInput" type="text" name="quantity" value="1" min="1" inputmode="numeric" class="w-12 text-center text-black rounded">
            <button type="button" onclick="changeQty(1)" class="bg-white px-3 py-1 rounded">+</button>
            <button type="submit" class="ml-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2 rounded">
                Add to cart
            </button>
        </form>

        <script>
        function changeQty(delta) {
            const input = document.getElementById("qtyInput");
            let value = parseInt(input.value);
            if (!isNaN(value)) {
                input.value = Math.max(1, value + delta);
            }
        }
        </script>
        
        <!-- Description -->
        <div class="rounded-lg p-4">
            <h2 class="poppins-font text-lg font-bold mb-2 text-soft-white">Detail</h2>
            <p class="leading-relaxed whitespace-pre-line text-soft-white"><?php echo nl2br(htmlspecialchars($product["description"])); ?></p>
        </div>
        
    </div>

</div>
    <div class="flex flex-col gap-4">
        <div class="flex flex-col mx-12"><?php require_once("show_review.php");?></div>
    </div>
</body>
</html>