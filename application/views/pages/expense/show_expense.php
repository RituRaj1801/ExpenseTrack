<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expense Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2,
        h4 {
            color: #343a40;
        }

        table th,
        table td {
            vertical-align: middle;
        }

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Expense Records</h2>

        <div class="mb-4 text-end">
            <a href="<?= base_url('homepage') ?>" class="btn btn-primary">← Back to Home</a>
        </div>

        <!-- Expense Table -->
        <div id="expenseTable">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $expenseTable ?>
                </tbody>
            </table>
        </div>

        <!-- Grouped by Category Summary -->
        <h4 class="mt-5 mb-3 text-center">Total Expense per Category</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th>Category</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?= $categoryTable ?>
            </tbody>
        </table>

        <!-- Pie Chart -->
        <h4 class="mt-5 mb-3 text-center">Expense Chart (Category-wise)</h4>
        <div class="chart-container mb-5">
            <canvas id="expensePieChart"></canvas>
        </div>
    </div>

    <script>
        const chartData = <?= $chart_data ?>;

        const labels = chartData.map(item => item.category);
        const data = chartData.map(item => parseFloat(item.total_amount));

        const colors = [
            '#007bff', '#28a745', '#ffc107', '#dc3545',
            '#17a2b8', '#6f42c1', '#fd7e14', '#20c997',
            '#e83e8c', '#6610f2'
        ];

        new Chart(document.getElementById("expensePieChart"), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expense by Category',
                    data: data,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed;
                                return `${label}: ₹${value.toLocaleString()}`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
