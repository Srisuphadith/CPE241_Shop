<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mongkol | Market</title>
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); ?>



    <div class="relative w-full overflow-hidden h-[200px] rounded-lg mb-4">
    <div id="bannerTrack" class="flex h-full transition-transform duration-500 ease-in-out">
            <div class="w-full flex-shrink-0 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Welcome to Our Store</h2>
                    <p class="text-lg">Swipe to see more deals</p>
                </div>
            </div>

            <div class="w-full flex-shrink-0 flex items-center justify-center bg-gradient-to-r from-green-500 to-teal-600 text-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">New Arrivals Are Here</h2>
                    <p class="text-lg">Fresh styles just dropped</p>
                </div>
            </div>

            <div class="w-full flex-shrink-0 flex items-center justify-center bg-gradient-to-r from-pink-500 to-red-600 text-center">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Exclusive Offers</h2>
                    <p class="text-lg">Only this week!</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        const track = document.getElementById('bannerTrack');
        let currentSlide = 0;
        const totalSlides = 3;

        // Auto swipe every 5 seconds
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        }, 5000);

        // Optional: Add swipe gesture (basic)
        let startX = 0;
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        track.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            if (startX - endX > 50) {
                currentSlide = (currentSlide + 1) % totalSlides;
            } else if (endX - startX > 50) {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            }
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        });
    </script>
    <div class="poppins-font flex justify-center align-center my-4">
        <?php
        $sql = "SELECT p.product_ID, p.productName, p.imgPath, p.price, AVG(r.starRate) AS STAR 
                FROM tbl_products p 
                LEFT JOIN tbl_reviews r ON p.product_ID = r.product_ID 
                GROUP BY p.product_ID
                ORDER BY p.product_ID ASC;";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){?>
                <div class="poppins-font flex flex-col justify-center py-1 px-2 mx-3 rounded-md bg-soft-white w-1/6">
                        <img src="<?php echo $row["imgPath"]; ?>" alt="<?php echo $row["productName"];?>" class="rounded-md mb-1 poppins-font">
                        <p class="poppins-font text-sm"><?php echo $row["productName"]; ?></b></p>
                        <div class="flex justify-between poppins-font items-end">
                            <p class="poppins-font"><?php echo number_format($row["STAR"],1,".",","); ?>/5</p>
                            <p class="poppins-font text-xl font-semibold">฿<?php echo number_format($row["price"],2,".",","); ?></p>
                        </div>
                </div>
        <?php
            }
        }
        ?>
    </div>


        <div id="search-results"></div>
</body>
</html>
