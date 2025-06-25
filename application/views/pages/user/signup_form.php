<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('includes/head'); ?>
</head>

<body class="bg-light">
  <div id="global_error_box" class="d-none" style="position: absolute;z-index: 2;right: 10px;border-radius: 10px;top: 10px;font-weight: 700;padding: 10px;background-color: red;">

  </div>
  </div>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow rounded-4">
          <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Signup</h4>
          </div>
          <div class="card-body p-4">
            <form method="post" action="<?= current_url() ?>" id="sign_up" novalidate>
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


              <div class="mb-3 d-none" id="otp_div">
                <label for="otp" class="form-label">Enter Otp</label>
                <input type="number" name="otp" id="otp" class="form-control">
              </div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-success btn-lg">Send Otp</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php $this->load->view('includes/foot'); ?>

<script>
  $('#sign_up').submit(function(e) {
    e.preventDefault();
    let valid = true;

    // Clear previous errors
    $('.error-msg').html('');

    // Get form values
    const username = $('input[name="username"]').val().trim();
    const email = $('input[name="email"]').val().trim();
    const phone = $('input[name="phone"]').val().trim();
    const password = $('input[name="password"]').val().trim();

    // Validate Full Name
    if (username === '') {
      $('#error_username').text('Full name is required');
      valid = false;
    }

    // Validate Email
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (email === '') {
      $('#error_email').text('Email is required');
      valid = false;
    } else if (!emailRegex.test(email)) {
      $('#error_email').text('Invalid email format');
      valid = false;
    }

    // Validate Phone (optional but must be valid if entered)
    const phoneRegex = /^[6-9][0-9]{9}$/;
    if (phone !== '' && !phoneRegex.test(phone)) {
      $('#error_phone').text('Invalid Indian phone number');
      valid = false;
    }

    // Validate Password
    if (password.length < 6) {
      $('#error_password').text('Password must be at least 6 characters');
      valid = false;
    }

    // Submit via AJAX if valid
    if (valid) {
      const fdata = new FormData($(this)[0]);
      const url = $(this).attr('action');

      submit_form_data_ajax(url, fdata, function(response) {
        let res = JSON.parse(response);

        $('#global_error_box').hide(); // stop any ongoing animation

        if (res.status === true) {
          // window.location.href = res.redirect;
        } else {
          $('#global_error_box')
            .removeClass('d-none')
            .html(res.message || 'Something went wrong.')
            .css('background-color', 'red')
            .fadeIn(300)
            .delay(3000)
            .fadeOut(500);
        }
      });

    }
  });
</script>

</html>