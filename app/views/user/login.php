<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/login_style.css">
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
                    <a href="<?php echo URLROOT; ?>/UserController/register" class="btn btn-outline-light btn-sm">Register</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Login Section -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">

            <h2 class="text-center mb-4">Login</h2>

            <?php if (!empty($data['loginError'])): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $data['loginError']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/UserController/login" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required value="<?php echo htmlspecialchars($data['email']); ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?php echo URLROOT; ?>/UserController/register" class="text-decoration-none">Don't have an account? Register here</a>
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