<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/history_style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="<?php echo URLROOT; ?>/products">My Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/products">Products</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Purchase History</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <h2>Your Purchase History</h2>
        <a href="<?php echo URLROOT; ?>/products" class="back-btn text-white btn btn-primary mb-3">Go Back</a>

        <!-- Filter Form -->
        <form method="GET" action="" class="mb-3">
            <label for="filter" class="mr-2">Filter by Date:</label>
            <select name="filter" id="filter" class="form-control d-inline w-auto">
                <option value="15_days" <?php echo isset($_GET['filter']) && $_GET['filter'] == '15_days' ? 'selected' : ''; ?>>Last 15 Days</option>
                <option value="30_days" <?php echo isset($_GET['filter']) && $_GET['filter'] == '30_days' ? 'selected' : ''; ?>>Last 30 Days</option>
                <option value="last_month" <?php echo isset($_GET['filter']) && $_GET['filter'] == 'last_month' ? 'selected' : ''; ?>>Last Month</option>
                <option value="2024" <?php echo isset($_GET['filter']) && $_GET['filter'] == '2024' ? 'selected' : ''; ?>>2024</option>
                <option value="2023" <?php echo isset($_GET['filter']) && $_GET['filter'] == '2023' ? 'selected' : ''; ?>>2023</option>
            </select>
            <button type="submit" class="btn btn-primary ml-2">Apply Filter</button>
        </form>

        <?php
        // Get the selected filter
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

        // Filter products based on the selected filter
        $filteredProducts = [];

        // Get today's date for date comparisons
        $today = new DateTime();

        // Filter products based on the selected time range
        foreach ($products as $product) {
            $purchaseDate = new DateTime($product->purchase_date);
            $dateDiff = $today->diff($purchaseDate)->days;

            switch ($filter) {
                case '15_days':
                    if ($dateDiff <= 15) {
                        $filteredProducts[] = $product;
                    }
                    break;

                case '30_days':
                    if ($dateDiff <= 30) {
                        $filteredProducts[] = $product;
                    }
                    break;

                case 'last_month':
                    if ($purchaseDate->format('Y-m') == $today->modify('-1 month')->format('Y-m')) {
                        $filteredProducts[] = $product;
                    }
                    break;

                case '2024':
                    if ($purchaseDate->format('Y') == '2024') {
                        $filteredProducts[] = $product;
                    }
                    break;

                case '2023':
                    if ($purchaseDate->format('Y') == '2023') {
                        $filteredProducts[] = $product;
                    }
                    break;

                default:
                    $filteredProducts = $products;
                    break;
            }
        }
        ?>

        <?php if (!empty($filteredProducts)): ?>
            <?php
            // Group filtered products by date
            $groupedProducts = [];
            foreach ($filteredProducts as $product) {
                $date = date('Y-m-d', strtotime($product->purchase_date)); // Extract date
                $groupedProducts[$date][] = $product; // Group by date
            }
            ?>
            <?php foreach ($groupedProducts as $date => $group): ?>
                <h3 class="mt-4"><?php echo htmlspecialchars($date); ?></h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($group as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product->productName); ?></td>
                                <td><?php echo htmlspecialchars($product->brand); ?></td>
                                <td><?php echo htmlspecialchars($product->quantity); ?></td>
                                <td>
                                    <?php if ($product->isWithheld == 1): ?>
                                        <span class="badge badge-secondary">Discontinued</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Display Product Image -->
                                    <?php if ($product->image_name): ?>
                                        <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($product->image_name); ?>"
                                            alt="<?php echo htmlspecialchars($product->productName); ?> image" width="50">
                                    <?php else: ?>
                                        <span>No Image Available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">You have not purchased any products yet or no products found for the selected filter.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; <?php echo date("Y"); ?> My Shop. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
