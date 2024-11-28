<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address and Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/payment_page.css">
</head>

<body class="bg-light">
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
                                        <td><?php echo htmlspecialchars($item->productName); ?></td>
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

    <!-- Bootstrap JS and dependencies (Updated Popper CDN) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
