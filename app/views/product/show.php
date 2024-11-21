<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Product</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/product_show.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="product-container">
        <div class="product-image-container">
            <!-- Carousel Container -->
            <div class="carousel-container">
                <div class="carousel-images">
                    <?php foreach ($data['images'] as $image): ?>
                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($image); ?>" alt="Product Image">
                    <?php endforeach; ?>
                </div>

                <!-- Carousel Buttons -->
                <button class="carousel-button carousel-prev" onclick="moveCarousel(-1)">&#10094;</button>
                <button class="carousel-button carousel-next" onclick="moveCarousel(1)">&#10095;</button>
            </div>

            <!-- Thumbnail Strip -->
            <div class="thumbnail-strip">
                <?php foreach ($data['images'] as $index => $image): ?>
                    <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($image); ?>"
                        alt="Thumbnail <?php echo $index + 1; ?>"
                        data-index="<?php echo $index; ?>"
                        class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-details">
            <h1><?php echo htmlspecialchars($data['productName']); ?></h1>
            <p class="brand"><?php echo htmlspecialchars($data['brand']); ?></p>
            <p class="category"><?php echo htmlspecialchars($data['categoryName']); ?></p>
            <div class="pricing">
                <p class="original-price">₹<?php echo htmlspecialchars($data['originalPrice']); ?></p>
                <p class="selling-price">₹<?php echo htmlspecialchars($data['sellingPrice']); ?></p>
            </div>
            <p class="weight">Weight: <?php echo htmlspecialchars($data['weight']); ?></p>

            <div class="buttons">
                <a href="<?php echo URLROOT; ?>/products" class="go-back-button">Go Back</a>
            </div>
        </div>
    </div>



    <script>
        let currentIndex = 0;

        // Get the container that holds the images
        const imagesContainer = document.querySelector('.carousel-images');
        const totalImages = imagesContainer.children.length;

        function moveCarousel(direction) {
            // Update currentIndex based on direction
            currentIndex += direction;

            // Loop back to the first or last image if the index goes out of bounds
            if (currentIndex < 0) {
                currentIndex = totalImages - 1;
            } else if (currentIndex >= totalImages) {
                currentIndex = 0;
            }

            // Calculate the new position to slide the images
            const offset = -currentIndex * 100; // Move images by 100% of their width
            imagesContainer.style.transform = `translateX(${offset}%)`; // Apply the sliding effect
        }

        document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('.carousel-images');
        const images = carousel.querySelectorAll('img');
        const thumbnails = document.querySelectorAll('.thumbnail-strip img');
        const imageWidth = images[0].clientWidth;
        let currentIndex = 0;

        // Function to update carousel and thumbnails
        function updateCarousel() {
            const offset = -currentIndex * imageWidth;
            carousel.style.transform = `translateX(${offset}px)`;

            // Update active thumbnail
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.classList.toggle('active', index === currentIndex);
            });
        }

        // Thumbnail click event
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                currentIndex = index; // Set currentIndex to clicked thumbnail
                updateCarousel();
            });
        });

        // Carousel navigation
        function moveCarousel(direction) {
            currentIndex += direction;

            // Wrap around logic
            if (currentIndex < 0) {
                currentIndex = images.length - 1;
            } else if (currentIndex >= images.length) {
                currentIndex = 0;
            }

            updateCarousel();
        }

        // Swipe logic for mobile devices
        let startX = 0;
        let endX = 0;

        carousel.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', function (e) {
            endX = e.changedTouches[0].clientX;

            if (startX - endX > 50) {
                // Swipe left
                moveCarousel(1);
            } else if (endX - startX > 50) {
                // Swipe right
                moveCarousel(-1);
            }
        });

        // Initialize carousel
        updateCarousel();
    });
    </script>
</body>

</html>