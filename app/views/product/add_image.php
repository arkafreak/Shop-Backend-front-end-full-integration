<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Images</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/add_image_style.css">
</head>

<body>
    <div class="container">
        <h1>Upload Images for Product #<?php echo $data['next_id']; ?></h1>
        <!-- Upload image form -->
        <form action="<?php echo URLROOT; ?>/products/addImage/<?php echo $data['next_id']; ?>" method="post" enctype="multipart/form-data" id="imageForm">
            <div id="fileInputs">
                <div class="file-input-container">
                    <label for="productImage1">Upload Product Images:</label>
                    <input type="file" name="productImage[]" id="productImage1" required>
                    <div class="image-preview" id="preview1"></div>
                    <button type="button" class="remove-btn">-</button>
                    <!-- Image preview -->
                </div>
            </div>
            <div class="add-more-container">
                <p>Add More</p>
                <span class="add-more-btn" id="addMore">+</span>
            </div>
            <button type="submit">Upload Images</button>
        </form>

        <a href="<?php echo URLROOT; ?>/Products">Add image later</a>
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
    </script>
</body>

</html>