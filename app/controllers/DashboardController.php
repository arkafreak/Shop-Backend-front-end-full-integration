<?php

class DashboardController extends Controller
{

    private $orderModel;

    public function __construct()
    {
        Helper::startSession();
        if (!Helper::isLoggedIn() || $_SESSION['role'] !== 'admin') {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        // Load the OrderModel
        $this->orderModel = $this->model('OrderModel');
    }

    public function index()
    {
        $purchasedProducts = $this->orderModel->getAllPurchasedProducts();
        
        // Use the groupProductsByDate method here
        $groupedProducts = $this->groupProductsByDate($purchasedProducts);
    
        $data = [
            'totalSales' => $this->orderModel->getTotalSales(),
            'totalRevenue' => $this->orderModel->getTotalRevenue(),
            'pendingOrders' => $this->orderModel->getOrderCountByStatus('pending'),
            'completedOrders' => $this->orderModel->getOrderCountByStatus('completed'),
            'groupedProducts' => $groupedProducts, // Use grouped products here
            'salesData' => $this->orderModel->getSalesDataByPaymentMethod(),
            'revenueData' => $this->orderModel->getRevenueOverTime()
        ];
    
        $this->view('dashboard/index', $data);
    }
    

    // Function to group products by purchase date and time
    private function groupProductsByDate($products)
    {
        $grouped = [];
        foreach ($products as $product) {
            // Format the date and time
            $dateTime = date('Y-m-d H:i', strtotime($product->purchase_date));  // Format as Date and Time (e.g., 2024-11-07 12:30)

            // Group by the formatted date and time
            if (!isset($grouped[$dateTime])) {
                $grouped[$dateTime] = [];
            }
            $grouped[$dateTime][] = $product;
        }
        return $grouped;
    }
}
