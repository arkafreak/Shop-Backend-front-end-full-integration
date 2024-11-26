<?php
class Pages extends Controller
{

    private $productModel;
    public function __construct()
    {
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        // Retrieve all products
        $products = $this->productModel->getAllProductsWithImages();
        // var_dump($products);


        // Get the cart item count for the user if logged in
        $cartItemCount = 0;
        // Prepare data to pass to the view
        $data = [
            'products' => $products
        ];
        $this->view('product/index', $data);
    }


    public function about()
    {
        $this->view('pages/about');
    }

    public function options()
    {
        $this->view('product/index');
    }
}
