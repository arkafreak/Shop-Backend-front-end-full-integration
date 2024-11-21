<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/add_product_style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Admin Panel - Add Product</h1>
        </div>

        <div class="form-container">
            <h2>Product Information</h2>
            <form action="<?php echo URLROOT; ?>/products/add" method="post">
                <div>
                    <label for="productName">Product Name:</label>
                    <input type="text" name="productName" id="productName" required>
                </div>
                <div>
                    <label for="brand">Brand:</label>
                    <input type="text" name="brand" id="brand" required>
                </div>
                <div>
                    <label for="originalPrice">Original Price:</label>
                    <input type="number" name="originalPrice" id="originalPrice" required>
                </div>
                <div>
                    <label for="sellingPrice">Selling Price:</label>
                    <input type="number" name="sellingPrice" id="sellingPrice" required>
                </div>
                <div>
                    <label for="weight">Weight:</label>
                    <input type="decimal" name="weight" id="weight" required>
                    <span class="hint">Enter "0" for Digital products</span>
                </div>
                <div class="full-width">
                    <label for="categoryId">Category:</label>
                    <select name="categoryId" id="categoryId" required>
                    <option value="">--Select Category--</option>
                        <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?php echo htmlspecialchars($category->id); ?>"
                                <?php echo (isset($data['categoryId']) && $data['categoryId'] == $category->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->categoryName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="action-buttons full-width">
                    <button type="submit">Add Product</button>
                    <a href="<?php echo URLROOT; ?>/products">
                        <button type="button">Go Back</button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
