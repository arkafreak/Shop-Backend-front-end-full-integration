<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/product_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark frosted-navbar fixed-top">
        <!-- Logo Section -->
        <a href="<?php echo URLROOT; ?>/products" class="navbar-brand">
            <img src="<?php echo URLROOT; ?>/public/images/fox_logo.png" alt="Shopsyyy.com" class="logo-small" style="max-width: 150px;">
            <span class="brand-text fs-4 text-white text-decoration-none d-inline-block position-relative">
                Shopsyyy.com
                <span class="hover-underline position-absolute bottom-0 left-0 w-100 h-1 bg-dark d-none"></span>
            </span>
        </a>


        <!-- Hamburger Icon for Mobile -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ml-auto">
                <!-- Admin Links -->
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a href="<?php echo URLROOT; ?>/products/add" class="btn btn-success mr-2 mb-2"><i class="fas fa-plus"></i>Add New Product</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo URLROOT; ?>/DashboardController/index" class="btn btn-secondary mr-2 mb-2">Dashboard</a>
                    </li>
                    <!-- category button -->
                    <li class="nav-item">
                        <a href="<?php echo URLROOT; ?>/categories" class="btn btn-info mr-2 mb-2"><i class="fas fa-boxes"></i>Go to Categories</a>
                    </li>
                <?php endif; ?>

                <!-- Category and Home Links -->
                <li class="nav-item">
                    <a href="<?php echo URLROOT; ?>/choose/options" class="btn btn-info mr-2 mb-2"><i class="fas fa-home"></i> Home</a>
                </li>

                <!-- Order History Button -->
                <?php if ($_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <form action="<?php echo URLROOT; ?>/OrderController/purchaseHistory" method="POST" style="display: inline;">
                            <button type="submit" class="btn btn-warning">Your Order History</button>
                        </form>
                    </li>
                <?php endif; ?>

                <!-- Cart Icon (Customer Only) -->
                <?php if ($_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a href="<?php echo URLROOT; ?>/CartController/index" class="nav-link text-light btn-lg">
                            <i class="fa fa-shopping-cart"></i> Cart
                            <?php if (isset($data['cartItemCount']) && $data['cartItemCount'] > 0): ?>
                                <span class="badge1 badge-pill badge-danger"><?php echo $data['cartItemCount']; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>


                <!-- Logout Button -->
                <li class="nav-item">
                    <form action="<?php echo URLROOT; ?>/UserController/logout" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Add padding to body to prevent content overlap with fixed navbar -->
    <style>
        body {
            padding-top: 80px;
            /* Adjust this value to match the height of your navbar */
        }
    </style>


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

    <!-- Filter Section -->
    <div class="filter-search-container">
        <!-- Filter Section -->
        <div class="filter-container">
            <form action="<?php echo URLROOT; ?>/products/filter" method="GET" id="filterForm">
                <label for="weightFilter">Filter:</label>
                <select name="weightFilter" id="weightFilter" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Products</option>
                    <option value="zero" <?php echo isset($_GET['weightFilter']) && $_GET['weightFilter'] === 'zero' ? 'selected' : ''; ?>>Digital Products</option>
                    <option value="nonZero" <?php echo isset($_GET['weightFilter']) && $_GET['weightFilter'] === 'nonZero' ? 'selected' : ''; ?>>Physical Products</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-container">
        <form action="<?php echo URLROOT; ?>/products/search" method="GET" id="searchForm">
            <label for="searchQuery" class="sr-only">Search products</label> <!-- Added label for accessibility -->

            <input
                type="text"
                name="query"
                id="searchQuery"
                placeholder="Search products..."
                value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>"
                class="search-input"
                aria-label="Search for products"> <!-- Added aria-label for accessibility -->

            <button type="submit" class="search-button">Search</button>

            <!-- Clear Button -->
            <?php if (isset($_GET['query']) && !empty($_GET['query'])): ?>
                <a href="<?php echo URLROOT; ?>/products/search" class="clear-search">Clear</a> <!-- Clear the search query -->
            <?php endif; ?>
        </form>
    </div>
    <div id="success-message" style="display: <?php echo $displayMessage ? 'block' : 'none'; ?>; color: green; text-align: center; margin-bottom: 20px;">
        <?php
        if ($displayMessage) {
            echo $_SESSION['message'];
            unset($_SESSION['message']); // Clear message after displaying
        }
        ?>
    </div>

    <!-- Product cards -->
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-2 mt-4">
            <?php foreach ($data['products'] as $product):
                if (isset($_GET['weightFilter'])) {
                    if ($_GET['weightFilter'] === 'zero' && $product->weight != 0) continue;
                    if ($_GET['weightFilter'] === 'nonZero' && $product->weight <= 0) continue;
                } ?>
                <div class="col">
                    <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->id; ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-lg border border-grey">
                            <!-- Product Image -->
                            <img src="<?php echo URLROOT; ?>/public/images/<?php echo $product->image_name ? htmlspecialchars($product->image_name) : 'placeholder.jpg'; ?>"
                                alt="Product" class="card-img-top product-img">

                            <!-- Limited Time Deal Badge -->
                            <?php
                            $discount = 0;
                            if ($product->originalPrice > $product->sellingPrice) {
                                $discount = round((($product->originalPrice - $product->sellingPrice) / $product->originalPrice) * 100);
                            }
                            ?>
                            <?php if ($_SESSION['role'] === 'customer'): ?>
                                <?php if ($discount > 60): ?>
                                    <div class="badge bg-danger position-absolute top-0 start-0 m-2 text-white">Limited Time Deal</div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Card Body -->
                            <div class="card-body p-2">
                                <!-- Product Name -->
                                <h5 class="card-title text-dark mb-2 text-truncate" style="max-width: 100%"><?php echo htmlspecialchars($product->productName); ?></h5>

                                <!-- Product Rating -->
                                <div class="product-rating mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $product->rating ? 'text-warning' : 'text-muted'; ?>">★</span>
                                    <?php endfor; ?>
                                    <span class="rating-count">
                                        (<?php echo isset($product->reviewCount) ? htmlspecialchars($product->reviewCount) : '0'; ?>)
                                    </span>
                                </div>

                                <!-- Product Price -->
                                <p class="card-text text-dark fw-bold">
                                    ₹<?php echo number_format($product->sellingPrice); ?>
                                </p>

                                <!-- Original Price and Discount -->
                                <div class=" align-items-center">
                                    <p class="text-muted">M.R.P: <span class="text-muted" style="text-decoration: line-through;">₹<?php echo number_format($product->originalPrice); ?></span>
                                        <?php if ($discount > 0): ?>
                                            <span class="badge text-danger">-<?php echo $discount . "% off"; ?></span>
                                        <?php endif; ?>
                                </div>


                                <!-- Savings -->
                                <p class="text-success">Save ₹<?php echo number_format($product->originalPrice - $product->sellingPrice); ?> with coupon</p>

                                <!-- Estimated Delivery -->
                                <small class="text-muted">Get it by <strong>Monday, December 9</strong></small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="card-footer text-center">
                                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center">
                                    <!-- "View Product" Button -->
                                    <?php if ($_SESSION['role'] === 'customer'): ?>
                                        <button class="btn btn-outline-primary btn-sm mr-2 mb-sm-0 me-sm-2 w-100 w-sm-auto"><i class="fas fa-eye"></i> View</button>
                                    <?php endif; ?>
                                    <!-- Admin Actions -->
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <a href="<?php echo URLROOT; ?>/products/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm mr-2 mb-sm-0 me-sm-2 w-100 w-sm-auto"><i class="fas fa-edit"></i>Edit</a>
                                        <a href="<?php echo URLROOT; ?>/products/delete/<?php echo $product->id; ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="btn btn-danger btn-sm mb-2 mb-sm-0 me-sm-2 w-100 w-sm-auto"><i class="fas fa-trash"></i>Delete</a>
                                    <?php endif; ?>

                                    <!-- Customer Add-to-Cart Button -->
                                    <?php if ($_SESSION['role'] === 'customer'): ?>
                                        <form action="<?php echo URLROOT; ?>/CartController/addToCart" method="POST" class="d-inline-block w-100 w-sm-auto">
                                            <input type="hidden" name="productId" value="<?php echo $product->id; ?>">
                                            <button type="submit" class="btn btn-success btn-sm w-100 w-sm-auto">Add to Cart</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        /* Product Image Styling */
        .product-img {
            width: 100%;
            height: auto;
            object-fit: contain;
            /* Ensures image fits container without distortion */
            max-width: 100%;
            max-height: 250px;
            /* You can adjust the height if needed */
        }
    </style>




    <!-- Footer Section -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <!-- Contact Us Section -->
                <div class="col-md-4">
                    <h3>Contact Us</h3>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-envelope"></i> Email: support@shop.com</li>
                        <li><i class="fa fa-phone"></i> Phone: 123-456-7890</li>
                        <li><i class="fa fa-fax"></i> Fax: 123-456-7891</li>
                    </ul>
                </div>

                <!-- Location Section -->
                <div class="col-md-4">
                    <h3>Location</h3>
                    <p><i class="fa fa-map-marker"></i> 123 Shop Street, City, Country</p>
                </div>

                <!-- Address Section -->
                <div class="col-md-4">
                    <h3>Address</h3>
                    <p><i class="fa fa-building"></i> Shop Inc., 123 Business Ave, Suite 101, City, Country</p>
                </div>
            </div>
            <!-- Optional: Add footer credits or links -->
            <div class="text-center mt-4">
                <p>&copy; 2024 Shopsyyy.com | All rights reserved.</p>
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
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>