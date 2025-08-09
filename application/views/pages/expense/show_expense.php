<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('include/head'); ?>

    <style>
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

        #expenseTable thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
            z-index: 2;
        }
    </style>
</head>


<body>
    <?php $this->load->view('include/header'); ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                Expense Records of
                <form method="GET" action="<?= current_url() ?>" class="d-inline">
                    <select class="form-control d-inline w-auto" name="month" id="month" onchange="this.form.submit()" style="display: inline-block;">
                        <?php foreach ($month_array as $month_number => $month_name) { ?>
                            <option value="<?= $month_number ?>" <?= ($month_number === $currentMonth) ? 'selected' : '' ?>>
                                <?= $month_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>
            </h2>
        </div>



        <!-- Expense Table -->
        <div id="expenseTable" class="table-responsive" style="overflow-x: auto;">
            <table id="myTable" class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Txn Type</th>
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
                    <th>Total Amount(Debit Only)</th>
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

    <?php $this->load->view('include/footer'); ?>
    <?php $this->load->view('include/foot'); ?>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ]
            });
        })
    </script>
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