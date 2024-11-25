<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Add New Category</h3>
            </div>
            <div class="card-body">
                <!-- Form to add a new category -->
                <form action="<?php echo URLROOT; ?>/categories/add" method="post">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name:</label>
                        <input type="text" name="categoryName" id="categoryName" class="form-control" placeholder="Enter category name" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <!-- Add Category Button -->
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Add Category
                        </button>

                        <!-- Back Button -->
                        <a href="<?php echo URLROOT; ?>/categories" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
