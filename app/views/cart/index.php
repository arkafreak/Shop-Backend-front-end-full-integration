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
    <!-- <td>
        <img
            style="max-width: 400px; 
               text-align: center; 
               padding: 20px 0; 
               background-color: white; 
               mix-blend-mode: multiply; 
               filter: brightness(1) contrast(601%) ;"
            src="https://w7.pngwing.com/pngs/1012/770/png-transparent-amazon-logo-amazon-com-amazon-video-logo-company-brand-amazon-logo-miscellaneous-wish-text.png"
            alt="Company Logo" />
    </td> -->

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
                    $hasDiscontinued = false; // Flag to track if there's any discontinued product
                    foreach ($data['cartItems'] as $item):
                        $itemTotal = $item->quantity * $item->sellingPrice;
                        $totalAmount += $itemTotal;
                        $isDiscontinued = $item->isWithheld == 1; // Check if the product is discontinued
                        if ($isDiscontinued) {
                            $hasDiscontinued = true; // Set flag if discontinued product is found
                        }
                        $isMaxQuantity = $item->quantity >= $item->stock; // Check if the quantity has reached the stock limit
                    ?>
                        <div class="cart-item row align-items-center border rounded shadow-sm mb-3 p-2 bg-white position-relative">
                            <div class="col-4 col-md-3 position-relative">
                                <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($item->image_name); ?>" alt="Product" class="img-fluid rounded" width="200" <?php echo $isDiscontinued ? 'class="blurred"' : ''; ?>>

                                <!-- Discontinued Overlay -->
                                <?php if ($isDiscontinued): ?>
                                    <div class="discontinued-overlay">
                                        <span>Discontinued</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-8 col-md-6">
                                <h5 class="mb-2 text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($item->productName); ?></h5>
                                <p class="text-muted mb-1">Brand: <?php echo htmlspecialchars($item->brand); ?></p>
                                <p class="mb-1">Price: ₹<?php echo number_format($item->sellingPrice); ?></p>
                                <p class="mb-1">Quantity: <?php echo htmlspecialchars($item->quantity); ?></p>
                                <p class="fw-bold mb-2">Total: ₹<?php echo number_format($itemTotal); ?></p>

                                <!-- Quantity Actions (Disabled if discontinued or max quantity reached) -->
                                <div class="d-flex">
                                    <?php if (!$isDiscontinued): ?>
                                        <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                            <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">-</button>
                                        </form>

                                        <form action="<?php echo URLROOT; ?>/CartController/update" method="POST" class="me-2">
                                            <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-outline-primary btn-sm" <?php echo $isMaxQuantity ? 'disabled' : ''; ?>>+</button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="<?php echo URLROOT; ?>/CartController/removeItem" method="POST">
                                        <input type="hidden" name="productId" value="<?php echo $item->id; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>

                                <!-- Stock Message (Only show for available stock) -->
                                <?php if (!$isDiscontinued && $item->quantity >= $item->stock): ?>
                                    <p class="txt text-warning mt-2"><?php echo "{$item->stock} in stock"; ?></p>
                                    <style>
                                        .txt {
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

                    <!-- Place Order Button (Disabled if any product is discontinued) -->
                    <form action="<?php echo URLROOT; ?>/OrderController/addressPayment" method="POST">
                        <button type="submit" class="btn btn-success w-100 mb-3" <?php echo $hasDiscontinued ? 'disabled' : ''; ?>
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


        <style>
            /* Improved discontinued overlay */
            .discontinued-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                /* Darker red overlay for better contrast */
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                font-size: 24px;
                /* Slightly larger font size for better visibility */
                font-weight: bold;
                /* transform: rotate(-45deg); */
                pointer-events: stroke;
                cursor: pointer;
                /* Prevent interference with other interactions */
                z-index: 1;
                /* Ensure overlay stays on top of the image */
                opacity: 0.8;
                /* Slight transparency for the overlay */
            }

            .discontinued-text {
                /* transform: rotate(-45deg); */
                /* Ensure text is readable */
                text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.9);
                /* Add text shadow for better visibility */
                font-size: 40px;
            }

            /* Blurred effect for the background */
            .blurred {
                position: relative;
            }

            .blurred img {
                filter: blur(6px);
                /* Apply blur effect to the image */
                transition: filter 0.3s ease;
                /* Smooth transition for blur effect */
            }

            /* Additional styling for the cart image container */
            .cart-item img {
                transition: transform 0.3s ease;
                /* Smooth transition for image transformations */
            }

            .cart-item:hover img {
                transform: scale(1.05);
                /* Slight zoom effect on hover for better interaction */
            }
        </style>


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