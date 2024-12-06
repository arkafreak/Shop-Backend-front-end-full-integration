<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Initiation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg border-0" style="max-width: 500px;">
            <div class="card-body text-center">
                <h1 class="card-title text-danger mb-3">Refund Initiation</h1>
                <p class="card-text text-muted">
                    Unfortunately, this order cannot be placed as it has already been processed.
                    A refund will be initiated shortly if applicable.
                </p>
                <p class="mt-4 text-secondary">
                    Redirecting in: <span id="timer">30</span> seconds...
                </p>
                <a href="<?= URLROOT; ?>/products" class="btn btn-primary mt-3">Go to Home</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Countdown Timer Script -->
    <script>
        // Countdown timer
        let timerElement = document.getElementById('timer');
        let countdown = 30;

        const interval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;

            // When timer reaches 0, redirect to HOME page
            if (countdown === 0) {
                clearInterval(interval);
                window.location.href = "<?= URLROOT; ?>/products";
            }
        }, 1000);
    </script>
</body>

</html>