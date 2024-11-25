<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Card Container -->
                <div class="card shadow-lg">
                    <!-- Card Header -->
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="h4 mb-0">Welcome to Shopsyyy</h1>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- Logout Message -->
                        <?php if (!empty($_SESSION['logoutMessage'])): ?>
                            <div id="logoutMessage" class="alert alert-success text-center">
                                <?php echo $_SESSION['logoutMessage']; ?>
                                <?php unset($_SESSION['logoutMessage']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- User Actions -->
                        <div class="d-grid gap-3">
                            <a href="<?php echo URLROOT; ?>/UserController/register" class="btn btn-primary btn-lg">
                                Register
                            </a>
                            <a href="<?php echo URLROOT; ?>/UserController/login" class="btn btn-outline-primary btn-lg">
                                Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hide the logout message after 3 seconds
        setTimeout(() => {
            const message = document.getElementById('logoutMessage');
            if (message) message.style.display = 'none';
        }, 3000);
    </script>

</body>

</html>