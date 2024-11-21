<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Category</title>
    <link rel="stylesheet" href="/path/to/your/style.css"> <!-- Adjust path to your CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: flex-start; /* Align button to the left */
            margin-top: 20px; /* Space above the button container */
        }

        .button-container a {
            text-decoration: none;
        }

        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .warning {
            font-weight: bold;
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 600px) {
            button {
                width: 100%;
                margin-bottom: 10px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h1>Category: <?php echo htmlspecialchars($data['category']->categoryName); ?></h1>

    <h2>Products under this Category:</h2>
    <table border="1">
        <tr>
            <th>Product Name</th>
        </tr>
        <?php if (!empty($data['products'])): ?>
            <?php foreach ($data['products'] as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product->productName); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td>No products found under this category.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Go Back button positioned below the table -->
    <div class="button-container">
        <a href="<?php echo URLROOT; ?>/categories"><button>Go Back</button></a>
    </div>
</body>
</html>
