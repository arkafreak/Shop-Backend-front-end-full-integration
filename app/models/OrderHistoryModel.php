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
}
