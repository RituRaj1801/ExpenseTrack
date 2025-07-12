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

    .error-msg {
      color: red;
      font-size: 0.9em;
      margin-top: 4px;
      display: block;
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
          <label for="user_name" class="form-label">Full Name</label>
          <input type="text" id="user_name" name="user_name" class="form-control" required placeholder="Enter your full name" autocomplete="off">
          <div id="error_user_name" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label for="user_email" class="form-label">Email</label>
          <input type="email" id="user_email" name="user_email" class="form-control" required placeholder="Enter your email address" autocomplete="off">
          <div id="error_user_email" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label for="user_phone" class="form-label">Phone</label>
          <input type="number" id="user_phone" name="user_phone" class="form-control" required placeholder="Enter 10-digit mobile number" autocomplete="off">
          <div id="error_user_phone" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label for="password_field" class="form-label">Password</label>
          <input type="password" id="password_field" name="password" class="form-control" required placeholder="Create a password" autocomplete="off">
          <div id="error_password" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Re-enter your password" autocomplete="off">
          <div id="error_confirm_password" class="error-msg"></div>
        </div>
        <div class="col-md-6">
          <label for="otp" class="form-label">Enter OTP</label>
          <div class="input-group">
            <input type="text" pattern="\d{6}" maxlength="6" inputmode="numeric" id="otp" name="otp" class="form-control" required placeholder="Enter the 6-digit OTP" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)">
            <button type="button" class="btn btn-outline-primary" onclick="validateFormForOtp()">Send OTP</button>
          </div>
          <div id="error_otp" class="error-msg"></div>
        </div>
        <div class="col-12">
          <label class="form-label d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="gender_male" name="gender" value="F" required>
            <label class="form-check-label" for="gender_male">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="gender_female" name="gender" value="M" required>
            <label class="form-check-label" for="gender_female">Female</label>
          </div>
          <div id="error_gender" class="error-msg"></div>
        </div>
        <div class="col-12 mt-3">
          <button type="submit" class="btn btn-primary w-100">Register</button>
        </div>
      </div>
    </form>

  </div>
  <?php $this->load->view('includes/foot'); ?>
  <script>
    var USER_REQUESTED_FOR_OTP = false;
    var requested_email = "";

    function validateFormForOtp() {
      const user_email = $('#user_email').val().trim();
      if (!user_email || !/^[\w.-]+@[\w.-]+\.\w+$/.test(user_email)) {
        $('#error_user_email').show().text('Enter a valid email before requesting OTP');
        fadeOutErrors();
        return;
      }
      const url = "<?= site_url('signup'); ?>";
      var fData = new FormData();
      fData.append('user_email', user_email);
      fData.append('action', "send_otp");
      submit_form_data_ajax(url, fData, function(data) {
        const response = JSON.parse(data)
        handleResponse(response.status, response.message);
        if (response.status) {
          USER_REQUESTED_FOR_OTP = true;
          requested_email = user_email;
        }
      });
    }

    $('#sign_up').submit(function(e) {
      e.preventDefault();
      if (!validateForm()) {
        $('#response_msg')
          .removeClass('d-none')
          .addClass('alert alert-danger')
          .html('Please fix the form errors before submitting.');
        setTimeout(() => $('#response_msg').fadeOut(), 5000);
        return;
      }
      if (!USER_REQUESTED_FOR_OTP) {
        $('#error_otp').html("Please request for an OTP before registering.");
        setTimeout(() => $('#error_otp').fadeOut(3000), 300);
        return;
      }
      const url = window.location.href;
      var fData = new FormData($('#sign_up')[0]);
      fData.append('action', "verify_otp");
      submit_form_data_ajax(url, fData, function(data) {
        var response = JSON.parse(data);
        if (!response.status) {
          $('#response_msg')
            .removeClass('d-none')
            .addClass('alert alert-danger')
            .html(response.message);
          setTimeout(() => $('#response_msg').fadeOut(), 5000);
        }
        handleResponse(response.status, response.message);
        if (response.status === true) {
          setTimeout(() => {
            window.location.href = "<?= site_url('login'); ?>";
          }, 3000);
        }
      });
    });

    function validateForm() {
      let valid = true;
      $('.error-msg').html('').show();

      const user_name = $('#user_name').val().trim();
      const user_email = $('#user_email').val().trim();
      const user_phone = $('#user_phone').val().trim();
      const password = $('#password_field').val().trim();
      const confirmPassword = $('#confirm_password').val().trim();
      const otp = $('#otp').val().trim();
      const gender = $('input[name="gender"]:checked').val();

      if (!user_name) {
        $('#error_user_name').text('Full Name is required');
        valid = false;
      }

      if (!user_email || !/^[\w.-]+@[\w.-]+\.\w+$/.test(user_email)) {
        $('#error_user_email').text('Valid email is required');
        valid = false;
      } else if (user_email !== requested_email) {
        $('#error_user_email').text('Email has been changed after requesting OTP');
        valid = false;
      }

      if (!/^[6-9][0-9]{9}$/.test(user_phone)) {
        $('#error_phone').text('Valid 10-digit phone number required');
        valid = false;
      }

      if (password.length < 6) {
        $('#error_password').text('Password must be at least 6 characters');
        valid = false;
      }

      if (password !== confirmPassword) {
        $('#error_confirm_password').text('Passwords do not match');
        valid = false;
      }

      if (!otp || otp.length !== 6) {
        $('#error_otp').text('OTP must be 6 digits');
        valid = false;
      }

      if (!gender) {
        $('#error_gender').text('Gender is required');
        valid = false;
      }

      fadeOutErrors();
      return valid;
    }

    function handleResponse(status, message) {
      const msgBox = $('#response_msg');
      msgBox.removeClass('d-none alert-success alert-danger').html('');
      if (status === true) {
        msgBox.addClass('alert alert-success').html(message);
      } else {
        msgBox.addClass('alert alert-danger').html(message || 'Something went wrong');
      }
      msgBox.show();
      setTimeout(() => msgBox.fadeOut(), 4000);
    }

    function fadeOutErrors() {
      setTimeout(() => {
        $('.error-msg').fadeOut();
      }, 5000);
    }
  </script>
</body>

</html>