<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup Form</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Signup</h4>
                    </div>
                    <div class="card-body p-4">


                        <form method="post" action="<?= current_url() ?>" id="sign_up">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="username" class="form-control" required>
                                <div class="text-danger error-msg" id="error_username"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required autocomplete="off">
                                <div class="text-danger error-msg" id="error_email"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number (optional)</label>
                                <input type="text" name="phone" class="form-control">
                                <div class="text-danger error-msg" id="error_phone"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                <div class="text-danger error-msg" id="error_password"></div>
                            </div>


                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">Sign Up</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    $('#sign_up').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'post',
            data: $(this).serialize(),
            success: function(response) {
                let res = JSON.parse(response);
                // Clear old errors
                $('.error-msg').html('');

                if (res.status) {
                    $('body').html(res.html); // Load OTP form
                } else {
                    if (res.errors) {
                        for (let field in res.errors) {
                            if (res.errors[field]) {
                                $('#error_' + field).html(res.errors[field]);
                            }
                        }
                    }
                }
            }

        })
    })
</script>

</html>