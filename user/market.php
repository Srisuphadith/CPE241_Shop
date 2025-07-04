<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mongkol | Market</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="icon" href="../img/logo.png">
</head>
<body class="m-4 bg-soft-black">
    <?php require_once("../navbar/nav_user.php"); 
    $filter = "WHERE p.is_delete = 0"; 
    // session_unset();
    // Handle category filter
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
        $allowed_categories = ['buddhist', 'christian', 'islamic', 'god', 'others'];
        if (in_array($category, $allowed_categories)) {
            $filter .= " AND c.cateName = '" . mysqli_real_escape_string($conn, $category) . "'";
        }
    }
    
    // Handle search query
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $searchQuery = mysqli_real_escape_string($conn, $_GET['query']);
        $filter .= " AND (p.productName LIKE '%$searchQuery%' OR p.description LIKE '%$searchQuery%')";
    }
    ?>
<!-- Improved Banner Carousel with Navigation Controls and Indicators -->
<div class="relative overflow-hidden rounded-lg mb-8 group w-full">
    <!-- Banner Images Track -->
    <div id="bannerTrack" class="flex transition-transform duration-500 ease-in-out h-full">
        <div class="w-full flex-shrink-0">
            <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1369960332780507206/1.png?ex=681f13fb&is=681dc27b&hm=69e4029bd51a92fe5e2663694f47f54797204a32a20e395e050aca148b8c8c5f&" alt="banner-ganesha" class="w-full h-full object-cover text-soft-white text-center bg-soft-white">
        </div>
        <div class="w-full flex-shrink-0">
            <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1369963861305720913/CPE241_-_Banner.png?ex=681f1745&is=681dc5c5&hm=94c92f6738eb587e9b1867f636ef49b1a5c001c46b7f6cc973fb66909ded065b&" alt="banner-reakna" class="w-full h-full object-cover text-soft-white text-center bg-soft-white">
        </div>
        <div class="w-full flex-shrink-0">
            <img src="https://cdn.discordapp.com/attachments/1369959680247463977/1369960364351164476/2.png?ex=681f1403&is=681dc283&hm=c67e311454c909bb22e738d1b1781e5980a447686d23af5681e92cdbe6e947a2&" alt="banner-sati" class="w-full h-full object-cover text-soft-white text-center bg-soft-white">
        </div>
    </div>
    
    <!-- Navigation Arrows -->
    <button id="prevButton" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 opacity-0 group-hover:opacity-70 hover:opacity-100 transition-opacity">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button id="nextButton" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 opacity-0 group-hover:opacity-70 hover:opacity-100 transition-opacity">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <!-- Slide Indicators -->
    <div id="indicators" class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <!-- Indicators will be added dynamically -->
    </div>
    
    <!-- Pause/Play Button -->
    <button id="pausePlayButton" class="absolute bottom-3 right-3 bg-black bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-70 hover:opacity-100 transition-opacity">
        <i class="fas fa-pause" id="pausePlayIcon"></i>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('bannerTrack');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const indicators = document.getElementById('indicators');
        const pausePlayButton = document.getElementById('pausePlayButton');
        const pausePlayIcon = document.getElementById('pausePlayIcon');
        
        let currentSlide = 0;
        const totalSlides = track.children.length;
        let autoplayInterval;
        let isPlaying = true;
        
        // Create indicators
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.classList.add('w-2', 'h-2', 'bg-white', 'rounded-full', 'opacity-50', 'transition-opacity');
            dot.dataset.slideIndex = i;
            
            dot.addEventListener('click', () => {
                currentSlide = i;
                updateSlide();
                updateIndicators();
            });
            
            indicators.appendChild(dot);
        }
        
        function updateSlide() {
            const slideWidth = track.parentElement.offsetWidth; // Get current container width
            track.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }
        
        function updateIndicators() {
            Array.from(indicators.children).forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('opacity-50');
                    dot.classList.add('opacity-100', 'w-3', 'h-3');
                } else {
                    dot.classList.add('opacity-50');
                    dot.classList.remove('opacity-100', 'w-3', 'h-3');
                }
            });
        }
        
        function startAutoplay() {
            if (autoplayInterval) clearInterval(autoplayInterval);
            
            autoplayInterval = setInterval(() => {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlide();
                updateIndicators();
            }, 5000);
        }
        
        // Previous button click
        prevButton.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlide();
            updateIndicators();
            
            if (isPlaying) {
                startAutoplay(); // Reset the interval timer
            }
        });
        
        // Next button click
        nextButton.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlide();
            updateIndicators();
            
            if (isPlaying) {
                startAutoplay(); // Reset the interval timer
            }
        });
        
        // Pause/Play button click
        pausePlayButton.addEventListener('click', () => {
            isPlaying = !isPlaying;
            
            if (isPlaying) {
                pausePlayIcon.classList.remove('fa-play');
                pausePlayIcon.classList.add('fa-pause');
                startAutoplay();
            } else {
                pausePlayIcon.classList.remove('fa-pause');
                pausePlayIcon.classList.add('fa-play');
                clearInterval(autoplayInterval);
            }
        });
        
        // Touch swipe functionality
        let startX = 0;
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });
        
        track.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;
            
            if (Math.abs(diffX) > 50) { // Minimum swipe distance
                if (diffX > 0) {
                    // Swipe left
                    currentSlide = (currentSlide + 1) % totalSlides;
                } else {
                    // Swipe right
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                }
                
                updateSlide();
                updateIndicators();
                
                if (isPlaying) {
                    startAutoplay(); // Reset the interval timer
                }
            }
        }, { passive: true });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateSlide();
                updateIndicators();
            } else if (e.key === 'ArrowRight') {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateSlide();
                updateIndicators();
            }
            
            if (isPlaying) {
                startAutoplay(); // Reset the interval timer
            }
        });
        
        // Initialize
        updateIndicators();
        startAutoplay();
        
        // Handle window resize to adjust slide positions
        window.addEventListener('resize', () => {
            updateSlide(); // Recalculate position on window resize
        });
        
        // Stop autoplay when page is not visible
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearInterval(autoplayInterval);
            } else if (isPlaying) {
                startAutoplay();
            }
        });
    });
</script>

<div class="ibm-plex-sans-thai-semibold grid gap-4 my-4 px-4
            grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
    <?php
    $sql = "SELECT p.product_ID, p.productName, p.imgPath, p.price, c.cateName, 
            ROUND(IFNULL(AVG(r.starRate), 0), 1) AS STAR 
            FROM tbl_products p 
            LEFT JOIN tbl_reviews r ON p.product_ID = r.product_ID 
            JOIN tbl_categories c ON c.cate_ID = p.cate_ID 
            $filter
            GROUP BY p.product_ID
            ORDER BY p.product_ID ASC;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){ ?>
            <a href="product_detail.php?product_ID=<?php echo $row['product_ID']; ?>" class="hover:shadow-lg transition-shadow duration-200 rounded-md">
                <div class="poppins-font flex flex-col justify-between py-1 px-2 rounded-md bg-soft-white h-full">
                    <img src="../<?php echo $row["imgPath"]; ?>" alt="<?php echo $row["productName"]; ?>" class="w-full h-40 object-cover rounded-md mb-1">
                    <div>
                        <p class="text-xl ibm-plex-sans-thai-medium line-clamp-2 h-10 overflow-hidden"><?php echo $row["productName"]; ?></p>
                        <div class="flex justify-between items-end mt-1">
                        <?php
                            $rating = round($row["STAR"] * 2) / 2;
                        ?>
                            <div class="flex items-center space-x-1 text-yellow-500 text-sm">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($rating >= $i) {
                                        echo '<i class="fas fa-star"></i>'; // full star
                                    } elseif ($rating >= ($i - 0.5)) {
                                        echo '<i class="fas fa-star-half-alt"></i>'; // half star
                                    } else {
                                        echo '<i class="far fa-star"></i>'; // empty star
                                    }
                                }
                                ?>
                                <span class="poppins-font text-gray-600 ml-1 text-xs">(<?php echo number_format($row["STAR"], 1, ".", ","); ?>)</span>
                            </div>
                            <p class="poppins-font text-xl font-semibold">฿<?php echo number_format($row["price"], 2, ".", ","); ?></p>
                        </div>
                    </div>
                </div>
            </a>
    <?php
        }
    }
    ?>
</div>

</body>
</html>
