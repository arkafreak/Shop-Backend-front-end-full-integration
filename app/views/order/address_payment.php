<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address and Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/payment_page.css">
</head>

<body>
    <div class="container">
        <h2>Address and Payment</h2>
        <h4>Order Details</h4>
        <table class="table table-bordered">
            <thead>
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
                            <td><?php echo number_format($item->sellingPrice); ?></td>
                            <td><?php echo htmlspecialchars($item->quantity); ?></td>
                            <td><?php echo number_format($item->sellingPrice * $item->quantity); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No items in cart.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h5>Total Amount: ₹<?php echo number_format($totalAmount); ?></h5>

        <!-- Include a hidden input field to submit the total amount if needed -->
        <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <input type="hidden" name="totalAmount" value="<?php echo number_format($totalAmount); ?>">


        <form action="<?php echo URLROOT; ?>/OrderController/confirm" method="POST">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="addressLine1">Address Line 1:</label>
                    <input type="text" name="addressLine1" id="addressLine1" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="addressLine2">Address Line 2:</label>
                    <input type="text" name="addressLine2" id="addressLine2" class="form-control">
                </div>
            </div>
            <div class="row">
           <div class="col-md-6 form-group">
                    <label for="country">Country:</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="">--Select Country--</option>
                        <option value="india">India</option>
                        <option value="usa">USA</option>
                        <option value="uk">UK</option>
                        <!-- Add more countries as needed -->
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="state">State:</label>
                    <select name="state" id="state" class="form-control" required>
                        <option value="">--Select State--</option>
                        <option value="west_bengal">West Bengal</option>
                        <option value="maharashtra">Maharashtra</option>
                        <option value="delhi">Delhi</option>
                        <!-- Add more states as needed -->
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="city">City:</label>
                    <select name="city" id="city" class="form-control" required>
                        <option value="">--Select City--</option>
                        <option value="kolkata">Kolkata</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="delhi">Delhi</option>
                        <!-- Add more cities as needed -->
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="postalCode">Postal Code:</label>
                    <input type="text" name="postalCode" id="postalCode" class="form-control" required maxlength="6" pattern="\d{6}" title="Please enter a valid 6-digit postal code.">
                </div>
            </div>

            <div class="form-group">
                <label for="paymentMethod">Select a payment method:</label>
                <select name="paymentMethod" id="paymentMethod" class="form-control" required>
                    <option value="">--Choose a payment method--</option>
                    <option value="paypal">PayPal</option>
                    <option value="stripe">Stripe</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Proceed to payment</button>
        </form><br>
        <form action="<?php echo URLROOT; ?>/CartController" method="POST">
            <button type="submit" class="btn btn-primary">GO back</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>