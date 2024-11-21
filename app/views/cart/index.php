<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/cart_style.css">
</head>

<body>
    <h1>Your Shopping Cart</h1>
    <div class="cart-container">


        <?php if (!empty($data['cartItems'])): ?>
            <div class="cart-items">
                <?php
                $totalAmount = 0;
                foreach ($data['cartItems'] as $item):
                    $itemTotal = $item->quantity * $item->sellingPrice;
                    $totalAmount += $itemTotal;
                ?>
                    <div class="cart-item">
                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($item->image_name); ?>" alt="Product" class="cart-item-image">

                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item->productName); ?></h3>
                            <p class="brand">Brand: <?php echo htmlspecialchars($item->brand); ?></p>
                            <p class="price">Price: ₹<?php echo number_format($item->sellingPrice); ?></p>
                            <p class="quantity">Quantity: <?php echo htmlspecialchars($item->quantity); ?></p>
                            <p class="total">Total: ₹<?php echo number_format($itemTotal); ?></p>

                            <div class="quantity-actions">
                                <!-- Decrease quantity -->
                                <form action="<?php echo URLROOT; ?>/CartController/update" method="POST">
                                    <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="quantity-button">-</button>
                                </form>

                                <!-- Increase quantity -->
                                <form action="<?php echo URLROOT; ?>/CartController/update" method="POST">
                                    <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="quantity-button">+</button>
                                </form>

                                <!-- Remove item -->
                                <form action="<?php echo URLROOT; ?>/CartController/removeItem" method="POST">
                                    <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="remove-button">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary" id="cart-summary">
                <h3>Order Summary</h3>
                <p>Total Amount: ₹<?php echo number_format($totalAmount); ?></p>
                <form action="<?php echo URLROOT; ?>/OrderController/addressPayment" method="POST">
                    <button type="submit" class="place-order-button">Place Order</button>
                </form>
                <a href="<?php echo URLROOT; ?>/products" class="back-button">Back to Products</a>
            </div>

        <?php else: ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
                <a href="<?php echo URLROOT; ?>/products" class="continue-shopping-button">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cartSummary = document.getElementById("cart-summary");

            let isDragging = false;
            let offsetX, offsetY;

            cartSummary.addEventListener("touchstart", (e) => {
                const touch = e.touches[0];
                offsetX = touch.clientX - cartSummary.getBoundingClientRect().left;
                offsetY = touch.clientY - cartSummary.getBoundingClientRect().top;
                isDragging = true;
            });

            cartSummary.addEventListener("touchmove", (e) => {
                if (!isDragging) return;

                const touch = e.touches[0];
                const newX = touch.clientX - offsetX;
                const newY = touch.clientY - offsetY;

                cartSummary.style.left = `${newX}px`;
                cartSummary.style.top = `${newY}px`;

                e.preventDefault(); // Prevent scrolling while dragging
            });

            cartSummary.addEventListener("touchend", () => {
                isDragging = false;
            });

            cartSummary.addEventListener("mousedown", (e) => {
                offsetX = e.clientX - cartSummary.getBoundingClientRect().left;
                offsetY = e.clientY - cartSummary.getBoundingClientRect().top;
                isDragging = true;
            });

            document.addEventListener("mousemove", (e) => {
                if (!isDragging) return;

                const newX = e.clientX - offsetX;
                const newY = e.clientY - offsetY;

                cartSummary.style.left = `${newX}px`;
                cartSummary.style.top = `${newY}px`;

                e.preventDefault(); // Prevent text selection while dragging
            });

            document.addEventListener("mouseup", () => {
                isDragging = false;
            });
        });
    </script>
</body>

</html>