<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/product_edit_style.css"> <!-- Adjust path to your CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/add_image_style.css"> <!-- Add new CSS for image upload -->
</head>

<body>
    <h1>Edit Product</h1>

    <!-- Form to update the product details -->
    <form action="<?php echo URLROOT; ?>/products/edit/<?php echo htmlspecialchars($data['id']); ?>" method="post" enctype="multipart/form-data">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($data['productName']); ?>" required>

        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($data['brand']); ?>" required>

        <label for="originalPrice">Original Price:</label>
        <input type="number" id="originalPrice" name="originalPrice" value="<?php echo htmlspecialchars($data['originalPrice']); ?>" required>

        <label for="sellingPrice">Selling Price:</label>
        <input type="number" id="sellingPrice" name="sellingPrice" value="<?php echo htmlspecialchars($data['sellingPrice']); ?>" required>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($data['weight']); ?>" required>
        <span>Enter "0" for Digital products</span>

        <label for="categoryId">Category:</label>
        <select name="categoryId" id="categoryId" required>
            <?php foreach ($data['categories'] as $category): ?>
                <option value="<?php echo htmlspecialchars($category->id); ?>"
                    <?php echo ($data['categoryId'] == $category->id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category->categoryName); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Image Upload Section -->
        <div class="image-container">
            <label for="productImages">Upload Images:</label>
            <div id="fileInputs">
                <div class="file-input-container">
                    <input type="file" name="productImage[]" id="productImage1" accept=".jpg, .jpeg, .png">
                    <div class="image-preview" id="preview1"></div>
                    <button type="button" class="remove-btn">-</button>
                </div>
            </div>
            <div class="add-more-container">
                <p>Add More</p>
                <span class="add-more-btn" id="addMore">+</span>
            </div>
        </div>

        <input type="submit" value="Update Product">
    </form>

    <div class="button-container">
        <a href="<?php echo URLROOT; ?>/products"><button>Go Back</button></a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addMoreBtn = document.getElementById("addMore");
            const fileInputs = document.getElementById("fileInputs");
            let inputCount = 1;

            addMoreBtn.addEventListener("click", function() {
                inputCount++;
                const newInputContainer = document.createElement("div");
                newInputContainer.classList.add("file-input-container");

                const newInput = document.createElement("input");
                newInput.type = "file";
                newInput.name = "productImage[]";
                newInput.id = `productImage${inputCount}`;
                newInput.accept = ".jpg, .jpeg, .png"; // Restrict file types
                newInput.required = false;

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.textContent = "-";
                removeBtn.classList.add("remove-btn");

                const newImagePreview = document.createElement("div");
                newImagePreview.classList.add("image-preview");
                newImagePreview.id = `preview${inputCount}`;

                removeBtn.addEventListener("click", function() {
                    fileInputs.removeChild(newInputContainer); // Only remove the specific container
                });

                newInput.addEventListener("change", function() {
                    removeBtn.style.display = this.value ? "flex" : "none";
                    previewImage(this, newImagePreview); // Display the image preview
                });

                newInputContainer.appendChild(newInput);
                newInputContainer.appendChild(removeBtn);
                newInputContainer.appendChild(newImagePreview); // Add the preview container
                fileInputs.appendChild(newInputContainer);
            });

            // Function to preview the image
            function previewImage(input, previewContainer) {
                const file = input.files[0];
                if (file) {
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!validTypes.includes(file.type)) {
                        alert("Invalid file type. Please select a JPG, JPEG, or PNG image.");
                        input.value = ''; // Clear the input
                        previewContainer.innerHTML = ''; // Clear the preview
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `<img src="${e.target.result}" alt="Image Preview" class="preview-img">`;
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
</body>

</html>
