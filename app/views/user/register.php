<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url(<?php echo URLROOT; ?>/public/images/background.jpg);
            background-size: cover;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Header Section (Smaller) -->
    <header class="bg-dark text-white py-1">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo URLROOT; ?>" class="navbar-brand text-white">
                    <img src="<?php echo URLROOT; ?>/public/images/fox_logo.png" alt="Logo" style="max-width: 100px;">
                    <span class="fs-5">Shopsyyy.com</span>
                </a>
                <div>
                    <a href="<?php echo URLROOT; ?>/UserController/login" class="btn btn-outline-light btn-sm">Login</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Register Section -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">

            <h2 class="text-center mb-4">Register</h2>

            <?php if (!empty($data['successMessage'])): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $data['successMessage']; ?>
                </div>
            <?php elseif (!empty($data['errorMessage'])): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $data['errorMessage']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/UserController/register" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required value="<?php echo htmlspecialchars($data['name']); ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required value="<?php echo htmlspecialchars($data['email']); ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                </div>

                <!-- No Role Selection, role is set as 'customer' by default -->
                <input type="hidden" name="role" value="customer">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?php echo URLROOT; ?>/UserController/login" class="text-decoration-none">Already have an account? Login here</a>
            </div>

        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white py-1">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Shopsyyy.com. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>