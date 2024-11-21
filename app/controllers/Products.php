<?php
class Products extends Controller
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        // Start session and check if user is logged in
        Helper::startSession();
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login"); // Redirect to login if not authenticated
        }

        // Initialize the Product and Category models
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }

    public function index()
    {
        // Retrieve all products
        // $products = $this->productModel->getAllProducts();
        // $data = [
        //     'products' => $products
        // ];
        $data['products'] = $this->productModel->getAllProductsWithImages();
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


    // public function addImage($product_id)
    // {
    //     // Only allow access for admin role
    //     if (!Helper::isAdmin()) {
    //         Helper::redirect(URLROOT . "/products");
    //     }

    //     // Retrieve the product ID
    //     $next_id = $this->productModel->getLastProductId();
    //     echo "$next_id";
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Check if the image is uploaded
    //         if (isset($_FILES['productImage']) && $_FILES['productImage']['error'][0] == 0) {
    //             $fileCount = count($_FILES['productImage']['name']);

    //             // Loop through each uploaded file
    //             for ($i = 0; $i < $fileCount; $i++) {
    //                 // Get the file name and extension
    //                 $fileName = $_FILES['productImage']['name'][$i];

    //                 // Store the image name in the database (product_images table)
    //                 $this->productModel->addProductImage($next_id, $fileName);
    //             }

    //             // Flash success message and redirect
    //             Helper::flashMessage('Images uploaded successfully.');
    //             Helper::redirect(URLROOT . '/products');
    //         } else {
    //             Helper::flashMessage('No image was uploaded. Please try again.', 'error');
    //         }
    //     } else {
    //         // Show the upload form and pass the next ID
    //         $data = ['next_id' => $next_id];
    //         $this->view('product/add_image', $data);
    //     }
    // }
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_PRODUCT = filter_input_array(INPUT_POST);
            $data = ['id' => $id];
            foreach (Helper::getProductFields() as $field) {
                $data[$field] = Helper::sanitizeInput($_PRODUCT[$field] ?? '');
            }

            $this->productModel->edit($data);
            Helper::flashMessage('Product updated successfully.');
            Helper::redirect(URLROOT . '/products');
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getAllCategories();

            if ($product) {
                $data = ['id' => $id, 'categories' => $categories];
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
}
