<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Additional custom styling for better spacing and design */
        .carousel-item {
            transition: transform 1s ease-in-out;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            border-radius: 10px;
        }

        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            font-size: 0.9rem;
        }

        .footer-icon {
            margin-right: 10px;
        }

        .footer-contact ul {
            list-style-type: none;
            padding-left: 0;
        }

        .footer-contact li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Shopsyyy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($_SESSION['role'] === 'customer'): ?>
                        <li class="nav-item">
                            <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary mr-2 mb-2" a>View Products</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary mr-2 mb-2">Products</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo URLROOT; ?>/categories" class="btn btn-primary mr-2 mb-2">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo URLROOT; ?>/DashboardController/index" class="btn btn-primary mr-2 mb-2">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <form action="<?php echo URLROOT; ?>/UserController/logout" method="POST">
                            <button class="btn btn-danger btn-sm ms-3" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

    <!-- Welcome Section -->
    <header class="container mt-4 text-center">
        <?php if (!empty($userName)): ?>
            <h1 class="mb-3">Hey <?php echo $userName; ?>, Welcome to the Shop!</h1>
        <?php else: ?>
            <h1 class="mb-3">Hey, Welcome to the Shopsyyy!</h1>
        <?php endif; ?>

        <?php if (!empty($_SESSION['loginMessage'])): ?>
            <div id="loginMessage" class="alert alert-success">
                <?php echo $_SESSION['loginMessage']; ?>
                <?php unset($_SESSION['loginMessage']); ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- Product Carousel -->
    <div id="productCarousel" class="carousel slide container my-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="card">
                    <img src="<?php echo URLROOT; ?>/public/images/coca-cola.jpg" class="card-img-top" alt="Coca Cola" style="width: 250px; height: 250px; object-fit: cover; margin: 0 auto;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Coca Cola</h5>
                        <p class="card-text">$19.99</p>
                        <button class="btn btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <img src="<?php echo URLROOT; ?>/public/images/Shoe.jpg" class="card-img-top" alt="Adidas F14" style="width: 250px; height: 250px; object-fit: cover; margin: 0 auto;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Adidas F14</h5>
                        <p class="card-text">$29.99</p>
                        <button class="btn btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="card">
                    <img src="<?php echo URLROOT; ?>/public/images/smart_watch.jpeg" class="card-img-top" alt="Apple Watch 3" style="width: 250px; height: 250px; object-fit: cover; margin: 0 auto;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Apple Watch 3</h5>
                        <p class="card-text">$39.99</p>
                        <button class="btn btn-primary">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Footer Section -->
    <footer class="bg-primary text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-contact">
                    <h5><i class="fas fa-envelope footer-icon"></i>Contact Us</h5>
                    <ul>
                        <li><i class="fas fa-envelope"></i> Email: support@shop.com</li>
                        <li><i class="fas fa-phone"></i> Phone: 123-456-7890</li>
                        <li><i class="fas fa-fax"></i> Fax: 123-456-7891</li>
                    </ul>
                </div>
                <div class="col-md-4 footer-contact">
                    <h5><i class="fas fa-map-marker-alt footer-icon"></i>Location</h5>
                    <p>123 Shop Street, City, Country</p>
                </div>
                <div class="col-md-4 footer-contact">
                    <h5><i class="fas fa-building footer-icon"></i>Address</h5>
                    <p>Shop Inc., 123 Business Ave, Suite 101, City, Country</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hide login message after 10 seconds
        setTimeout(() => {
            const message = document.getElementById('loginMessage');
            if (message) message.style.display = 'none';
        }, 10000);
    </script>

</body>

</html>