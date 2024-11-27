<?php
class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {

        $this->userModel = $this->model('UserModel'); // Ensure UserModel exists
        // No need to call session_start() here, as it should be done in bootstrap.php
    }

    public function register()
    {
        $data = [
            'name' => '',
            'email' => '',
            'password' => '',
            'successMessage' => '',
            'role' => '',
            'errorMessage' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
            $role = trim($_POST['role']);

            // Check for existing user before inserting
            if ($this->userModel->getUserByEmail($email, $role)) {
                $data['errorMessage'] = "Email already exists. Please choose a different email.";
            } else {
                // Insert user and set success message on success
                if ($this->userModel->insertUser($name, $email, $password, $role)) {
                    $data['successMessage'] = "Registration successful! You can now log in.";
                } else {
                    $data['errorMessage'] = "Registration failed. Please try again.";
                }
            }
        }

        // Load the registration view with the data
        $this->view('user/register', $data);
    }

    public function login()
    {
        $data = [
            'email' => '',
            'password' => '',
            'loginError' => ''
        ];

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_USER = filter_input_array(INPUT_POST);
            $data['email'] = trim($_USER['email']);
            $data['password'] = trim($_USER['password']);

            // Fetch user by email (no need to pass role anymore)
            $user = $this->userModel->getUserByEmail($data['email']);

            if ($user) {
                // Check password
                if (password_verify($data['password'], $user->password)) {
                    // Store user ID, name, and role in session
                    $_SESSION['name'] = $user->name;
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['role'] = $user->role;
                    // Set login success message
                    $_SESSION['loginMessage'] = "Login successful!";

                    // Redirect based on role (admin or customer)
                    if ($user->role == 'admin') {
                        header("Location: " . URLROOT . "/products/index");
                    } else {
                        header("Location: " . URLROOT . "/products/index");
                    }
                    exit(); // Always exit after redirect
                } else {
                    $data['loginError'] = "Invalid email or password"; // Set error message
                }
            } else {
                $data['loginError'] = "User not found"; // Set error message
            }
        }

        // Load the login view with the data
        $this->view('user/login', $data);
    }




    public function logout()
    {
        // Set a session variable to show a logout success message
        $_SESSION['logoutMessage'] = "Logout successful!"; // Set message before destroying the session

        // Remove user ID from session
        unset($_SESSION["user_id"]);

        // Destroy the session
        session_destroy();

        // Redirect to index
        header("Location: " . URLROOT . "");
        // Always exit after redirect
        exit();
    }
    public function index()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Redirect to login if not logged in
            header('Location: ' . URLROOT . '/users/login');
            exit();
        }

        // Fetch the customer details using the logged-in user's ID
        $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
        $user = $this->userModel->getUserById($userId); // Fetch user details from the model

        // Pass the user data to the view
        $data = [
            'user' => $user
        ];

        $this->view('profile/profile_index', $data);
    }

    public function changePassword()
    {
        // Ensure you fetch the user data at the beginning
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);

        // Validate the input data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $oldPassword = trim($_POST['oldPassword']);
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);

            // Array to hold errors
            $errors = [];

            // Validate the passwords
            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $errors[] = 'All fields are required.';
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New password and confirm password do not match.';
            }

            // Check if the old password is correct
            if (empty($errors)) {
                if (!password_verify($oldPassword, $user->password)) {
                    $errors[] = 'Old password is incorrect.';
                }
            }

            // If there are errors, set them in session and redirect back to profile page
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $this->view('profile/profile_index', ['user' => $user]); // Pass the user data to the view
                exit;
            }

            // If everything is fine, update the password
            if (empty($errors)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                if ($userModel->updatePassword($_SESSION['user_id'], $hashedPassword)) {
                    $_SESSION['success'] = 'Password changed successfully!';
                    // Redirect back to the profile page after password change
                    $this->view('profile/profile_index', ['user' => $user]); // Pass the user data to the view
                    exit;
                } else {
                    $_SESSION['error'] = 'An error occurred while changing your password.';
                    $this->view('profile/profile_index', ['user' => $user]); // Pass the user data to the view
                    exit;
                }
            }
        }

        // Ensure you pass the user data to the view if the form was not submitted
        $this->view('profile/profile_index', ['user' => $user]);
    }
}
