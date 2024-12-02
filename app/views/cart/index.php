<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo URLROOT; ?>/products" class="text-white text-decoration-none h4">Shopsyyy</a>
                <nav class="d-flex">
                    <a href="<?php echo URLROOT; ?>/products" class="btn btn-outline-light me-2">Home</a>
                    <a href="<?php echo URLROOT; ?>/CartController/index" class="btn btn-outline-light me-2">Cart</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                        <a href="<?php echo URLROOT; ?>/OrderController/purchaseHistory" class="btn btn-outline-light">Order History</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content: Cart -->
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
                                <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($item->image_name); ?>" alt="Product" class="img-fluid rounded"
                                alt="image" width="200">
                            </div>
                            <div class="col-8 col-md-6">
                                <h5 class="mb-2 text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->productName); ?></h5>
                                <p class="text-muted mb-1">Brand: <?php echo htmlspecialchars($item->brand); ?></p>
                                <p class="mb-1">Price: ₹<?php echo number_format($item->sellingPrice); ?></p>
                                <p class="mb-1">Quantity: <?php echo htmlspecialchars($item->quantity); ?></p>
                                <p class="fw-bold mb-2">Total: ₹<?php echo number_format($itemTotal); ?></p>

                                <!-- Quantity Actions -->
                                <div class="d-flex">
                                    <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">-</button>
                                    </form>

                                    <!-- Check stock and disable "+" button if necessary -->
                                    <?php
                                    $disablePlus = ''; // By default, allow the increase button
                                    $stockMessage = ''; // No stock message by default
                                    if ($item->quantity >= $item->stock) {
                                        $disablePlus = 'disabled'; // Disable the plus button if quantity is equal to stock
                                        $stockMessage = "Only {$item->stock} in stock"; // Show stock message
                                    }
                                    ?>
                                    <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="btn btn-outline-primary btn-sm" <?php echo $disablePlus; ?>>+</button>
                                    </form>

                                    <form action="<?php echo URLROOT; ?>/CartController/removeItem" method="POST">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>

                                <!-- Stock Message -->
                                <?php if (!empty($stockMessage)): ?>
                                    <p class=" txt text-warning mt-2"><?php echo $stockMessage; ?></p>
                                    <style>
                                        .txt{
                                            color: orangered !important;
                                        }
                                    </style>
                                <?php endif; ?>
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
                        <button type="submit" class="btn btn-success w-100 mb-3"
                            <?php echo empty($data['stockIssues']) ? '' : 'disabled'; ?>>
                            Place Order
                        </button>
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

    <!-- Stock Warning Modal -->
    <div class="modal fade" id="stockWarningModal" tabindex="-1" aria-labelledby="stockWarningModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="stockWarningModalLabel">Stock Warning</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="stockWarnings" class="list-unstyled"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Shop4. All Rights Reserved.</p>
            <p>Designed by <a href="#" class="text-white text-decoration-none">&copy; arkafreak</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const stockIssues = <?php echo json_encode($data['stockIssues']); ?>;

            if (stockIssues.length > 0) {
                const stockWarnings = document.getElementById('stockWarnings');

                stockIssues.forEach(issue => {
                    const warningItem = document.createElement('li');
                    warningItem.innerHTML = `
                        <strong>${issue.productName}</strong>: 
                        Available Stock: ${issue.availableStock}, 
                        Requested: ${issue.requestedQuantity}
                    `;
                    stockWarnings.appendChild(warningItem);
                });

                const stockWarningModal = new bootstrap.Modal(document.getElementById('stockWarningModal'));
                stockWarningModal.show();
            }
        });
    </script>
</body>

</html>