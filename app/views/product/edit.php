<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="preload" href="<?php echo URLROOT; ?>/public/css/product_edit_style.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/product_edit_style.css">
    </noscript>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS (Bundle includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100%">

        <form action="<?php echo URLROOT; ?>/products/edit/<?php echo htmlspecialchars($data['id']); ?>" method="post" enctype="multipart/form-data" class="p-4 shadow-sm rounded border">
            <h2 class="text-center mb-4">Edit Product</h2>

            <div class="mb-3">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" id="productName" name="productName" class="form-control" value="<?php echo htmlspecialchars($data['productName']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="brand" class="form-label">Brand:</label>
                <input type="text" id="brand" name="brand" class="form-control" value="<?php echo htmlspecialchars($data['brand']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="originalPrice" class="form-label">Original Price:</label>
                <input type="number" id="originalPrice" name="originalPrice" class="form-control" value="<?php echo htmlspecialchars($data['originalPrice']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="sellingPrice" class="form-label">Selling Price:</label>
                <input type="number" id="sellingPrice" name="sellingPrice" class="form-control" value="<?php echo htmlspecialchars($data['sellingPrice']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="weight" class="form-label">Weight:</label>
                <input type="number" id="weight" name="weight" class="form-control" value="<?php echo htmlspecialchars($data['weight']); ?>" required>
                <small class="form-text text-muted">Enter "0" for Digital products.</small>
            </div>

            <div class="mb-3">
                <label for="categoryId" class="form-label">Category:</label>
                <select name="categoryId" id="categoryId" class="form-select" required>
                    <?php foreach ($data['categories'] as $category): ?>
                        <option value="<?php echo htmlspecialchars($category->id); ?>"
                            <?php echo ($data['categoryId'] == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->categoryName); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Button to show modal -->
            <div class="d-flex justify-content-center mb-4">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#imageModal">
                    Show Existing Images
                </button>
            </div>

            <!-- Image Upload Section -->
            <div class="container">
                <h1>Upload Images for <?php echo htmlspecialchars($data['productName']); ?></h1>
                <div id="fileInputs">
                    <div class="file-input-container">
                        <label for="productImage1">Upload Product Images:</label>
                        <input type="file" name="productImage[]" id="productImage1">
                        <div class="image-preview" id="preview1"></div>
                        <button type="button" class="remove-btn">-</button>
                        <!-- Image preview -->
                    </div>
                </div>
                <div class="add-more-container">
                    <p>Add More</p>
                    <span class="add-more-btn" id="addMore">+</span>
                </div>
            </div>

            <!-- Submit part -->
            <input type="submit" value="Update Product">
            <div class="button-container">
                <a href="<?php echo URLROOT; ?>/products">
                    <button type="button">Go Back</button>
                </a>
            </div>
        </form>

        <!-- Modal for showing existing images -->
        <div id="imageModal" class="modal fade" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Existing Images</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($data['images'])): ?>
                            <div class="row g-3">
                                <?php foreach ($data['images'] as $image): ?>
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="card">
                                            <img src="<?php echo URLROOT; ?>/public/images/<?php echo htmlspecialchars($image->image_name); ?>" alt="Product Image" class="card-img-top img-thumbnail">
                                            <div class="card-body p-2 text-center">
                                                <form action="<?php echo URLROOT; ?>/products/edit/<?php echo htmlspecialchars($data['id']); ?>" method="post">
                                                    <input type="hidden" name="imageId" value="<?php echo htmlspecialchars($image->id); ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center">No images added yet for this product.</p>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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

                const newInputLabel = document.createElement("label");
                newInputLabel.textContent = `Upload Product Image ${inputCount}:`;
                newInputLabel.setAttribute("for", `productImage${inputCount}`);

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
                newImagePreview.id = `preview${inputCount}`; // Unique ID for each preview

                removeBtn.addEventListener("click", function() {
                    fileInputs.removeChild(newInputContainer); // Only remove the specific container
                });

                newInput.addEventListener("change", function() {
                    removeBtn.style.display = this.value ? "flex" : "none";
                    previewImage(this, newImagePreview); // Display the image preview
                });

                newInputContainer.appendChild(newInputLabel);
                newInputContainer.appendChild(newInput);
                newInputContainer.appendChild(removeBtn);
                newInputContainer.appendChild(newImagePreview); // Add the preview container
                fileInputs.appendChild(newInputContainer);
            });

            // Handle the initial remove button visibility for the first file input
            const initialRemoveBtn = document.querySelector(".remove-btn");
            const initialFileInput = document.querySelector("#productImage1");
            const initialPreview = document.querySelector("#preview1");

            initialFileInput.addEventListener("change", function() {
                initialRemoveBtn.style.display = this.value ? "flex" : "none";
                previewImage(this, initialPreview); // Display the image preview
            });

            initialRemoveBtn.addEventListener("click", function() {
                const firstInputContainer = document.querySelector(".file-input-container");
                if (firstInputContainer) {
                    fileInputs.removeChild(firstInputContainer); // Only remove the first input container
                }
            });

            // Validate if at least one file is selected before form submission
            const imageForm = document.getElementById("imageForm");
            imageForm.addEventListener("submit", function(event) {
                const fileInputs = document.querySelectorAll("input[type='file']");
                let isValid = false;

                fileInputs.forEach(input => {
                    if (input.files.length > 0) {
                        isValid = true;
                    }
                });

                if (!isValid) {
                    alert("Please select at least one image to upload.");
                    event.preventDefault(); // Prevent form submission
                }
            });

            // Function to preview the image
            function previewImage(input, previewContainer) {
                const file = input.files[0];
                if (file) {
                    // Validate file type
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
        // Get elements
        const showImagesBtn = document.getElementById('showImagesBtn');
        const imageModal = document.getElementById('imageModal');
        const closeBtn = document.querySelector('.close-btn');
        const body = document.querySelector('body');

        // Show the modal
        showImagesBtn.addEventListener('click', function() {
            imageModal.style.display = 'flex'; // Show the modal
            body.classList.add('modal-open'); // Apply blur to background
        });

        // Close the modal
        closeBtn.addEventListener('click', function() {
            imageModal.style.display = 'none'; // Hide the modal
            body.classList.remove('modal-open'); // Remove blur effect
        });

        // Close the modal if user clicks outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === imageModal) {
                imageModal.style.display = 'none'; // Hide the modal
                body.classList.remove('modal-open'); // Remove blur effect
            }
        });
    </script>
</body>

</html>