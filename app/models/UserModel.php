<?php
class UserModel
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database(); // Assuming Database class is correctly set up
    }

    public function insertUser($name, $email, $password, $role)
    {
        try {
            $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $this->db->query($sql);
            $this->db->bind(':name', $name);
            $this->db->bind(':email', $email);
            $this->db->bind(':password', $password);
            $this->db->bind(':role', $role);

            // Execute the statement and return success status
            return $this->db->execute();
        } catch (Exception $e) {
            // Show error message (for debugging purposes, you can log this instead in production)
            //echo "Failed to insert user: " . $e->getMessage();
            return false; // Return false on error
        }
    }

    public function getUserByEmail($email, $role)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email AND role = :role");
        $this->db->bind(':email', $email);
        $this->db->bind(':role', $role);
        return $this->db->single(); // Fetch a single record
        // This should return an object or an associative array
    }

    public function getEmailById($userId)
    {
        $result = $this->db->select('users', 'email', "id = $userId");
        return $result ? $result[0]->email : null;
    }
    public function getUserNameById($userId)
    {
        // Use the select method from the Database class to get the user's name
        $result = $this->db->select('users', 'name', "id = $userId");

        // Return the name if found, otherwise return null
        return $result ? $result[0]->name : null; // Adjust according to your result format
    }
    // function to delete user by id from the admin panel;
    public function deleteUserById($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    //function to get all users to show!
    public function getUsers()
    {
        $this->db->query("SELECT * FROM users WHERE role = 'customer' ORDER BY createdAt DESC");
        return $this->db->resultSet();
    }
}
