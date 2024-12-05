<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Canceled</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container text-center mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3>Order Canceled</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">
                            We're sorry! Your order has been canceled as the payment was not completed in time.
                        </p>
                        <p class="text-muted">
                            If you need assistance, feel free to contact our support team.
                        </p>
                        <div class="mt-4">
                            <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary me-3">
                                <i class="bi bi-house-door-fill"></i> Back to Home
                            </a>
                            <a href="<?php echo URLROOT; ?>/CartController/index" class="btn btn-secondary">
                                <i class="bi bi-cart-fill"></i> View Cart
                            </a>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        Thank you for visiting our store!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>