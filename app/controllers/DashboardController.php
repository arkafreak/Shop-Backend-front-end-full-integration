<?php

class DashboardController extends Controller
{
    private $orderModel;
    private $userModel;

    public function __construct()
    {
        Helper::startSession();
        if (!Helper::isLoggedIn() || $_SESSION['role'] !== 'admin') {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        // Load models
        $this->orderModel = $this->model('OrderModel');
        $this->userModel = $this->model('UserModel');
    }

    public function index()
    {
        $purchasedProducts = $this->orderModel->getAllPurchasedProducts();

        // Group products by date
        $groupedProducts = $this->groupProductsByDate($purchasedProducts);

        // Fetch all users
        $users = $this->userModel->getUsers();

        $data = [
            'totalSales' => $this->orderModel->getTotalSales(),
            'totalRevenue' => $this->orderModel->getTotalRevenue(),
            'pendingOrders' => $this->orderModel->getOrderCountByStatus('pending'),
            'completedOrders' => $this->orderModel->getOrderCountByStatus('completed'),
            'groupedProducts' => $groupedProducts, // Grouped products
            'salesData' => $this->orderModel->getSalesDataByPaymentMethod(),
            'revenueData' => $this->orderModel->getRevenueOverTime(),
            'users' => $users // Pass users to the view
        ];

        $this->view('dashboard/index', $data);
    }

    // Add a method to delete a user
    public function deleteUser($id)
    {
        if ($this->userModel->deleteUserById($id)) {
            Helper::flash('user_message', 'User deleted successfully', 'alert-success');
        } else {
            Helper::flash('user_message', 'Unable to delete user. Please try again.', 'alert-danger');
        }

        // Redirect back to the dashboard
        Helper::redirect(URLROOT . '/DashboardController/index');
    }


    // Function to group products by purchase date and time
    private function groupProductsByDate($products)
    {
        $grouped = [];
        foreach ($products as $product) {
            $dateTime = date('Y-m-d H:i', strtotime($product->purchase_date)); // Format as Date and Time
            if (!isset($grouped[$dateTime])) {
                $grouped[$dateTime] = [];
            }
            $grouped[$dateTime][] = $product;
        }
        return $grouped;
    }
}
