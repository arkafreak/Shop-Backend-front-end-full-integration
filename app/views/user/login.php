<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/login_style.css">
    <title>Login</title>
</head>

<body>
    <div class="form-container">
        <h1>LOGIN</h1>

        <?php if (!empty($data['loginError'])): ?>
            <p style="color: red;"><?php echo $data['loginError']; ?></p>
        <?php endif; ?>
        <div class="form-container">
            <form action="<?php echo URLROOT; ?>/UserController/login" method="POST">
                <input type="email" name="email" placeholder="Enter Email" required value="<?php echo htmlspecialchars($data['email']); ?>"><br><br>
                <input type="password" name="password" placeholder="Enter Password" required><br><br>
                <label for="role">Select Role:</label>
                <select name="role" id="role" required>
                    <option value="">--Select Role--</option>
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                </select><br><br>
                <br>
                <button type="submit">Login</button>
                <br>
                <a href="<?php echo URLROOT; ?>/UserController/register">Don't have an account? Register here</a>
            </form>
        </div>
    </div>
</body>

</html>