<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional Custom CSS -->
    <!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/cart_style.css"> -->
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-dark">Your Shopping Cart</h1>

        <!-- Cart Container -->
        <div class="cart-container">
            <?php if (!empty($data['cartItems'])): ?>
                <!-- Cart Items -->
                <div class="cart-items mb-4">
                    <?php
                    $totalAmount = 0;
                    foreach ($data['cartItems'] as $item):
                        $itemTotal = $item->quantity * $item->sellingPrice;
                        $totalAmount += $itemTotal;
                    ?>
                        <div class="cart-item row align-items-center border rounded shadow-sm mb-3 p-2 bg-white">
                            <div class="col-4 col-md-3">
                                <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($item->image_name); ?>" alt="Product" class="img-fluid rounded">
                            </div>
                            <div class="col-8 col-md-6">
                                <h5 class="mb-2 text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->productName); ?></h5>
                                <p class="text-muted mb-1">Brand: <?php echo htmlspecialchars($item->brand); ?></p>
                                <p class="mb-1">Price: ₹<?php echo number_format($item->sellingPrice); ?></p>
                                <p class="mb-1">Quantity: <?php echo htmlspecialchars($item->quantity); ?></p>
                                <p class="fw-bold mb-2">Total: ₹<?php echo number_format($itemTotal); ?></p>

                                <!-- Quantity Actions -->
                                <div class="d-flex">
                                    <!-- Decrease Quantity -->
                                    <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">-</button>
                                    </form>

                                    <!-- Increase Quantity -->
                                    <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">+</button>
                                    </form>

                                    <!-- Remove Item -->
                                    <form action="<?php echo URLROOT; ?>/CartController/removeItem" method="POST">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary p-3 bg-white rounded shadow-sm border">
                    <h3 class="mb-3">Order Summary</h3>
                    <p><strong>Total Amount:</strong> ₹<?php echo number_format($totalAmount); ?></p>

                    <!-- Place Order Button -->
                    <form action="<?php echo URLROOT; ?>/OrderController/addressPayment" method="POST">
                        <button type="submit" class="btn btn-success w-100 mb-3">Place Order</button>
                    </form>

                    <!-- Back to Products Button -->
                    <a href="<?php echo URLROOT; ?>/products" class="btn btn-outline-secondary w-100">Back to Products</a>
                </div>
            <?php else: ?>
                <!-- Empty Cart -->
                <div class="empty-cart text-center">
                    <p>Your cart is empty.</p>
                    <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary">Continue Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
