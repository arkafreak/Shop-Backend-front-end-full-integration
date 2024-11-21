<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="logo">
                <h3>Admin</h3>
            </div>

            <!-- Hamburger button for mobile view -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarNav" aria-controls="sidebarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <ul class="nav flex-column text-center">
                <li class="nav-item"><a href="<?php echo URLROOT; ?>/Products/index" class="nav-link">Products</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Orders</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Sales</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Users</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Reports</a></li>
                <li class="nav-item"><a href="#bottom-section" class="nav-link">Graphs and Charts</a></li>
            </ul>
        </div>


        <div class="content">
            <header class="d-flex justify-content-between align-items-center">
                <h1>Admin Dashboard</h1>
                <a href="<?php echo URLROOT; ?>/Products/index" class="btn btn-outline-dark text-dark fw-bold hover-text-light rounded-2">Go back</a>
            </header>
            <div class="statistics-cards d-flex justify-content-between flex-wrap my-4">
                <div class="card small-card  rounded-4">
                    <div class="card-body bg-success rounded-4">
                        <h6 class="card-title text-center">Total Sales</h6>
                        <p class="card-text fs-5 fw-bold text-center"><?php echo $data['totalSales']; ?></p>
                    </div>
                </div>

                <div class="card small-card mx-1 rounded-4">
                    <div class="card-body bg-danger rounded-4">
                        <h6 class="card-title text-center">Total Revenue</h6>
                        <p class="card-text fs-5 fw-bold text-center">₹<?php echo number_format($data['totalRevenue'], 2); ?></p>
                    </div>
                </div>
                <div class="card small-card mx-1 rounded-4">
                    <div class="card-body bg-warning rounded-4">
                        <h6 class="card-title text-center">Pending Orders</h6>
                        <p class="card-text fs-5 fw-bold text-center"><?php echo $data['pendingOrders']; ?></p>
                    </div>
                </div>
                <div class="card small-card mx-1  rounded-4">
                    <div class="card-body bg-info rounded-4">
                        <h6 class="card-title text-center">Completed Orders</h6>
                        <p class="card-text fs-5 fw-bold text-center"><?php echo $data['completedOrders']; ?></p>
                    </div>
                </div>
            </div>


            <section class="my-5">
                <h2 class="section-title">Purchased Products Grouped by Date & Time</h2>
                <div class="table-container">
                    <?php foreach ($data['groupedProducts'] as $dateTime => $products): ?>
                        <div class="table-card">
                            <div class="table-header">
                                <h3><?php echo htmlspecialchars($dateTime); ?></h3>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Brand</th>
                                        <th>Quantity Purchased</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product->id); ?></td>
                                            <td><?php echo htmlspecialchars($product->productName); ?></td>
                                            <td><?php echo htmlspecialchars($product->brand); ?></td>
                                            <td><?php echo htmlspecialchars($product->quantity); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section id="bottom-section" class="my-5">
                <h2 class="section-title">Sales by Payment Method & Revenue Over Time</h2>
                <div class="charts-container">
                    <div class="chart-card">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="chart-card">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Prepare the data for the pie chart
        var paymentMethods = <?php echo json_encode($data['salesData']); ?>;
        var labels = [];
        var data = [];
        paymentMethods.forEach(function(item) {
            labels.push(item.paymentMethod);
            data.push(item.total_sales);
        });

        // Create the pie chart
        var ctx = document.getElementById('paymentMethodChart').getContext('2d');
        var paymentMethodChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales by Payment Method',
                    data: data,
                    backgroundColor: ['#FF6347', '#36A2EB', '#FFCD56', '#4BC0C0'],
                    hoverOffset: 4
                }]
            }
        });

        // Prepare the data for the line chart
        var revenueData = <?php echo json_encode($data['revenueData']); ?>;
        var labels2 = [];
        var data2 = [];

        revenueData.forEach(function(item) {
            labels2.push(item.date); // X-axis: Date
            data2.push(parseFloat(item.revenue)); // Y-axis: Revenue, convert to number
        });

        // Create the line chart
        var ctx2 = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labels2, // Dates on the X-axis
                datasets: [{
                    label: 'Total Revenue',
                    data: data2, // Revenue data (now as numbers)
                    borderColor: '#4BC0C0',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Toggle the sidebar when the hamburger button is clicked
        $(document).ready(function() {
            $(".navbar-toggler").click(function() {
                $(".sidebar").toggleClass("show");
            });
        });
    </script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>