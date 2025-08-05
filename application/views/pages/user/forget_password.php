<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - Expense Track</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    :root {
      --background: #1a1a2e;
      --color: #ffffff;
      --primary-color: #0f3460;
    }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: var(--background);
      color: var(--color);
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 1rem;
    }

    .form-container {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 0 36px 1px rgba(0, 0, 0, 0.2);
      padding: 2rem;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(20px);
    }

    h1 {
      text-align: center;
      margin-bottom: 1rem;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 5px;
    }

    .form-control {
      width: 100%;
      padding: 12px;
      background-color: #9191911f;
      border: none;
      border-radius: 5px;
      color: var(--color);
      font-size: 15px;
    }

    .input-group {
      display: flex;
      gap: 8px;
    }

    .input-group .form-control {
      flex: 1;
    }

    .btn {
      padding: 12px 16px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      transform: scale(1.02);
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .form-text {
      font-size: 13px;
      color: #ff6b6b;
      display: none;
    }

    .text-success {
      color: #4cd137 !important;
    }

    .text-danger {
      color: #ff6b6b !important;
    }

    .show {
      display: block !important;
    }

    #response_msg {
      display: none;
      transition: opacity 0.4s ease;
    }

    button[type="submit"] {
      width: 100%;
      margin-top: 1rem;
    }

    .btn-link {
      margin-top: 0.75rem;
      width: 100%;
      display: block;
      text-align: center;
      background: transparent;
      color: var(--color);
      border: 1px solid var(--primary-color);
    }

    .btn-link:hover {
      background-color: var(--primary-color);
    }

    @media (max-width: 500px) {
      .form-container {
        padding: 1.5rem;
      }

      .btn,
      .btn-link {
        font-size: 13px;
        padding: 10px 12px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <h1>Reset Password</h1>
      <form id="forgot_form" action="<?= site_url('reset_password'); ?>" method="POST">

        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email" required />
          <small id="email_error" class="form-text text-danger"></small>
        </div>

        <!-- OTP + Send OTP -->
        <div class="form-group">
          <label for="otp">OTP</label>
          <div class="input-group">
            <input type="text" pattern="\d{6}" maxlength="6" inputmode="numeric" id="otp" name="otp" class="form-control"
              required placeholder="Enter the 6-digit OTP"
              oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)" />
            <button type="button" class="btn" id="sendOtpBtn">Send OTP</button>
          </div>
          <small id="otp_error" class="form-text text-danger"></small>
        </div>

        <!-- New Password -->
        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password"
            required />
          <small id="new_password_error" class="form-text text-danger"></small>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="text" name="confirm_password" id="confirm_password" class="form-control"
            placeholder="Confirm Password" required />
          <small id="confirm_password_error" class="form-text text-danger"></small>
        </div>

        <!-- Response -->
        <small id="response_msg" class="form-text"></small>

        <!-- Submit -->
        <button type="submit" class="btn">Submit</button>

        <!-- Navigation -->
        <a href="<?= site_url('login'); ?>" class="btn btn-link">Login</a>
        <a href="<?= site_url('signup'); ?>" class="btn btn-link">Register</a>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      var forget_password_url = '<?php echo site_url('forget_password') ?>';

      $('#sendOtpBtn').click(function() {
        let email = $('#email').val().trim();
        $('#email_error').removeClass('show').text('');
        $('#response_msg').stop(true, true).hide().removeClass('text-success text-danger show');

        if (email === '') {
          $('#email_error').text('Email is required').addClass('show');
        } else {
          $.post(forget_password_url, {
            email: email,
            action: 'send_otp'
          }, function(res) {
            let response = typeof res === 'object' ? res : JSON.parse(res);
            $('#response_msg')
              .text(response.message)
              .removeClass('text-danger text-success')
              .addClass(response.status ? 'text-success' : 'text-danger')
              .fadeIn(300, function() {
                setTimeout(() => {
                  $('#response_msg').fadeOut(500);
                }, 4000);
              });
          });
        }
      });

      $('#forgot_form').submit(function(e) {
        e.preventDefault();

        let email = $('#email').val().trim();
        let otp = $('#otp').val().trim();
        let newPass = $('#new_password').val().trim();
        let confirmPass = $('#confirm_password').val().trim();
        let valid = true;

        $('.form-text').removeClass('show').text('');
        $('#response_msg').removeClass('show text-danger text-success');

        if (!otp) {
          $('#otp_error').text('OTP is required').addClass('show');
          valid = false;
        }

        if (newPass.length < 6) {
          $('#new_password_error').text('Password must be at least 6 characters').addClass('show');
          valid = false;
        }

        if (newPass !== confirmPass) {
          $('#confirm_password_error').text('Passwords do not match').addClass('show');
          valid = false;
        }

        if (valid) {
          $.ajax({
            type: "POST",
            url: forget_password_url,
            data: {
              email: email,
              otp: otp,
              new_password: newPass,
              cnf_new_password: confirmPass,
              action: 'verify_otp'
            },
            success: function(res) {
              const response = typeof res === 'object' ? res : JSON.parse(res);
              $('#response_msg')
                .text(response.message)
                .removeClass('text-danger text-success')
                .addClass(response.status ? 'text-success show' : 'text-danger show');

              if (response.status) {
                $('#forgot_form')[0].reset();
                setTimeout(() => {
                  window.location.href = "<?= site_url('login'); ?>";
                }, 2000);
              }
            },
            error: function() {
              $('#response_msg')
                .text('Server error. Please try again later.')
                .addClass('text-danger show');
            }
          });
        }
      });
    });
  </script>
</body>

</html>