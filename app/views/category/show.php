<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <!-- Category Header -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Category: <?php echo htmlspecialchars($data['category']->categoryName); ?></h3>
            </div>
            <div class="card-body">
                <!-- Products Table -->
                <h4 class="mb-3">Products under this Category:</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Product Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['products'])): ?>
                                <?php foreach ($data['products'] as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->productName); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="1" class="text-muted">No products found under this category.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Back Button -->
                <div class="d-flex justify-content-end">
                    <a href="<?php echo URLROOT; ?>/categories" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
