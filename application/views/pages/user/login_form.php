<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Expense Track</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    :root {
      --background: #1a1a2e;
      --color: #ffffff;
      --primary-color: #0f3460;
    }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      box-sizing: border-box;
      font-family: "poppins";
      background: var(--background);
      color: var(--color);
      letter-spacing: 1px;
      transition: background 0.2s ease;
    }
    a { text-decoration: none; color: var(--color); }
    h1 { font-size: 2.5rem; }
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      position: relative;
      width: 22.2rem;
    }
    .form-container {
      border: 1px solid hsla(0, 0%, 65%, 0.158);
      box-shadow: 0 0 36px 1px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
      backdrop-filter: blur(20px);
      z-index: 99;
      padding: 2rem;
    }
    .login-container form input {
      display: block;
      padding: 14.5px;
      width: 100%;
      margin: 2rem 0 0.5rem;
      color: var(--color);
      outline: none;
      background-color: #9191911f;
      border: none;
      border-radius: 5px;
      font-weight: 500;
      letter-spacing: 0.8px;
      font-size: 15px;
    }
    .login-container form input:focus {
      box-shadow: 0 0 16px 1px rgba(0, 0, 0, 0.2);
      animation: wobble 0.3s ease-in;
    }
    .login-container form button {
      background-color: var(--primary-color);
      color: white;
      padding: 13px;
      border-radius: 5px;
      font-size: 18px;
      letter-spacing: 1.5px;
      font-weight: bold;
      width: 100%;
      cursor: pointer;
      margin: 1.5rem 0;
      border: none;
    }
    .login-container form button:hover {
      box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.15);
      transform: scale(1.02);
    }
    .circle {
      width: 8rem;
      height: 8rem;
      background: var(--primary-color);
      border-radius: 50%;
      position: absolute;
    }
    .illustration {
      position: absolute;
      top: -14%;
      z-index: -1;
      right: -2px;
      width: 90%;
    }
    .circle-one {
      top: 0;
      left: 0;
      z-index: -1;
      transform: translate(-45%, -45%);
    }
    .circle-two {
      bottom: 0;
      right: 0;
      z-index: -1;
      transform: translate(45%, 45%);
    }
    .register-forget {
      margin: 1rem 0;
      display: flex;
      justify-content: space-between;
    }
    .opacity { opacity: 0.6; }
    .theme-btn-container {
      position: absolute;
      left: 0;
      bottom: 2rem;
    }
    .theme-btn {
      cursor: pointer;
      transition: all 0.3s ease-in;
    }
    .theme-btn:hover {
      width: 40px !important;
    }
    @keyframes wobble {
      0% { transform: scale(1.025); }
      25% { transform: scale(1); }
      75% { transform: scale(1.025); }
      100% { transform: scale(1); }
    }
    .form-text {
      font-size: 13px;
      margin-bottom: 10px;
      display: block;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .text-danger { color: #ff6b6b; }
    .text-success { color: #4cd137; }
    .form-text.show { opacity: 1; }
  </style>
</head>
<body>
  <section class="container">
    <div class="login-container">
      <div class="circle circle-one"></div>
      <div class="form-container">
        <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png" alt="illustration" class="illustration" />
        <h1 class="opacity">LOGIN</h1>
        <form id="login_form" action="<?= site_url('user/user_login'); ?>" novalidate>
          <input name="user_email" type="email" placeholder="EMAIL" />
          <small id="email_error" class="form-text text-danger"></small>

          <input name="password" type="password" placeholder="PASSWORD" />
          <small id="password_error" class="form-text text-danger"></small>

          <small id="response_msg" class="form-text text-danger" style="margin-top: 10px; display: none;"></small>

          <button class="opacity" type="submit">Login</button>
        </form>

        <div class="register-forget opacity">
          <a href="<?= site_url('signup') ?>">REGISTER</a>
          <a href="#">FORGOT PASSWORD</a>
        </div>
      </div>
      <div class="circle circle-two"></div>
    </div>
    <div class="theme-btn-container"></div>
  </section>

  <script>
    const themes = [
      { background: "#1A1A2E", color: "#FFFFFF", primaryColor: "#0F3460" },
      { background: "#461220", color: "#FFFFFF", primaryColor: "#E94560" },
      { background: "#192A51", color: "#FFFFFF", primaryColor: "#967AA1" },
      { background: "#F7B267", color: "#000000", primaryColor: "#F4845F" },
      { background: "#F25F5C", color: "#000000", primaryColor: "#642B36" },
      { background: "#231F20", color: "#FFF", primaryColor: "#BB4430" }
    ];

    const setTheme = (theme) => {
      const root = document.querySelector(":root");
      root.style.setProperty("--background", theme.background);
      root.style.setProperty("--color", theme.color);
      root.style.setProperty("--primary-color", theme.primaryColor);
    };

    const displayThemeButtons = () => {
      const btnContainer = document.querySelector(".theme-btn-container");
      themes.forEach((theme) => {
        const div = document.createElement("div");
        div.className = "theme-btn";
        div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
        btnContainer.appendChild(div);
        div.addEventListener("click", () => setTheme(theme));
      });
    };

    displayThemeButtons();
  </script>

  <script>
    $(document).ready(function () {
      $('#login_form').submit(function (e) {
        e.preventDefault();

        let email = $('input[name="user_email"]').val().trim();
        let password = $('input[name="password"]').val().trim();
        let valid = true;

        $('#email_error, #password_error, #response_msg')
          .removeClass('show text-success text-danger')
          .hide()
          .text('');

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === '') {
          $('#email_error').text('Email is required').addClass('show').fadeIn();
          valid = false;
        } else if (!emailRegex.test(email)) {
          $('#email_error').text('Invalid email format').addClass('show').fadeIn();
          valid = false;
        }

        if (password === '') {
          $('#password_error').text('Password is required').addClass('show').fadeIn();
          valid = false;
        } else if (password.length < 6) {
          $('#password_error').text('Password must be at least 6 characters').addClass('show').fadeIn();
          valid = false;
        }

        if (valid) {
          const url = $(this).attr('action');
          const fData = new FormData(this);

          $.ajax({
            type: "POST",
            url: url,
            data: fData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (response) {
              try {
                const res = typeof response === 'object' ? response : JSON.parse(response);
                $('#response_msg').text(res.message).fadeIn();

                if (res.status === true) {
                  $('#response_msg')
                    .removeClass('text-danger')
                    .addClass('text-success show')
                    .fadeIn();

                  $('#login_form')[0].reset();

                  setTimeout(() => {
                    window.location.href = "<?= site_url('homepage'); ?>";
                  }, 3000);
                } else {
                  $('#response_msg')
                    .removeClass('text-success')
                    .addClass('text-danger show')
                    .fadeIn();
                }
              } catch (err) {
                $('#response_msg')
                  .text('Unexpected error. Please try again.')
                  .addClass('text-danger show')
                  .fadeIn();
              }
            },
            error: function () {
              $('#response_msg')
                .text('Server error. Please try again later.')
                .addClass('text-danger show')
                .fadeIn();
            }
          });
        }
      });
    });
  </script>
</body>
</html>
