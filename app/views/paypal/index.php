<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Checkout</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/paypal_style.css">
    <script src="https://www.paypal.com/sdk/js?client-id=Acwul7cJu4m2PiwDoyqnxBQ6Gz5l1Kv13jbfk6m0GKWT13fgMS9yvXjTjs-ds82ppHT5DQ9dYY0ObVHj&currency=USD"></script>
</head>

<body>
    <div class="checkout-container">
        <h2>Complete Your Purchase</h2>
        <img src="<?php echo URLROOT; ?>/public/images/paypal_logo.png" alt="Your Logo" class="logo" />
        <div id="paypal-button-container"></div>
    </div>

    <script>
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '20.00' // Replace with dynamic amount
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);

                    // Send transaction data to server for further processing
                    fetch('/app/services/capture_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderID: data.orderID
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Server response:', data);
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        // Redirect to Products/index on success
                        window.location.href = "<?php echo URLROOT; ?>/OrderController/checkout";
                    });
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>

</html>
