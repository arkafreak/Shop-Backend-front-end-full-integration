<?php

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    // Redirect to login if not logged in
    header('Location: ' . URLROOT . '/login');
    exit;
}
// Define the user's name
$userName = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/choose_style.css">
    <title>Home Page</title>
</head>

<body>
    <header>
        <h1>Hey <?php echo $userName; ?>, Welcome to the Shop</h1>

        <!-- Login Success Message -->
        <?php if (!empty($_SESSION['loginMessage'])): ?>
            <p class="message" id="loginMessage"><?php echo $_SESSION['loginMessage']; ?></p>
            <?php unset($_SESSION['loginMessage']); ?>
        <?php endif; ?>

        <!-- Navigation Bar -->
        <div class="top-bar">
            <div class="options">
                <?php if ($_SESSION['role'] === 'customer'): ?>
                    <a href="<?php echo URLROOT; ?>/products"><button>View all Products</button></a>
                <?php endif; ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="<?php echo URLROOT; ?>/products"><button>Products</button></a>
                    <a href="<?php echo URLROOT; ?>/categories"><button>Categories</button></a>
                <?php endif; ?>
            </div>
            <form action="<?php echo URLROOT; ?>/UserController/logout" method="POST" class="logout-form">
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>

    <!-- Product Carousel -->
    <!-- Product Carousel -->
    <div class="product-carousel">
        <div class="product-item">
            <div class="product-image"></div>
            <h3>coca cola</h3>
            <p>$19.99</p>
            <button>Add to Cart</button>
        </div>
        <div class="product-item">
            <div class="product-image"></div>
            <h3>Adidas F14</h3>
            <p>$29.99</p>
            <button>Add to Cart</button>
        </div>
        <div class="product-item">
            <div class="product-image"></div>
            <h3>Apple watch 3</h3>
            <p>$39.99</p>
            <button>Add to Cart</button>
        </div>
        <div class="product-item">
            <div class="product-image"></div>
            <h3>Nikon DSLR 6G</h3>
            <p>$19.99</p>
            <button>Add to Cart</button>
        </div>
        <div class="product-item">
            <div class="product-image"></div>
            <h3>Beuty Products</h3>
            <p>$29.99</p>
            <button>Add to Cart</button>
        </div>
        <div class="product-item">
            <div class="product-image"></div>
            <h3>Beverages</h3>
            <p>$39.99</p>
            <button>Add to Cart</button>
        </div>
    </div>

    <!-- Carousel Controls -->
    <div class="carousel-controls">
        <button onclick="scrollCarousel(-1)">&#8249;</button>
        <button onclick="scrollCarousel(1)">&#8250;</button>
    </div>

    <script>
        // Scroll the product carousel
        function scrollCarousel(direction) {
            const carousel = document.querySelector('.product-carousel');
            const scrollAmount = 300; // Adjust scroll distance
            carousel.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth',
            });
        }

        // Hide the login message after 10 seconds
        setTimeout(() => {
            const message = document.getElementById('loginMessage');
            if (message) message.style.display = 'none';
        }, 10000);
    </script>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><i class="fa fa-envelope"></i> Email: support@shop.com</li>
                    <li><i class="fa fa-phone"></i> Phone: 123-456-7890</li>
                    <li><i class="fa fa-fax"></i> Fax: 123-456-7891</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Location</h3>
                <p><i class="fa fa-map-marker"></i> 123 Shop Street, City, Country</p>
            </div>
            <div class="footer-section">
                <h3>Address</h3>
                <p><i class="fa fa-building"></i> Shop Inc., 123 Business Ave, Suite 101, City, Country</p>
            </div>
        </div>
    </footer>

</body>

</html>