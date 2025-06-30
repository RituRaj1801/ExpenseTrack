<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Signup Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      background: linear-gradient(135deg, #71b7e6, #9b59b6);
      font-family: 'Poppins', sans-serif;
      padding: 30px 10px;
    }

    .container {
      max-width: 700px;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .form-title {
      font-size: 26px;
      font-weight: 600;
      position: relative;
      margin-bottom: 30px;
    }

    .form-title::after {
      content: "";
      width: 40px;
      height: 4px;
      background: linear-gradient(135deg, #71b7e6, #9b59b6);
      position: absolute;
      bottom: -10px;
      left: 0;
      border-radius: 5px;
    }

    .strength-bar {
      display: flex;
      height: 5px;
      margin-top: 5px;
      gap: 2px;
    }

    .strength-bar div {
      flex: 1;
      background-color: #ddd;
      border-radius: 2px;
    }

    .strength-1 .bar-1 {
      background-color: red;
    }

    .strength-2 .bar-1,
    .strength-2 .bar-2 {
      background-color: orange;
    }

    .strength-3 .bar-1,
    .strength-3 .bar-2,
    .strength-3 .bar-3,
    .strength-3 .bar-4 {
      background-color: green;
    }

    .error-msg {
      color: red;
      font-size: 0.9em;
      margin-top: 4px;
    }

    #response_msg {
      font-weight: 500;
      font-size: 0.95rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-title">Registration</div>
    <div id="response_msg" class="alert d-none" role="alert"></div>
    <form id="sign_up" action="#" method="post">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="username" class="form-control" required>
          <div id="error_username" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
          <div id="error_email" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" required>
          <div id="error_phone" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" id="password_field" class="form-control" required>
          <div class="strength-bar" id="strength_bar">
            <div class="bar-1"></div>
            <div class="bar-2"></div>
            <div class="bar-3"></div>
            <div class="bar-4"></div>
          </div>
          <div id="error_password" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Confirm Password</label>
          <input type="text" name="confirm_password" class="form-control" required>
          <div id="error_confirm_password" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Enter OTP</label>
          <div class="input-group">
            <input type="text" name="otp" class="form-control" required>
            <button type="button" class="btn btn-outline-primary" onclick="validateFormForOtp()">Send OTP</button>
          </div>
          <div id="error_otp" class="error-msg"></div>
        </div>
        <div class="col-12">
          <label class="form-label d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="male">
            <label class="form-check-label">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="female">
            <label class="form-check-label">Female</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="" checked>
            <label class="form-check-label">Prefer not to say</label>
          </div>
        </div>
        <div class="col-12 mt-3">
          <button type="submit" class="btn btn-primary w-100">Register</button>
        </div>
      </div>
    </form>
    <div class="text-center mt-3">
      <span>Already have an account?</span>
      <a href="<?= site_url('login'); ?>" class="text-primary fw-semibold">Login</a>
    </div>
  </div>

  <script>
    const STRONG_PASSWORD_REGEX = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

    function updateStrengthBar(strength) {
      const bar = document.getElementById('strength_bar');
      bar.className = 'strength-bar';
      if (strength === 'weak') bar.classList.add('strength-1');
      else if (strength === 'medium') bar.classList.add('strength-2');
      else if (strength === 'strong') bar.classList.add('strength-3');
    }

    function checkPasswordStrength(password) {
      if (STRONG_PASSWORD_REGEX.test(password)) {
        updateStrengthBar('strong');
        return 'strong';
      } else if (password.length < 6) {
        updateStrengthBar('weak');
        return 'weak';
      } else {
        updateStrengthBar('medium');
        return 'medium';
      }
    }

    $('#password_field').on('keyup', function() {
      checkPasswordStrength($(this).val());
    });

    function validateForm(checkOtp = false) {
      let valid = true;
      $('.error-msg').html('');

      const username = $('input[name="username"]').val().trim();
      const email = $('input[name="email"]').val().trim();
      const phone = $('input[name="phone"]').val().trim();
      const password = $('input[name="password"]').val().trim();
      const confirmPassword = $('input[name="confirm_password"]').val().trim();
      const otp = $('input[name="otp"]').val().trim();

      if (!username) {
        $('#error_username').text('Full Name is required');
        valid = false;
      }
      if (!email || !/^[\w.-]+@[\w.-]+\.\w+$/.test(email)) {
        $('#error_email').text('Valid email is required');
        valid = false;
      }
      if (!/^[6-9][0-9]{9}$/.test(phone)) {
        $('#error_phone').text('Valid phone number is required');
        valid = false;
      }
      if (!STRONG_PASSWORD_REGEX.test(password)) {
        $('#error_password').text('Password must be strong');
        valid = false;
      }
      if (password !== confirmPassword) {
        $('#error_confirm_password').text('Passwords do not match');
        valid = false;
      }
      if (checkOtp && otp === '') {
        $('#error_otp').text('OTP is required');
        valid = false;
      }
      return valid;
    }

    function handleResponse(response) {
      const msgBox = $('#response_msg');
      msgBox.removeClass('d-none alert-success alert-danger').html('');
      if (response.status === true) {
        msgBox.addClass('alert-success').html(response.message).fadeIn();
      } else {
        msgBox.addClass('alert-danger').html(response.message || 'Something went wrong').fadeIn();
        if (response.errors) {
          $.each(response.errors, function(key, msg) {
            $('#error_' + key).html(msg);
          });
        }
      }
      setTimeout(() => msgBox.fadeOut(), 5000);
    }

    function validateFormForOtp() {
      if (!validateForm(false)) return;
      const url = "<?= site_url('user/send_login_otp'); ?>";
      const fData = new FormData($('#sign_up')[0]);
      $.ajax({
        type: 'POST',
        url: url,
        data: fData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: handleResponse,
        error: () => $('#response_msg').removeClass('d-none alert-success').addClass('alert-danger').html('❌ Network error').fadeIn()
      });
    }

    $('#sign_up').submit(function(e) {
      e.preventDefault();

      // Clear old messages
      $('#response_msg').removeClass('alert-danger alert-success d-none').html('');
      $('.error-msg').html('');

      // Run local validation first
      if (!validateForm()) {
        $('#response_msg')
          .addClass('alert alert-danger')
          .html('Please fix the form errors before submitting.')
          .fadeIn();

        setTimeout(() => $('#response_msg').fadeOut(), 5000);
        return;
      }

      // Submit form via AJAX to current_url
      const url = window.location.href;
      const fData = new FormData($('#sign_up')[0]);

      $.ajax({
        type: 'POST',
        url: url,
        data: fData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          const msgBox = $('#response_msg');
          msgBox.removeClass('alert-danger alert-success d-none').html('');

          if (response.status === true) {
            msgBox.addClass('alert alert-success')
              .html(response.message || 'Registration successfully!. Please login to continue.')
              .fadeIn();
            setTimeout(() => {
              window.location.href = "<?= site_url('login'); ?>";
            }, 3000);
          } else {
            msgBox.addClass('alert alert-danger')
              .html(response.message || 'Something went wrong.')
              .fadeIn();

            // Display individual field-level errors (from response.errors array)
            if (response.errors && Array.isArray(response.errors)) {
              response.errors.forEach(msg => {
                // Try to map the error to input field if it mentions field name
                if (msg.toLowerCase().includes('name')) {
                  $('#error_username').text(msg);
                } else if (msg.toLowerCase().includes('email')) {
                  $('#error_email').text(msg);
                } else if (msg.toLowerCase().includes('phone')) {
                  $('#error_phone').text(msg);
                } else if (msg.toLowerCase().includes('password') && msg.toLowerCase().includes('confirm')) {
                  $('#error_confirm_password').text(msg);
                } else if (msg.toLowerCase().includes('password')) {
                  $('#error_password').text(msg);
                } else if (msg.toLowerCase().includes('otp')) {
                  $('input[name="otp"]').addClass('is-invalid');
                }
              });
            }
          }

          setTimeout(() => msgBox.fadeOut(), 6000);
        },
        error: function() {
          $('#response_msg')
            .removeClass('alert-success d-none')
            .addClass('alert alert-danger')
            .html('❌ Network/server error. Please try again.')
            .fadeIn();

          setTimeout(() => $('#response_msg').fadeOut(), 5000);
        }
      });
    });
  </script>
</body>

</html>