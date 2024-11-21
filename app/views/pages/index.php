<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT;?>/public/css/pages_style.css">
    <title>Home Page</title>
</head>

<body>
    <div class="container">
        <!-- Right side with content -->
        <div class="content-container">
            <h1>Welcome to the Shopsyyy Sign Up Page</h1>

            <!-- Logout Message -->
            <?php if (!empty($_SESSION['logoutMessage'])): ?>
                <p class="message" id="logoutMessage"><?php echo $_SESSION['logoutMessage']; ?></p>
                <?php unset($_SESSION['logoutMessage']); ?>
            <?php endif; ?>

            <!-- Navigation Buttons -->
            <div class="options">
                <h2>User Actions:</h2>
                <a href="<?php echo URLROOT; ?>/UserController/register"><button>Register</button></a>
                <a href="<?php echo URLROOT; ?>/UserController/login"><button>Login</button></a>
            </div>
        </div>
    </div>

    <script>
        // Hide the logout message after 3 seconds
        setTimeout(() => {
            const message = document.getElementById('logoutMessage');
            if (message) message.style.display = 'none';
        }, 3000);
    </script>

</body>

</html>