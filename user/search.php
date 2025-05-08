<?php
require_once("../conn.php");

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
    $sql = "SELECT * FROM tbl_products WHERE productName LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="product_detail.php?product_ID=' . $row['product_ID'] . '" class="block bg-soft-white rounded-lg shadow hover:shadow-lg transition p-4">';
            echo '<img src="' . htmlspecialchars($row['imgPath']) . '" alt="' . htmlspecialchars($row['productName']) . '" class="w-full h-40 object-cover rounded mb-2">';
            echo '<div class="poppins-font text-lg font-bold mb-1">' . htmlspecialchars($row['productName']) . '</div>';
            echo '<div class="poppins-font text-base text-gray-700 mb-1">THB ' . number_format($row['price'], 2) . '</div>';
            echo '<div class="poppins-font text-sm text-gray-500 line-clamp-2">' . htmlspecialchars($row['description']) . '</div>';
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="poppins-font text-lg text-gray-500 text-center">No results found for your search.</div>';
    }
}
?>
