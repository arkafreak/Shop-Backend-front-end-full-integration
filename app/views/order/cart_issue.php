<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Issue</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg border-0" style="max-width: 600px;">
            <div class="card-body text-center">
                <h1 class="card-title text-danger mb-3">Cart Issues</h1>
                <?php if (!empty($data['errorMessages'])): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($data['errorMessages'] as $message): ?>
                            <li class="list-group-item text-start"><?= $message ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <a href="<?= URLROOT; ?>/products" class="btn btn-primary mt-3">Go Back to Shop</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
