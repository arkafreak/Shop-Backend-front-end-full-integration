<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/product_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="<?php echo URLROOT; ?>/products" class="navbar-logo-link">
                <img src="<?php echo URLROOT; ?>/public/images/fox_logo.png" alt="Shopsyyy.com" class="logo-small">
                Shopsyyy.com
            </a>
        </div>
        <div class="filter-section">
            <form action="<?php echo URLROOT; ?>/products/filter" method="GET" id="filterForm">
                <label for="weightFilter">Filter:</label>
                <select name="weightFilter" id="weightFilter" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Products</option>
                    <option value="zero" <?php echo isset($_GET['weightFilter']) && $_GET['weightFilter'] === 'zero' ? 'selected' : ''; ?>>Digital Products</option>
                    <option value="nonZero" <?php echo isset($_GET['weightFilter']) && $_GET['weightFilter'] === 'nonZero' ? 'selected' : ''; ?>>Physical Products</option>
                </select>
            </form>
        </div>



        <!-- Hamburger Icon for Mobile -->
        <div class="hamburger" id="hamburger-icon">
            <i class="fa fa-bars"></i>
        </div>

        <!-- Navbar Menu -->
        <div class="navbar-menu" id="navbar-menu">
            <div class="button-container">
                <div class="button-group">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo URLROOT; ?>/products/add"><button>Add New Product</button></a>
                        <a href="<?php echo URLROOT; ?>/DashboardController/index"><button>Dashboard</button></a>
                    <?php endif; ?>
                    <a href="<?php echo URLROOT; ?>/categories"><button>Go to categories</button></a>
                    <a href="<?php echo URLROOT; ?>/choose/options"><button>Home</button></a>
                </div>

                <?php if ($_SESSION['role'] === 'customer'): ?>
                    <div class="cart-icon">
                        <a href="<?php echo URLROOT; ?>/CartController/index"><i class="fa fa-shopping-cart">Cart</i></a>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/UserController/logout" method="POST" style="display: inline;">
                    <button type="submit" class="red-button">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <?php
    // Initialize the variable at the top of your view
    $displayMessage = false; // Default value

    // Check if the session message and its timestamp are set
    if (isset($_SESSION['message']) && isset($_SESSION['message_time'])) {
        // Check if 3 seconds have passed
        if (time() - $_SESSION['message_time'] <= 3) {
            $displayMessage = true; // Message should be displayed
        } else {
            unset($_SESSION['message']); // Clear message after 3 seconds
            unset($_SESSION['message_time']); // Clear timestamp
        }
    }
    ?>

    <div id="success-message" style="display: <?php echo $displayMessage ? 'block' : 'none'; ?>; color: green; text-align: center; margin-bottom: 20px;">
        <?php
        if ($displayMessage) {
            echo $_SESSION['message'];
            unset($_SESSION['message']); // Clear message after displaying
        }
        ?>
    </div>

    <!-- Product cards -->
    <div class="product-cards">
        <?php foreach ($data['products'] as $product):
            if (isset($_GET['weightFilter'])) {
                if ($_GET['weightFilter'] === 'zero' && $product->weight != 0) continue;
                if ($_GET['weightFilter'] === 'nonZero' && $product->weight <= 0) continue;
            } ?>
            <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->id; ?>" class="product-card-link">
                <div class="product-card">
                    <!-- Product Image -->
                    <img src="<?php echo URLROOT; ?>/public/images/<?php echo $product->image_name ? htmlspecialchars($product->image_name) : 'placeholder.jpg'; ?>"
                        alt="Product"
                        class="product-image">

                    <!-- Limited Time Deal Badge -->
                    <?php
                    $discount = 0;
                    if ($product->originalPrice > $product->sellingPrice) {
                        $discount = round((($product->originalPrice - $product->sellingPrice) / $product->originalPrice) * 100);
                    }
                    ?>
                    <?php if ($discount > 60): ?>
                        <div class="limited-deal">Limited Time Deal</div>
                    <?php endif; ?>

                    <!-- Product Name -->
                    <div class="product-name"><?php echo htmlspecialchars($product->productName); ?></div>

                    <!-- Product Rating -->
                    <div class="product-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= $product->rating ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                        <span class="rating-count">
                            (<?php echo isset($product->reviewCount) ? htmlspecialchars($product->reviewCount) : '0'; ?>)
                        </span>
                    </div>

                    <!-- Product Price -->
                    <div class="product-price">
                        ₹<?php echo number_format($product->sellingPrice); ?>
                    </div>

                    <!-- Original Price and Discount -->
                    <div class="product-original-price">
                        M.R.P:
                        <span class="original-price">₹<?php echo number_format($product->originalPrice); ?></span>
                        <span class="discount-percent">
                            (<?php
                                $discount = 0;
                                if ($product->originalPrice > $product->sellingPrice) {
                                    $discount = round((($product->originalPrice - $product->sellingPrice) / $product->originalPrice) * 100);
                                    echo $discount . "% off";
                                }
                                ?>)
                        </span>
                    </div>

                    <!-- Savings -->
                    <div class="product-savings">
                        Save ₹<?php echo number_format($product->originalPrice - $product->sellingPrice); ?> with coupon
                    </div>

                    <!-- Estimated Delivery -->
                    <div class="product-delivery">
                        Get it by <strong>Monday, December 9</strong>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card-actions">
                        <button>view product</button>

                        <!-- Admin Actions -->
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $product->id; ?>"><button>Edit</button></a>
                            <a href="<?php echo URLROOT; ?>/products/delete/<?php echo $product->id; ?>" onclick="return confirm('Are you sure you want to delete this product?');">
                                <button class="red-button">Delete</button>
                            </a>
                        <?php endif; ?>

                        <!-- Customer Add-to-Cart Button -->
                        <?php if ($_SESSION['role'] === 'customer'): ?>
                            <form action="<?php echo URLROOT; ?>/CartController/addToCart" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="productId" value="<?php echo $product->id; ?>">
                                <button type="submit" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>






    <br>
    <!-- <a href="<?php echo URLROOT; ?>/digital"><button>Digital Products</button></a>&nbsp; -->
    <!-- <a href="<?php echo URLROOT; ?>/physical"><button>Physical Products</button></a>&nbsp; -->
    <form action="<?php echo URLROOT; ?>/OrderController/history" method="POST" style="display: inline;">
        <button type="submit" class="red-button">Your Order History</button>
    </form>
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

    <script>
        function addToCart(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const successMessage = document.getElementById('success-message');
                        successMessage.innerText = data.message;
                        successMessage.style.display = 'block';
                        const button = form.querySelector('.add-to-cart-button');
                        button.innerText = 'Already Added';
                        button.style.backgroundColor = 'grey';
                        button.disabled = true;
                        setTimeout(() => successMessage.style.display = 'none', 3000);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Add the "shrink" class to the navbar when scrolling
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shrink');
            } else {
                navbar.classList.remove('shrink');
            }
        });

        // Toggle the navbar menu visibility on hamburger click
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const navbarMenu = document.getElementById('navbar-menu');

        document.getElementById('hamburger-icon').addEventListener('click', function() {
            const menu = document.getElementById('navbar-menu');
            menu.style.display = (menu.style.display === 'block' ? 'none' : 'block');
        });
    </script>

</body>

</html>