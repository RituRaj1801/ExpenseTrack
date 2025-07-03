<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ✅ Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ✅ jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <style>
        body {
            background: #f5f5f5;
            padding-top: 60px;
        }

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

        #pageLoader {
            position: fixed;
            z-index: 9999;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h3 class="text-center mb-4">Expense Tracker</h3>

            <div id="responseBox" class="alert" role="alert"></div>

            <form id="add_expense" method="post">
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" name="amount" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" class="form-select" required>
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

                <button type="submit" class="btn btn-primary w-100">Add Expense</button>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= base_url('show_expense') ?>" class="btn btn-success w-100">Show Expense Details</a>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div id="pageLoader" style="display: none;">
        <div class="spinner-border text-light" role="status" style="width: 4rem; height: 4rem;"></div>
    </div>

    <script>
        $('#add_expense').on('submit', function(e) {
            e.preventDefault();

            // Collect field values
            const amount = $('input[name="amount"]').val().trim();
            const description = $('input[name="description"]').val().trim();
            const category = $('select[name="category"]').val();

            // Validate fields
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
                return; // Stop the form from submitting
            }

            // All good, proceed with AJAX
            const formData = new FormData(this);
            const url = "<?php echo current_url(); ?>";

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#pageLoader').show();
                    $('#responseBox').hide();
                },
                success: function(response) {
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

                        // ✅ Auto-fade the message after 3 seconds
                        setTimeout(() => {
                            box.fadeOut();
                        }, 3000);

                    } catch (err) {
                        $('#responseBox')
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .html("Error parsing server response.")
                            .fadeIn();
                    }
                },
                error: function() {
                    $('#responseBox')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .html("Network error. Please try again.")
                        .fadeIn();
                },
                complete: function() {
                    $('#pageLoader').hide();
                }
            });
        });
    </script>

</body>

</html>