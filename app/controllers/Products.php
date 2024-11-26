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
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Retrieve all products
        $products = $this->productModel->getAllProductsWithImages();

        // Get the cart item count for the user if logged in
        $cartItemCount = 0;
        if ($userId) {
            $cartItemCount = $this->cartModel->count($userId);
            $cartItemCount = isset($cartItemCount[0]->count) ? $cartItemCount[0]->count : 0;
        }

        // Prepare data to pass to the view
        $data = [
            'products' => $products,
            'cartItemCount' => $cartItemCount
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
                $imageName = $this->productModel->getImageNameById($imageId); // Fetch the image name
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

        // Retrieve the image name from the Product_images table
        $productImage = $this->productModel->getProductImageByProductId($id);

        // Check if the product has associated images
        if ($productImage && isset($productImage->image_name)) { // Use object notation
            $imagePath = getcwd() . "/images/" . $productImage->image_name; // Path to the image file

            // Check if the image exists and delete it
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            } else {
                Helper::flashMessage('Image file not found, but product deleted successfully.', 'error');
            }
        }

        // Delete the product from the database (this will also delete associated image records due to cascade delete)
        $this->productModel->delete($id);

        // Flash success message and redirect
        Helper::flashMessage('Product deleted successfully.');
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
}
