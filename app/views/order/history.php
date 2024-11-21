<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
</head>

<body>
    <h1>Your Order History</h1>
    <?php if (!empty($data['orderHistory'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Original Price</th>
                    <th>Selling Price</th>
                    <th>Weight</th>
                    <th>Order Date</th>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['orderHistory'] as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['productName']); ?></td>
                        <td><?php echo htmlspecialchars($order['brand']); ?></td>
                        <td><?php echo htmlspecialchars($order['originalPrice']); ?></td>
                        <td><?php echo htmlspecialchars($order['sellingPrice']); ?></td>
                        <td><?php echo htmlspecialchars($order['weight']); ?></td>
                        <td><?php echo htmlspecialchars($order['orderDate']); ?></td>
                        <td><?php echo htmlspecialchars($order['paymentMethod']); ?></td>
                        <td><?php echo htmlspecialchars($order['totalAmount']); ?></td>
                        <td><?php echo htmlspecialchars($order['orderStatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders in your history.</p>
    <?php endif; ?>
    <a href="<?php echo URLROOT; ?>/Products/index">Back to products page</a>
</body>


</html>