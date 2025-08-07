<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('include/head'); ?>
    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        #responseBox {
            display: none;
        }
    </style>
</head>

<body>
    <?php $this->load->view('include/header'); ?>

    <div class="container">
        <div class="form-container">
            <h3 class="text-center mb-4">Expense Tracker</h3>

            <div id="responseBox" class="alert" role="alert"></div>

            <form id="add_expense" method="post">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" class="form-control" name="amount" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" required>
                </div>

                <div class="form-group">
                    <label for="txn_type">Transaction Type</label>
                    <select name="txn_type" class="form-control" required>
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="" selected disabled>-- Select Category --</option>
                        <option value="Travel">Travel</option>
                        <option value="Food">Food</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Groceries">Groceries</option>
                        <option value="Bills">Bills</option>
                        <option value="Health">Health</option>
                        <option value="Saving">Saving</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Add Expense</button>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= base_url('show_expense') ?>" class="btn btn-success btn-block">Show Expense Details</a>
            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap 4 Scripts -->
    <?php $this->load->view('include/footer'); ?>
    <?php $this->load->view('include/foot'); ?>
    <script>
        $('#add_expense').on('submit', function(e) {
            e.preventDefault();

            const amount = $('input[name="amount"]').val().trim();
            const description = $('input[name="description"]').val().trim();
            const category = $('select[name="category"]').val();

            let errorMsg = '';
            if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
                errorMsg += '<li>Amount must be a positive number.</li>';
            }
            if (!description || description.length < 2) {
                errorMsg += '<li>Description is required and should be at least 2 characters.</li>';
            }
            if (!category) {
                errorMsg += '<li>Please select a category.</li>';
            }

            if (errorMsg) {
                $('#responseBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(`<ul>${errorMsg}</ul>`)
                    .fadeIn();
                return;
            }

            const formData = new FormData(this);
            const url = "<?= current_url(); ?>";
            submit_form_data_ajax(url, formData, function(response) {
                try {
                    const res = JSON.parse(response);
                    const box = $('#responseBox');
                    box.removeClass('alert-success alert-danger');

                    if (res.status) {
                        box.addClass('alert-success').html(res.message);
                        $('#add_expense')[0].reset();
                    } else {
                        box.addClass('alert-danger').html(res.message);
                    }

                    box.fadeIn();
                    setTimeout(() => box.fadeOut(), 3000);
                } catch (err) {
                    $('#responseBox')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .html("Error parsing server response.")
                        .fadeIn();
                }
            }, function() {
                $('#responseBox')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html("Network error. Please try again.")
                    .fadeIn();
            });


        });
    </script>

</body>

</html>