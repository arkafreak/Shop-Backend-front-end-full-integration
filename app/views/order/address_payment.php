<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address and Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/payment_page.css">

    <style>
        /* Style for the countdown timer */
        .countdown-timer {
            background-color: #ffcc00;
            color: #000;
            font-size: 14px;
            /* Reduced font size */
            text-align: center;
            padding: 10px 0;
            /* Reduced padding */
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
            box-sizing: border-box;
        }

        /* Make countdown timer responsive */
        @media (max-width: 768px) {
            .countdown-timer {
                font-size: 12px;
                /* Adjust font size on smaller screens */
                padding: 10px 0;
                /* Adjust padding on smaller screens */
            }
        }

        /* Adjust navbar padding to ensure no overlap with the countdown timer */
        nav.navbar {
            margin-top: 40px;
            /* Add margin to navbar to avoid overlap */
        }
    </style>

</head>

<body class="bg-light">

    <!-- Countdown Timer -->
    <div class="countdown-timer" id="countdown">
        Time left for checkout: <span id="timer"></span>
    </div>

    <!-- Header Section (Navigation Bar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="<?php echo URLROOT; ?>/products">Your Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2 btn btn-outline-light rounded-pill" href="<?php echo URLROOT; ?>/products">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2 btn btn-outline-light rounded-pill" href="<?php echo URLROOT; ?>/OrderController/purchaseHistory">Order History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2 btn btn-outline-light rounded-pill" href="<?php echo URLROOT; ?>/CartController/index">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light px-3 py-2 btn btn-danger rounded-pill" href="<?php echo URLROOT; ?>/UserController/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <h2 class="text-center mb-4">Address and Payment</h2>
        <h4 class="mb-4">Order Details</h4>

        <!-- Order Table (responsive) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cartItems)): ?>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item->productName); ?>
                                            <?php if ($item->image_name): ?>
                                                <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($item->image_name); ?>" alt="<?php echo htmlspecialchars($item->productName); ?> image" width="50">
                                            <?php else: ?>
                                                <span>No Image Available</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item->brand); ?></td>
                                        <td>₹<?php echo number_format($item->sellingPrice); ?></td>
                                        <td><?php echo htmlspecialchars($item->quantity); ?></td>
                                        <td>₹<?php echo number_format($item->sellingPrice * $item->quantity); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No items in cart.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <h5 class="text-right">Total Amount: ₹<?php echo number_format($totalAmount); ?></h5>
            </div>
        </div>

        <!-- Address and Payment Form -->
        <form action="<?php echo URLROOT; ?>/OrderController/confirm" method="POST" class="card shadow-sm p-4">
            <h5 class="mb-4">Shipping Address</h5>

            <!-- Address Form Fields -->
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="addressLine1">Address Line 1</label>
                    <input type="text" name="addressLine1" id="addressLine1" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="addressLine2">Address Line 2</label>
                    <input type="text" name="addressLine2" id="addressLine2" class="form-control">
                </div>
            </div>

            <!-- Country, State, City, and Postal Code -->
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="">--Select Country--</option>
                        <option value="india">India</option>
                        <option value="usa">USA</option>
                        <option value="uk">UK</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="state">State</label>
                    <select name="state" id="state" class="form-control" required>
                        <option value="">--Select State--</option>
                        <option value="west_bengal">West Bengal</option>
                        <option value="maharashtra">Maharashtra</option>
                        <option value="delhi">Delhi</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="city">City</label>
                    <select name="city" id="city" class="form-control" required>
                        <option value="">--Select City--</option>
                        <option value="kolkata">Kolkata</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="delhi">Delhi</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="postalCode">Postal Code</label>
                    <input type="text" name="postalCode" id="postalCode" class="form-control" required maxlength="6" pattern="\d{6}" title="Please enter a valid 6-digit postal code.">
                </div>
            </div>

            <!-- Payment Method Selection -->
            <div class="form-group">
                <label for="paymentMethod">Select a Payment Method</label>
                <select name="paymentMethod" id="paymentMethod" class="form-control" required>
                    <option value="">--Choose a payment method--</option>
                    <option value="paypal">PayPal</option>
                    <option value="stripe">Stripe</option>
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success btn-lg w-100 mb-3">Proceed to Payment</button>
            </div>
        </form>

        <!-- Back to Cart Button -->
        <form action="<?php echo URLROOT; ?>/CartController" method="POST" class="text-center">
            <button type="submit" class="btn btn-dark btn-lg w-20">Go Back to Cart</button>
        </form>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 Your Store. All Rights Reserved.</p>
            <p>Follow us on:
                <a href="#" class="text-white ml-2">Facebook</a> |
                <a href="#" class="text-white ml-2">Twitter</a> |
                <a href="#" class="text-white ml-2">Instagram</a>
            </p>
        </div>
    </footer>

    <!-- JavaScript for Countdown Timer and Redirect -->
    <script>
        // Countdown timer logic
        var countdownElement = document.getElementById('timer');
        var secondsLeft = 120; // 1 minutes countdown

        function updateCountdown() {
            var minutes = Math.floor(secondsLeft / 60);
            var seconds = secondsLeft % 60;
            countdownElement.innerHTML = minutes + "m " + seconds + "s";
            secondsLeft--;

            // Redirect when countdown ends
            if (secondsLeft < 0) {
                window.location.href = "<?php echo URLROOT; ?>/CartController"; // Redirect to cart
            }
        }

        // Start the countdown
        setInterval(updateCountdown, 1000);
    </script>

</body>

</html>