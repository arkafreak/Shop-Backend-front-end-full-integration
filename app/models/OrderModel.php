<?php
class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Create a new order
    public function createOrder($userId, $totalAmount, $orderStatus, $paymentMethod)
    {
        error_log("data is here: " . $userId . " " . $totalAmount . " " . $paymentMethod);

        $orderData = [
            'userId' => $userId,
            'totalAmount' => $totalAmount,
            'paymentMethod' => $paymentMethod,
            'orderStatus' => $orderStatus
            // 'orderStatus' is omitted; the database will use its default value
        ];

        return $this->db->insert('orders', $orderData);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $stmt = $this->db->prepare("UPDATE orders SET orderStatus = :status WHERE id = :orderId");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderId', $orderId);

        return $stmt->execute(); // Returns true on success
    }

    public function updateOrderPayment($orderId, $paymentMethod)
    {
        $stmt = $this->db->prepare("UPDATE orders SET paymentMethod = :paymentMethod where id =: orderId");
        $stmt->bindParam(':paymentMethod', $paymentMethod);
        $stmt->bindParam(':orderId', $orderId);
        return $stmt->execute();
    }

    public function getTotalAmountByOrderId()
    {
        $table = 'orders';
        $columns = 'totalAmount';
        $where = '1 ORDER BY id DESC LIMIT 1'; // Placeholder condition

        $result = $this->db->select($table, $columns, $where);

        // Return the total amount if found, otherwise return null
        return $result ? $result[0]->totalAmount : null;
    }

    public function updateOrder($orderId, $status)
    {
        $data = ['orderStatus' => $status];
        return $this->db->update('orders', $data, "id = $orderId");
    }


    // Clear cart items after placing an order
    public function clearCart($userId)
    {
        return $this->db->delete('cart', 'userId = ' . (int)$userId);
    }
    public function getPaymentMethodByOrderId($orderId)
    {
        $result = $this->db->select('orders', 'paymentMethod', "id = $orderId");
        return $result ? $result[0]->paymentMethod : null; // Assuming result is an array of objects
    }

    public function getLatestOrderIdByUserId($userId)
    {
        // Query to select the latest order ID for a specific user
        $query = "SELECT MAX(id) AS latestOrderId FROM orders WHERE userId = :userId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the latest order ID if found, otherwise return null
        return $result ? $result['latestOrderId'] : null;
    }


    //Part for order_items table!
    public function addOrderItem($orderId, $productId, $quantity)
    {
        $query = "INSERT INTO order_items (orderId, productId, quantity) VALUES (:orderId, :productId, :quantity)";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':quantity', $quantity);
        $this->db->execute();
    }

    public function getAllPurchasedProducts()
    {
        // Query to fetch products ordered by createdAt timestamp (grouped by date and time)
        $query = "SELECT p.id, p.productName, p.brand, oi.quantity, o.createdAt AS purchase_date
                  FROM order_items oi
                  JOIN products p ON oi.productId = p.id
                  JOIN orders o ON oi.orderId = o.id
                  WHERE o.orderStatus = 'completed'
                  ORDER BY o.createdAt DESC";

        $this->db->query($query);
        return $this->db->resultSet();
    }
    public function getRevenueOverTime()
    {
        // Query to calculate daily revenue
        $query = "SELECT DATE(createdAt) AS date, SUM(totalAmount) AS revenue
                      FROM orders
                      WHERE orderStatus = 'completed'
                      GROUP BY DATE(createdAt)
                      ORDER BY DATE(createdAt) DESC";

        $this->db->query($query);
        return $this->db->resultSet();  // Return the result
    }

    // 1. Get Total Sales Count
    public function getTotalSales()
    {
        $query = "SELECT COUNT(*) AS total_sales FROM orders WHERE orderStatus = 'completed'";
        $this->db->query($query);
        return $this->db->single()->total_sales ?? 0;
    }

    // 2. Get Total Revenue
    public function getTotalRevenue()
    {
        $query = "SELECT SUM(totalAmount) AS total_revenue FROM orders WHERE orderStatus = 'completed'";
        $this->db->query($query);
        return $this->db->single()->total_revenue ?? 0;
    }

    // 3. Get Order Count by Status
    public function getOrderCountByStatus($status)
    {
        $query = "SELECT COUNT(*) AS order_count FROM orders WHERE orderStatus = :status";
        $this->db->query($query);
        $this->db->bind(':status', $status);
        return $this->db->single()->order_count ?? 0;
    }

    // 4. Group Products by Date and Time
    public function getGroupedProducts()
    {
        $query = "
            SELECT 
                DATE_FORMAT(createdAt, '%Y-%m-%d %H:00:00') AS groupedTime,
                productId,
                products.productName,
                products.brand,
                COUNT(*) AS quantity
            FROM order_items
            JOIN products ON order_items.productId = products.id
            GROUP BY groupedTime, productId
            ORDER BY groupedTime DESC
        ";
        $this->db->query($query);
        $results = $this->db->resultSet();

        // Group by time
        $groupedProducts = [];
        foreach ($results as $row) {
            $groupedProducts[$row->groupedTime][] = $row;
        }

        return $groupedProducts;
    }

    // 5. Get Sales Data by Payment Method
    public function getSalesDataByPaymentMethod()
    {
        $query = "
            SELECT paymentMethod, SUM(totalAmount) AS total_sales
            FROM orders
            WHERE orderStatus = 'completed'
            GROUP BY paymentMethod
        ";
        $this->db->query($query);
        return $this->db->resultSet();
    }
    public function getProductImageByProductId($id)
    {
        $query = "SELECT image_name FROM Product_images WHERE product_id = :id LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single(); // Returns the image record
    }


    // Fetch current order status by order ID
    public function getOrderStatusById($orderId)
    {
        $query = "SELECT orderStatus FROM orders WHERE id = :orderId";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);
        return $this->db->single()->orderStatus;  // Returns the status as a string ('pending', 'completed', etc.)
    }

    // Fetch items from an order by order ID
    public function getOrderItems($orderId)
    {
        $query = "SELECT * FROM order_items WHERE orderId = :orderId";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);
        return $this->db->resultSet(); // Returns all order items for the order
    }

    // Update order status
    public function updateStatus($orderId, $status)
    {
        $query = "UPDATE orders SET orderStatus = :status WHERE id = :orderId";
        $this->db->query($query);
        $this->db->bind(':status', $status);
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();
    }

    public function getPendingOrdersWithinTimeframe($userId, $seconds)
    {
        $timeLimit = time() - $seconds;
        $query = "SELECT * FROM orders WHERE userId != :userId AND orderStatus = 'pending' AND createdAt > :timeLimit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':timeLimit', date('Y-m-d H:i:s', $timeLimit), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    //update paymentMethod
    public function updateOrderPaymentMethod($orderId, $paymentMethod)
    {
        $sql = "UPDATE orders SET paymentMethod = :paymentMethod WHERE id = :orderId";
        $this->db->query($sql);
        $this->db->bind(':paymentMethod', $paymentMethod);
        $this->db->bind(':orderId', $orderId);
        return $this->db->execute();
    }

    public function getPendingOrdersOlderThan($minutes)
    {
        // SQL query to fetch pending orders older than specified minutes
        $query = "SELECT * FROM orders WHERE orderStatus = 'pending' AND createdAt <= NOW() - INTERVAL :minutes MINUTE";

        // Prepare and execute the query
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':minutes', $minutes, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch and return the orders
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUserByOrderId($orderId)
    {
        $query = "SELECT u.id, u.name, u.email FROM users u JOIN orders o ON u.id = o.userId WHERE o.id = :orderId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ); // Assuming you're fetching a single user
    }
    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_OBJ);
        return $order;
    }

    public function getCartQuantity($orderId, $productId)
    {
        // Query to get the quantity of a product in a user's cart for a specific order
        $query = "SELECT quantity FROM cart WHERE userId = (SELECT userId FROM orders WHERE id = :orderId) AND productId = :productId";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':productId', $productId);

        // Return the result as a single value (quantity)
        return $this->db->single()->quantity;
    }
    public function getOrderItemsFromCart($orderId)
    {
        $query = "SELECT productId, quantity FROM cart WHERE orderId = :orderId";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);

        return $this->db->resultSet(); // Ensure this returns an array of objects or associative arrays
    }

    public function getOrderItemsFromCartForMail($orderId)
    {
        $query = "
        SELECT
            c.productId,
            c.quantity,
            p.productName,
            p.sellingPrice,
            o.totalAmount
        FROM
            cart c
        INNER JOIN
            products p ON c.productId = p.id
        INNER JOIN
            orders o ON c.orderId = o.id
        WHERE
            c.orderId = :orderId
    ";

        $this->db->query($query);
        $this->db->bind(':orderId', $orderId);

        return $this->db->resultSet(); // Returns an array of objects or associative arrays
    }

    public function restartOrderTimer($orderId)
    {
        $sql = "UPDATE orders SET createdAt = NOW() WHERE id = :orderId";
        $this->db->query($sql);
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();
    }

    public function markTimerRestarted($orderId)
    {
        $sql = "UPDATE orders SET timerRestarted = 1 WHERE id = :orderId";
        $this->db->query($sql);
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();
    }
}
