<?php
class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Get all categories
    public function getAllCategories()
    {
        return $this->db->select('categories'); // Select all categories
    }

    // Add a new category
    public function add($data)
    {
        return $this->db->insert('categories', ['categoryName' => $data['categoryName']]); // Insert new category
    }

    // Get a category by ID
    public function getCategoryById($id)
    {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single(); // This should return a single object, not an array
    }

    // Edit a category
    public function edit($data)
    {
        return $this->db->update('categories', ['categoryName' => $data['categoryName']], 'id = ' . (int)$data['id']); // Update category
    }

    // Delete a category
    public function delete($id)
    {
        return $this->db->delete('categories', 'id = ' . (int)$id); // Delete category by ID
    }
}
