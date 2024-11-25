<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .success-container {
            text-align: center;
            margin-top: 20vh;
        }

        .checkmark-circle {
            display: inline-block;
            width: 100px;
            height: 100px;
            border: 5px solid #4CAF50;
            border-radius: 50%;
            margin-bottom: 20px;
            position: relative;
        }

        .checkmark-circle i {
            font-size: 40px;
            color: #4CAF50;
            position: absolute;
            top: 24px;
            left: 24px;
            opacity: 0;
            transform: scale(0);
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .checkmark-circle i.checkmark-animation {
            opacity: 1;
            transform: scale(1);
        }

        h1 {
            font-size: 24px;
            color: #4CAF50;
        }
    </style>
</head>

<body onload="showSuccessMessage()">
    <script>
        function showSuccessMessage() {
            document.body.innerHTML = `
                <div class="success-container">
                    <div class="checkmark-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <h1>Order Successful!</h1>
                </div>
            `;

            // Animation for the checkmark
            setTimeout(() => {
                document.querySelector(".checkmark-circle i").classList.add("checkmark-animation");
            }, 100);

            // Redirect to the index page after 4 seconds
            setTimeout(() => {
                window.location.href = "<?php echo URLROOT; ?>/products"; // Redirect to product listing
            }, 4000); // Allow animation to complete before redirect
        }
    </script>
</body>

</html>