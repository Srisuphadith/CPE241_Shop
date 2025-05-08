<?php
require_once("../conn.php");

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p><a href='product_detail.php?id=" . $row['product_id'] . "'>View Product</a></p>";
            echo "</div>";
        }
    } else {
        echo "No results found for your search.";
    }
}
?>
