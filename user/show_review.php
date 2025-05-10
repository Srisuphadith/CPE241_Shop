<h1 class="text-2xl font-bold mb-6 text-white text-center pt-16">Product Reviews</h1>

<?php
// Fetch reviews with user names and optional images
$stmt = $conn->prepare("
    SELECT R.review_ID, U.firstName, R.starRate, R.txt, I.imgPath
    FROM tbl_reviews R
    LEFT JOIN tbl_users U ON R.user_ID = U.user_ID
    LEFT JOIN tbl_review_images I ON R.review_ID = I.review_ID AND I.image_number = 1
    WHERE R.product_ID = ?
    ORDER BY R.date_posted DESC
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()):
?>
    <div class="flex bg-white shadow-md rounded-lg p-4 mb-4">
        <?php if ($row['imgPath']): ?>
            <img src="<?php echo htmlspecialchars($row['imgPath']); ?>" class="w-24 h-24 object-cover rounded mr-4" alt="Review image">
        <?php else: ?>
            <div class="w-24 h-24 bg-gray-200 rounded mr-4 flex items-center justify-center text-gray-400">No Image</div>
        <?php endif; ?>
        <div>
            <p class="font-semibold text-black text-lg mb-1">
                <?php echo $row['firstName'] ? htmlspecialchars($row['firstName']) : 'Anonymous'; ?>
            </p>
            <div class="flex text-yellow-400 mb-1">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<i class="fas fa-star' . ($i <= $row['starRate'] ? '' : '-o') . '"></i>';
                }
                ?>
            </div>
            <div class="text-gray-700 whitespace-pre-line">
                <?php echo nl2br(htmlspecialchars($row['txt'])); ?>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<!-- Review Form -->
<?php if (isset($_SESSION['userID'])): ?>
    <div class="bg-white shadow-md rounded-lg p-4 mt-6">
        <h2 class="text-xl font-semibold text-black mb-4">Write a Review</h2>
        <form method="POST" action="product_detail.php" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

            <!-- Rating -->
            <div class="flex items-center gap-1 ">
                <label class="text-gray-700 font-medium mr-2">Rating:</label>
                <input type="text" name="starRate" class="w-16 border border-gray-300 rounded p-2" placeholder="1-5" required>
                <div class="flex text-yellow-400">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<i class="fas fa-star' . ($i <= 5 ? '' : '-o') . '"></i>';
                    }
                    ?>
                </div>
            </div>
            <!-- Comment -->
            <textarea name="txt" rows="4" class="w-full border border-gray-300 rounded p-2" placeholder="Write your comment..." required></textarea>

            <!-- Image Upload -->
            <input type="file" name="review_image" accept="image/*" class="block">

            <!-- Submit -->
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                Submit Review
            </button>
        </form>
    </div>
<?php else: ?>
    <p class="mt-6 text-gray-600">You must be <a href="../auth/sign_in.php" class="text-blue-500 underline">logged in</a> to leave a review.</p>
<?php endif; ?>