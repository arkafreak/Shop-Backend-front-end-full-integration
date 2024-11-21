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
        // Redirect to options page if user is already logged in
        // if (isset($_SESSION['user_id'])) {
        //     header("Location: " . URLROOT . "/choice/options"); // Redirect to the desired page
        //     exit(); // Always exit after redirect
        // }

        // Initialize data with default values
        $data = [
            'email' => '',
            'password' => '',
            'role' => '',
            'loginError' => ''
        ];

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_USER = filter_input_array(INPUT_POST);
            $data['email'] = trim($_USER['email']);
            $data['password'] = trim($_USER['password']);
            $data['role'] = trim($_USER['role']);

            // Fetch user by email
            $user = $this->userModel->getUserByEmail($data['email'], $data['role']);

            if ($user) {
                // Check password
                if (password_verify($data['password'], $user->password)) {
                    // Store user ID in session
                    $_SESSION['name'] = $user->name;
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['role'] = $user->role;
                    // Set login success message
                    $_SESSION['loginMessage'] = "Login successful!";

                    // Redirect to options page
                    header("Location: " . URLROOT . "/choice/options");
                    exit(); // Always exit after redirect
                } else {
                    $data['loginError'] = "Invalid email or password"; // Set error message
                }
            } else {
                $data['loginError'] = "User not found under this role"; // Set error message
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
}
