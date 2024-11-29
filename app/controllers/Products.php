<?php
class Products extends Controller
{
    private $productModel;
    private $categoryModel;
    private $cartModel;

    public function __construct()
    {
        // Start session and check if user is logged in
        Helper::startSession();
        // if (!Helper::isLoggedIn()) {
        //     Helper::redirect(URLROOT . "/UserController/login"); // Redirect to login if not authenticated
        // }

        // Initialize the Product and Category models
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->cartModel = $this->model('CartModel');
    }

    public function index()
    {
        // Retrieve userId from session
        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['role'] ?? null;  // Get the user's role

        // Initialize cart item count
        $cartItemCount = 0;
        if ($userId) {
            $cartItemCount = $this->cartModel->count($userId);
            $cartItemCount = isset($cartItemCount->count) ? (int)$cartItemCount->count : 0;
        }

        // Retrieve products based on user role
        if ($role === 'admin') {
            // Admin can view all products, including withheld ones
            $products = $this->productModel->getAllProductsWithImages();
        } else {
            // Customers cannot see withheld products
            $products = $this->productModel->getAllProductsWithImages(['isWithheld' => 0]);
        }

        // Add isInCart property for each product
        if ($userId) {
            foreach ($products as $product) {
                $product->isInCart = $this->cartModel->isProductInCart($product->id, $userId);
            }
        } else {
            foreach ($products as $product) {
                $product->isInCart = false; // Default to false if the user is not logged in
            }
        }

        // Prepare data to pass to the view
        $data = [
            'products' => $products,
            'cartItemCount' => $cartItemCount,
        ];

        // Load the view
        $this->view('product/index', $data);
    }






    public function add()
    {
        // Only allow access for admin role
        if (!Helper::isAdmin()) {
            Helper::redirect(URLROOT . "/products");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and filter input
            $_PRODUCT = filter_input_array(INPUT_POST);
            $data = [];
            foreach (Helper::getProductFields() as $field) {
                $data[$field] = Helper::sanitizeInput($_PRODUCT[$field] ?? '');
            }

            // Add product to the database and get the product_id
            $product_id = $this->productModel->add($data);

            $last_id = $this->productModel->getLastProductId();
            echo $last_id;

            if ($product_id) {
                // Redirect to the addImage method to upload images
                Helper::redirect(URLROOT . '/products/addImage/' . $product_id);
            } else {
                Helper::flashMessage('Error adding product. Please try again.', 'error');
                Helper::redirect(URLROOT . '/products');
            }
        } else {
            // Get categories for the form
            $categories = $this->categoryModel->getAllCategories();
            $data = array_merge(array_fill_keys(Helper::getProductFields(), ''), ['categories' => $categories]);
            $this->view('product/add', $data);
        }
    }

    public function addImage($product_id)
    {
        // Only allow access for admin role
        if (!Helper::isAdmin()) {
            Helper::redirect(URLROOT . "/products");
        }

        // Retrieve the next product ID
        $next_id = $this->productModel->getLastProductId();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if images are uploaded
            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'][0] == 0) {
                $fileCount = count($_FILES['productImage']['name']);
                $uploadDir = getcwd() . "/images/"; // Relative path to public/images

                // Debugging: print current working directory
                echo "Current working directory: " . getcwd();
                echo "<br>";

                // Check if the upload directory is writable
                if (!is_writable($uploadDir)) {
                    echo "Upload directory is not writable.";
                    exit;
                }

                // Loop through each uploaded file
                for ($i = 0; $i < $fileCount; $i++) {
                    // Get the original file name and temporary path
                    $originalFileName = $_FILES['productImage']['name'][$i];
                    $tmpFilePath = $_FILES['productImage']['tmp_name'][$i];

                    // Generate a unique name for the file (sanitize the filename)
                    $sanitizedFileName = basename(preg_replace("/[^a-zA-Z0-9.]/", "_", $originalFileName));
                    $targetFilePath = $uploadDir . $sanitizedFileName;

                    // Debugging: check the target file path
                    echo "Target file path: " . $targetFilePath;
                    echo "<br>";

                    // Check if the file exists before moving it
                    if (file_exists($targetFilePath)) {
                        echo "File already exists: " . $sanitizedFileName;
                        continue;
                    }

                    // Try to move the uploaded file to the target directory
                    if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
                        // Store the image name in the database
                        $this->productModel->addProductImage($next_id, $sanitizedFileName);
                    } else {
                        // Handle upload failure
                        echo "Failed to upload: " . $originalFileName;
                        echo "<br>";
                        Helper::flashMessage('Failed to upload image: ' . $originalFileName, 'error');
                    }
                }

                // Flash success message and redirect
                Helper::flashMessage('Images uploaded and saved successfully.');
                Helper::redirect(URLROOT . '/products');
            } else {
                // Handle no image uploaded
                echo "No images were uploaded or there was an error.";
                Helper::flashMessage('No images were uploaded. Please try again.', 'error');
            }
        } else {
            // Show the upload form and pass the next ID
            $data = ['next_id' => $next_id];
            $this->view('product/add_image', $data);
        }
    }



    public function show($id)
    {
        // Retrieve a single product by ID, including all images
        $product = $this->productModel->getProductWithImagesById($id);

        // Check if the product exists
        if (!$product) {
            Helper::flashMessage('Product not found.', 'error');
            Helper::redirect(URLROOT . '/products');
            return;
        }

        // Split the image names by comma to get an array of images
        $images = explode(',', $product->image_names); // Will return an array of image names

        // Prepare the data to pass to the view
        $data = [
            'productName' => $product->productName,
            'brand' => $product->brand,
            'originalPrice' => $product->originalPrice,
            'sellingPrice' => $product->sellingPrice,
            'weight' => $product->weight,
            'categoryName' => $product->categoryName,
            'images' => $images, // Pass array of image names
        ];

        // Pass the product data to the view
        $this->view('product/show', $data);
    }





    public function edit($id)
    {
        // Only allow access for admin role
        if (!Helper::isAdmin()) {
            Helper::redirect(URLROOT . "/products");
        }

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle image removal
            if (isset($_POST['imageId'])) {
                $imageId = $_POST['imageId'];
                // Call a method in your model to remove the image from the database
                $imageName = $this->productModel->getImagesByProductId($imageId); // Fetch the image name
                if ($imageName) {
                    // Delete the image file from the folder
                    $imagePath = getcwd() . "/images/" . $imageName;
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the file from the server
                    }
                }

                // Now remove the image record from the database
                $this->productModel->removeProductImage($imageId);
                Helper::flashMessage('Image removed successfully.');
                Helper::redirect(URLROOT . "/products/edit/$id");
            }

            // Otherwise, handle product updates (if no image removal)
            $_PRODUCT = filter_input_array(INPUT_POST);
            $data = ['id' => $id];
            foreach (Helper::getProductFields() as $field) {
                $data[$field] = Helper::sanitizeInput($_PRODUCT[$field] ?? '');
            }

            // Update product details in the database
            $this->productModel->edit($data);

            // Handle image uploads (if any)
            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'][0] == 0) {
                $fileCount = count($_FILES['productImage']['name']);
                $uploadDir = getcwd() . "/images/";

                // Check if the upload directory is writable
                if (!is_writable($uploadDir)) {
                    Helper::flashMessage('Upload directory is not writable.', 'error');
                    Helper::redirect(URLROOT . "/products/edit/$id");
                    exit;
                }

                // Loop through each uploaded file
                for ($i = 0; $i < $fileCount; $i++) {
                    $originalFileName = $_FILES['productImage']['name'][$i];
                    $tmpFilePath = $_FILES['productImage']['tmp_name'][$i];

                    // Generate a unique name for the file (sanitize the filename)
                    $sanitizedFileName = basename(preg_replace("/[^a-zA-Z0-9.]/", "_", $originalFileName));
                    $targetFilePath = $uploadDir . $sanitizedFileName;

                    // Check if the file already exists
                    if (file_exists($targetFilePath)) {
                        Helper::flashMessage("File already exists: $sanitizedFileName", 'error');
                        continue;
                    }

                    // Try to move the uploaded file
                    if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
                        // Save the image name in the database
                        $this->productModel->addProductImage($id, $sanitizedFileName);
                    } else {
                        // Handle upload failure
                        Helper::flashMessage("Failed to upload image: $originalFileName", 'error');
                    }
                }

                // Flash success message if images were uploaded successfully
                Helper::flashMessage('Images uploaded and saved successfully.');
            }

            // Redirect back to products page
            Helper::redirect(URLROOT . '/products');
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getAllCategories();
            $images = $this->productModel->getProductImages($id); // Fetch existing images

            if ($product) {
                $data = [
                    'id' => $id,
                    'categories' => $categories,
                    'images' => $images, // Pass images to the view
                ];

                foreach (Helper::getProductFields() as $field) {
                    $data[$field] = $product->$field ?? '';
                }

                $this->view('product/edit', $data);
            } else {
                Helper::flashMessage('Product not found.', 'error');
                Helper::redirect(URLROOT . '/products');
            }
        }
    }




    public function delete($id)
    {
        // Only allow access for admin role
        if (!Helper::isAdmin()) {
            Helper::redirect(URLROOT . "/products");
        }

        // Check if the product is in any cart
        if ($this->cartModel->isProductInCart($id)) { // Assuming isProductInCart method exists in CartModel
            Helper::flashMessage('This product cannot be deleted as it is currently in a cart.', 'error');
            Helper::redirect(URLROOT . "/products");
        }

        // Retrieve all images associated with the product
        $productImages = $this->productModel->getImagesByProductId($id); // Updated to get images by product ID

        // Check if the product has associated images
        if ($productImages) {
            // Flag to check if all images were deleted successfully
            $imagesDeletedSuccessfully = true;

            // Loop through all associated images
            foreach ($productImages as $image) {
                $imagePath = getcwd() . "/images/" . $image->image_name; // Path to the image file

                // Check if the image exists and delete it
                if (file_exists($imagePath)) {
                    if (!unlink($imagePath)) {
                        $imagesDeletedSuccessfully = false;
                    }
                } else {
                    // Log an error if the image file does not exist
                    $imagesDeletedSuccessfully = false;
                }

                // Remove the image record from the database
                $this->productModel->removeProductImage($image->id); // Assuming image has an 'id' field for deletion
            }

            // Provide feedback to the admin about image deletion status
            if ($imagesDeletedSuccessfully) {
                Helper::flashMessage('All associated images deleted successfully.', 'success');
            } else {
                Helper::flashMessage('One or more images could not be deleted.', 'error');
            }
        } else {
            // If no images were found, still proceed with deleting the product
            Helper::flashMessage('No images found for this product.', 'info');
        }

        // Delete the product from the database
        $this->productModel->delete($id);

        // Flash success message and redirect
        Helper::flashMessage('Product and associated images deleted successfully.', 'success');
        Helper::redirect(URLROOT . '/products');
    }




    // Search product part
    public function search()
    {
        $query = isset($_GET['query']) ? trim($_GET['query']) : '';

        if (!empty($query)) {
            $products = $this->productModel->searchProducts($query);
            $this->view('product/index', ['products' => $products]);
        } else {
            $this->index(); // Load all products if no query
        }
    }


    //withheld part
    public function toggleWithhold($productId)
    {
        // Check if the user is an admin
        if (!Helper::isAdmin()) {
            Helper::redirect(URLROOT . "/products");
        }

        // Get current product status
        $product = $this->productModel->getProductById($productId);

        if ($product) {
            // Toggle the isWithheld status
            $newStatus = !$product->isWithheld;
            $this->productModel->toggleWithholdStatus($productId, $newStatus);

            // Flash message based on action
            $action = $newStatus ? 'Withheld' : 'Published';
            Helper::flashMessage("Product has been successfully {$action}.", 'success');
        } else {
            Helper::flashMessage("Product not found.", 'error');
        }

        // Redirect back to the product list
        Helper::redirect(URLROOT . "/products");
    }
}
