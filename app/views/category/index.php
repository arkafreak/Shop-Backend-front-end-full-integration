<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Categories</h1>

            <!-- Logout Button -->
            <form action="<?php echo URLROOT; ?>/UserController/logout" method="POST">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        <!-- Button Group -->
        <div class="mb-4">
            <div class="btn-group" role="group">
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="<?php echo URLROOT; ?>/categories/add" class="btn btn-success m-1">
                        <i class="fas fa-plus"></i> Add New Category
                    </a>
                <?php endif; ?>

                <a href="<?php echo URLROOT; ?>/products" class="btn btn-primary m-1">
                    <i class="fas fa-boxes"></i> Go to Products
                </a>
                <a href="<?php echo URLROOT; ?>/products" class="btn btn-secondary m-1">
                    <i class="fas fa-home"></i> Home
                </a>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['categories'])): ?>
                            <?php foreach ($data['categories'] as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category->id); ?></td>
                                    <td><?php echo htmlspecialchars($category->categoryName); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                                <a href="<?php echo URLROOT; ?>/categories/edit/<?php echo htmlspecialchars($category->id); ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="<?php echo URLROOT; ?>/categories/delete/<?php echo htmlspecialchars($category->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo URLROOT; ?>/categories/show/<?php echo htmlspecialchars($category->id); ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-muted">No categories found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
