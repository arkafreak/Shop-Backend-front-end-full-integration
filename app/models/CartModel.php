<?php

class CartModel
{
    private $db;

    public function __construct()
    {
        // Initialize the database connection
        $this->db = new Database();
    }

    // Update cart quantity or remove item if quantity is 0
    public function updateCart($userId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            // Remove item if quantity is zero or less
            return $this->removeFromCart($userId, $productId);
        } else {
            // Check if the item already exists in the cart
            $query = "SELECT quantity FROM cart WHERE userId = :userId AND productId = :productId";
            $this->db->query($query);
            $this->db->bind(':userId', $userId);
            $this->db->bind(':productId', $productId);
            $existingItem = $this->db->single();

            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem->quantity + $quantity;
                $query = "UPDATE cart SET quantity = :quantity WHERE userId = :userId AND productId = :productId";
                $this->db->query($query);
                $this->db->bind(':quantity', $newQuantity);
            } else {
                // Insert new item
                $query = "INSERT INTO cart (userId, productId, quantity) VALUES (:userId, :productId, :quantity)";
                $this->db->query($query);
                $this->db->bind(':quantity', $quantity);
            }

            // Common bindings and execute
            $this->db->bind(':userId', $userId);
            $this->db->bind(':productId', $productId);
            return $this->db->execute();
        }
    }



    // Fetch cart items for the specified user
    public function getCartItems($userId)
    {
        $query = "SELECT p.id, p.productName, p.brand, p.sellingPrice, c.quantity, 
                     (SELECT image_name FROM Product_images WHERE product_id = p.id LIMIT 1) AS image_name
              FROM cart c
              JOIN products p ON c.productId = p.id
              WHERE c.userId = :userId";

        $this->db->query($query);
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet(); // Fetch all results
    }


    // Add product to the cart or return error if already in cart
    public function addToCart($userId, $productId)
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);

        // Check if the product is already in the cart
        $checkQuery = "SELECT quantity FROM cart WHERE userId = :userId AND productId = :productId";
        $this->db->query($checkQuery);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':productId', $productId);

        $result = $this->db->single(); // Fetch single result

        if ($result) {
            // Product is already in cart, return an error message
            return ['status' => 'error', 'message' => 'Product is already in the cart.'];
        } else {
            // Product not in cart, insert it with quantity 1
            $query = "INSERT INTO cart (userId, productId, quantity)
                      VALUES (:userId, :productId, 1)";
            $this->db->query($query);
            $this->db->bind(':userId', $userId);
            $this->db->bind(':productId', $productId);

            if ($this->db->execute()) {
                return ['status' => 'success', 'message' => 'Product added to cart successfully!'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to add product to cart.'];
            }
        }
    }

    // Remove product from the cart or decrease quantity
    public function removeFromCart($userId, $productId)
    {
        // Reduce quantity by 1
        $query = "UPDATE cart SET quantity = quantity - 1 WHERE userId = :userId AND productId = :productId";
        $this->db->query($query);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':productId', $productId);
        $this->db->execute();

        // Remove item if quantity is now zero or less
        $query = "DELETE FROM cart WHERE userId = :userId AND productId = :productId AND quantity <= 0";
        $this->db->query($query);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':productId', $productId);
        return $this->db->execute();
    }

    public function removeItem($userId, $productId)
    {
        $query = "DELETE FROM cart WHERE userId = :userId AND productId = :productId";
        $this->db->query($query);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':productId', $productId);
        return $this->db->execute();
    }


    public function getCartProductsByUserId($userId)
    {
        // Example query to get cart products
        $this->db->query('SELECT productId FROM cart WHERE userId = :userId');
        $this->db->bind(':userId', $userId);
        $result = $this->db->resultSet();

        // Extracting product IDs from the result
        return array_map(function ($item) {
            return $item->productId;
        }, $result);
    }

    // public function isProductInCart($userId, $productId)
    // {
    //     // Query to check if the product is already in the user's cart
    //     $this->db->query("SELECT COUNT(*) FROM cart WHERE userId = :userId AND productId = :productId");
    //     $this->db->bind(':userId', $userId);
    //     $this->db->bind(':productId', $productId);

    //     return $this->db->fetchColumn() > 0; // Return true if product is in cart
    // }


    //new function for getting cart items along with images

    public function getCartItemsWithImages($userId)
    {
        $query = "SELECT p.id,
                     p.productName,
                     p.brand,
                     p.sellingPrice,
                     c.quantity,
                     (SELECT image_name
                      FROM Product_images
                      WHERE product_id = p.id
                      LIMIT 1) as image_name
              FROM cart c
              JOIN products p ON c.productId = p.id
              WHERE c.userId = :userId";

        $this->db->query($query);
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet(); // Fetch all results
    }

    public function count($userId)
    {
        $query = "SELECT COUNT(*) as count FROM cart WHERE userId = :userId";
        $this->db->query($query);
        $this->db->bind(':userId', $userId);

        $result = $this->db->single();  // Fetch a single result

        // Debugging: Output the result
        // var_dump($result);  // This will help you inspect the returned object

        return $result;
    }


    public function getCartItemsWithDetails($userId)
    {
        $query = "SELECT
                  p.id,
                  p.productName,
                  p.brand,
                  p.sellingPrice,
                  p.isWithheld,
                  c.quantity,
                  p.stock,
                  (SELECT image_name
                   FROM Product_images
                   WHERE product_id = p.id
                   LIMIT 1) as image_name
              FROM cart c
              JOIN products p ON c.productId = p.id
              WHERE c.userId = :userId";

        $this->db->query($query);
        $this->db->bind(':userId', $userId);

        return $this->db->resultSet(); // Fetch all results
    }

    public function isProductInCart($productId)
    {
        $query = "SELECT COUNT(*) as count FROM cart WHERE productId = :productId";
        $this->db->query($query);
        $this->db->bind(':productId', $productId);
        $result = $this->db->single();

        // Access the property as an object
        return $result->count > 0;
    }

    public function isProductInCartt($productId, $userId)
    {
        $query = "SELECT COUNT(*) as count
              FROM cart
              WHERE productId = :productId AND userId = :userId";
        $this->db->query($query);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':userId', $userId);
        $result = $this->db->single();
        return $result->count > 0; // Returns true if count > 0, otherwise false
    }

    public function updateCartWithOrderId($orderIdNumber, $userId)
    {
        $query = "UPDATE cart SET orderId = :orderId WHERE userId = :userId";
        $this->db->query($query);
        $this->db->bind(':orderId', $orderIdNumber);
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    public function getCartItemsFinal($userId)
    {
        $this->db->query("
        SELECT 
            c.cartId as cartId,
            p.id as productId,
            p.productName,
            p.sellingPrice,
            p.stock,
            p.isWithheld,
            c.quantity
        FROM cart c
        INNER JOIN products p ON c.productId = p.id
        WHERE c.userId = :userId
    ");
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }
}
