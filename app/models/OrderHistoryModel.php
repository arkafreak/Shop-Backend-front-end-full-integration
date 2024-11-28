<?php
class OrderHistoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPurchasedProductsByUser($userId)
    {
        $query = "SELECT p.id, p.productName, p.brand, oi.quantity, o.createdAt AS purchase_date
                  FROM order_items oi
                  JOIN products p ON oi.productId = p.id
                  JOIN orders o ON oi.orderId = o.id
                  WHERE o.orderStatus = 'completed' AND o.userId = :userId
                  ORDER BY o.createdAt DESC";

        $this->db->query($query);
        $this->db->bind(':userId', $userId);

        return $this->db->resultSet();
    }

    public function getOrderHistoryByCustomerId($userId)
    {
        // SQL query to retrieve all orders and associated order items for the customer
        $sql = "
            SELECT o.id AS order_id, o.totalAmount, o.orderStatus, o.createdAt, oi.productId, oi.quantity, p.productName, p.sellingPrice
            FROM orders o
            JOIN order_items oi ON o.id = oi.orderId
            JOIN products p ON oi.productId = p.id
            WHERE o.userId = :userId
            ORDER BY o.createdAt DESC"; // Orders by creation date descending

        // Prepare the query
        $stmt = $this->db->prepare($sql);

        // Bind the userId parameter to the query
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $orderHistory = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Return the order history array or false if no orders found
        return $orderHistory ? $orderHistory : false;
    }
}
