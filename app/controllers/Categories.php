<?php
class Categories extends Controller
{
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        // Start the session
        Helper::startSession();

        // Check if user is logged in
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login"); // Redirect to login if not authenticated
        }

        // Initialize the Category and Product models
        $this->categoryModel = $this->model('Category');
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        // Retrieve all categories
        $categories = $this->categoryModel->getAllCategories();
        $data = ['categories' => $categories];
        $this->view('category/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'categoryName' => Helper::sanitizeInput($_POST['categoryName'])
            ];
            $this->categoryModel->add($data);
            Helper::flashMessage('Category added successfully.'); // Add flash message
            Helper::redirect(URLROOT . '/categories');
        } else {
            $data = ['categoryName' => ''];
            $this->view('category/add', $data);
        }
    }

    public function show($id)
    {
        // Retrieve a single category by ID
        $category = $this->categoryModel->getCategoryById($id);
        $products = $this->productModel->getProductsByCategoryId($id);
        $data = [
            'category' => $category,
            'products' => $products
        ];
        $this->view('category/show', $data); // View for displaying category details
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and filter input
            $data = [
                'id' => $id,
                'categoryName' => Helper::sanitizeInput($_POST['categoryName'])
            ];
            $this->categoryModel->edit($data); // Call the correct model method
            Helper::flashMessage('Category updated successfully.'); // Add flash message
            Helper::redirect(URLROOT . '/categories');
        } else {
            // Retrieve category for editing
            $category = $this->categoryModel->getCategoryById($id);
            $data = [
                'id' => $id,
                'categoryName' => $category->categoryName
            ];
            $this->view('category/edit', $data); // Corrected to 'categories/edit'
        }
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        Helper::flashMessage('Category deleted successfully.'); // Add flash message
        Helper::redirect(URLROOT . '/categories');
    }
}