<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>Edit Category</h3>
            </div>
            <div class="card-body">
                <!-- Form to update the category details -->
                <form action="<?php echo URLROOT; ?>/categories/edit/<?php echo htmlspecialchars($data['id']); ?>" method="post">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name:</label>
                        <input type="text" id="categoryName" name="categoryName" class="form-control" value="<?php echo htmlspecialchars($data['categoryName']); ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <!-- Update Button -->
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Category
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
