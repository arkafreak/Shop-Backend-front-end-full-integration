<?php
class Product
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get all products
    public function getAllProducts()
    {
        return $this->db->select('products p LEFT JOIN categories c ON p.categoryId = c.id', 'p.*, c.categoryName');
    }

    // Add a new product
    public function add($data)
    {
        return $this->db->insert('products', $data);
    }

    // Get a product by ID
    public function getProductById($id)
    {
        return $this->db->select(
            'products p LEFT JOIN categories c ON p.categoryId = c.id',
            'p.*, c.categoryName',
            'p.id = ' . (int)$id // Using direct integer conversion
        )[0] ?? null; // Return the first result or null if not found
    }


    // Edit a product
    public function edit($data)
    {
        $where = 'id = ' . (int)$data['id']; // Ensure id is safely cast to an integer
        return $this->db->update('products', $data, $where);
    }

    // Delete a product
    public function delete($id)
    {
        return $this->db->delete('products', 'id = ' . (int)$id); // Directly using integer
    }

    // Get products by category ID
    public function getProductsByCategoryId($categoryId)
    {
        return $this->db->select(
            'products',
            '*',
            'categoryId = ' . (int)$categoryId // Using direct integer conversion
        );
    }


    public function getProductsWithImages()
    {
        $query = "SELECT p.*,
                         (SELECT image_name
                          FROM Product_images
                          WHERE product_id = p.id
                          LIMIT 1) as image_name
                  FROM products p";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    //new modified queries


    public function getAllProductsWithImages()
    {
        $query = "SELECT p.*,
                     c.categoryName,
                     (SELECT image_name
                      FROM Product_images
                      WHERE product_id = p.id
                      LIMIT 1) as image_name
              FROM products p
              LEFT JOIN categories c ON p.categoryId = c.id";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getProductWithImagesById($id)
    {
        // Ensure the ID is cast to an integer to prevent SQL injection
        $id = (int)$id;

        // Combine the product and all images query
        $query = "
        SELECT p.*, 
               c.categoryName,
               (SELECT GROUP_CONCAT(image_name) 
                FROM Product_images 
                WHERE product_id = p.id) as image_names
        FROM products p
        LEFT JOIN categories c ON p.categoryId = c.id
        WHERE p.id = $id
    ";

        // Execute the query
        $this->db->query($query);

        // Return the result (this will return an object with all product data including a comma-separated list of image names)
        return $this->db->single();
    }


    // Add an image to the product_images table
    public function addProductImage($product_id, $image_name)
    {
        $query = "INSERT INTO Product_images (product_id, image_name) VALUES (:product_id, :image_name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindValue(':image_name', $image_name, PDO::PARAM_STR);
        return $stmt->execute();
    }


    public function getLastProductId()
    {
        // Query to get the last inserted product_id
        $query = "SELECT MAX(id) AS last_product_id FROM products";

        // Prepare and execute the query
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Fetch the result and return the last product_id
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['last_product_id'] ?? 0;  // Return 0 if no products exist
    }
    // getting the product image
    public function getProductImageByProductId($id)
    {
        $query = "SELECT image_name FROM Product_images WHERE product_id = :id LIMIT 1";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single(); // Returns the image record
    }

    // weight filter seggregation function
    public function getProductsByWeight($weight)
    {
        $this->db->query("SELECT * FROM products WHERE weight = :weight");
        $this->db->bind(':weight', $weight);
        return $this->db->resultSet();
    }

    public function getProductsByWeightGreaterThan($weight)
    {
        $this->db->query("SELECT * FROM products WHERE weight > :weight");
        $this->db->bind(':weight', $weight);
        return $this->db->resultSet();
    }

    public function searchProducts($query)
    {
        $sql = "SELECT p.*, pi.image_name FROM products p
                LEFT JOIN Product_images pi ON p.id = pi.product_id
                WHERE p.productName LIKE :query OR p.brand LIKE :query";
        $this->db->query($sql);
        $this->db->bind(':query', '%' . $query . '%');
        return $this->db->resultSet();
    }

    public function getProductImages($productId)
    {
        $this->db->query("SELECT * FROM Product_images WHERE product_id = :productId");
        $this->db->bind(':productId', $productId);
        return $this->db->resultSet();
    }

    public function removeProductImage($imageId)
    {
        // Step 1: Fetch the image name from the database before deleting the record
        $this->db->query('SELECT image_name FROM Product_images WHERE id = :imageId');
        $this->db->bind(':imageId', $imageId);
        $image = $this->db->single();

        // Check if the image exists in the database
        if ($image) {
            $imageName = $image->image_name;

            // Step 2: Delete the image file from the server
            $imagePath = getcwd() . "/images/" . $imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file from the server
            }

            // Step 3: Delete the image record from the database
            $this->db->query('DELETE FROM Product_images WHERE id = :imageId');
            $this->db->bind(':imageId', $imageId);

            return $this->db->execute();
        }

        return false;
    }

    public function getImageNameById($imageId)
    {
        // Prepare the SQL query to get the image name from the database
        $query = "SELECT image_name FROM Product_images WHERE id = :imageId";

        // Execute the query
        $this->db->query($query);
        $this->db->bind(':imageId', $imageId);

        // Fetch the result
        $result = $this->db->single();

        // Return the image name if found, or null if not
        return $result ? $result->image_name : null;
    }

    public function getImagesByProductId($productId)
    {
        // SQL query to select all images associated with the given product_id
        $sql = "SELECT * FROM Product_images WHERE product_id = :product_id";

        // Prepare the query
        $stmt = $this->db->prepare($sql);

        // Bind the product_id parameter to the query
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $images = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Return the images array or false if no images found
        return $images ? $images : false;
    }
    //reduce stock and restore stock part
    public function reduceStock($productId, $quantity)
    {
        // Check the current stock for the product
        $query = "SELECT stock FROM products WHERE id = :productId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $currentStock = $stmt->fetchColumn();

        // If there's enough stock, reduce it, otherwise do nothing
        if ($currentStock >= $quantity) {
            $query = "UPDATE products SET stock = stock - :quantity WHERE id = :productId";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }


    public function getProductQuantityFromCart($productId, $orderId)
    {
        // Build the query to get the quantity from the cart table for the given productId and orderId
        $query = "SELECT SUM(quantity) AS quantity FROM cart WHERE productId = :productId AND orderId = :orderId";

        // Prepare and execute the query
        $this->db->query($query);
        $this->db->bind(':productId', $productId);
        $this->db->bind(':orderId', $orderId);

        // Get the result
        $result = $this->db->single(); // Using single() to expect one result

        // Return the quantity (or 0 if no result found)
        return $result ? $result->quantity : 0;
    }




    public function increaseStockByCartQuantity($productId, $quantity)
    {
        if ($quantity > 0) {
            // Prepare the query to increase the stock
            $query = "UPDATE products SET stock = stock + :quantity WHERE id = :productId";

            // Execute the query
            $this->db->query($query);
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':productId', $productId);

            return $this->db->execute(); // Returns true if successful, false otherwise
        }

        return false; // If no quantity found or invalid, return false
    }




    public function toggleWithholdStatus($productId, $newStatus)
    {
        try {
            // Update the isWithheld status in the database
            $sql = "UPDATE products SET isWithheld = :isWithheld WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':isWithheld', $newStatus, PDO::PARAM_INT);
            $this->db->bind(':id', $productId, PDO::PARAM_INT);

            // Execute the query
            return $this->db->execute();
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error updating isWithheld for Product ID {$productId}: " . $e->getMessage());
            return false;
        }
    }

    public function updateProductStock($productId, $newStock)
    {
        // Prepare the SQL query to update the stock
        $sql = "UPDATE products SET stock = :newStock WHERE id = :productId";  // Changed 'quantity' to 'stock'

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':newStock', $newStock, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);

        // Execute the query and check for success
        if ($stmt->execute()) {
            return true; // Success
        } else {
            return false; // Failure
        }
    }
}
