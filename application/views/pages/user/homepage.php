<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Home - Expense Track</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #2c3e50; /* Darker, sophisticated blue */
            --secondary-blue: #34495e; /* Slightly lighter for contrast */
            --accent-green: #2ecc71; /* A subtle pop of color for success */
            --light-gray: #ecf0f1;
            --medium-gray: #bdc3c7;
            --dark-text: #2f3640;
            --light-text: #f0f0f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef); /* Softer, modern background */
            color: var(--dark-text);
            line-height: 1.6;
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-blue);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--light-text) !important;
            letter-spacing: -0.8px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-link:hover {
            background-color: var(--secondary-blue);
            color: var(--light-text) !important;
        }

        .nav-link.active {
            background-color: var(--secondary-blue);
            color: var(--light-text) !important;
            font-weight: 600;
        }

        .nav-link.text-danger {
            color: #ff6b6b !important; /* Slightly brighter red for logout */
            font-weight: 600;
        }
        .nav-link.text-danger:hover {
             background-color: rgba(220, 53, 69, 0.2) !important; /* Light red background on hover */
             color: #dc3545 !important;
        }


        /* Hero Section */
        .hero {
            background: linear-gradient(45deg, #34495e, #2c3e50); /* Deep blue gradient */
            color: var(--light-text);
            padding: 150px 0; /* More generous padding */
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"%3E%3C/path%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); /* Subtle geometric pattern */
            opacity: 0.2;
            z-index: 0;
        }

        .hero .container {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 4.2rem; /* Larger, impactful heading */
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: -1.5px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
            opacity: 0.9;
        }

        .hero .btn-light {
            background-color: var(--light-gray);
            color: var(--primary-blue);
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero .btn-light:hover {
            background-color: #dbe4e7;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        /* Features Section */
        #features {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        #features h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 60px;
            position: relative;
        }

        #features h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--accent-green);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .feature-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 45px 30px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08); /* More pronounced shadow */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smooth cubic-bezier transition */
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height: 100%; /* Ensures equal height for all cards */
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-12px) scale(1.02); /* Lift and slightly enlarge */
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15); /* Stronger shadow on hover */
        }

        .feature-card .icon-wrapper {
            background-color: var(--primary-blue);
            border-radius: 50%;
            width: 90px;
            height: 90px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease;
        }

        .feature-card:hover .icon-wrapper {
            background-color: var(--accent-green); /* Change icon background on hover */
        }

        .feature-card .bi {
            font-size: 3.5rem; /* Larger, more prominent icons */
            color: var(--light-text);
        }

        .feature-card h4 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-blue);
            font-size: 1.6rem;
        }

        .feature-card p {
            color: #555;
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Feedback Form Section */
        #feedback {
            padding: 80px 0;
            background: linear-gradient(135deg, #e9ecef, #f8f9fa); /* Reverse gradient for visual break */
        }

        #feedback h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        #feedback .lead {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 50px;
        }

        #feedbackForm {
            background: #ffffff;
            padding: 50px; /* More generous padding */
            border-radius: 20px; /* More rounded corners */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1); /* Stronger shadow */
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        #feedbackForm .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 8px;
        }

        #feedbackForm .form-control {
            border-radius: 10px; /* Rounded input fields */
            padding: 12px 18px;
            border: 1px solid var(--medium-gray);
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        #feedbackForm .form-control:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 204, 113, 0.25); /* Green focus ring */
            outline: none;
        }

        #feedbackForm .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            border-radius: 50px;
            padding: 15px 35px;
            font-weight: 600;
            font-size: 1.15rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #feedbackForm .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        #feedbackStatus {
            font-size: 1.1rem;
            margin-top: 20px;
            font-weight: 600;
        }

        /* Logout Modal */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: #dc3545; /* Bootstrap danger red */
            color: white;
            border-bottom: none;
            padding: 1.5rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }

        .modal-title {
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
            font-size: 1.1rem;
            color: var(--dark-text);
        }

        .modal-body .btn {
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .modal-body .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .modal-body .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }


        /* Footer */
        footer {
            background-color: var(--secondary-blue);
            color: rgba(255, 255, 255, 0.8);
            padding: 40px 0;
            text-align: center;
            font-size: 0.95rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        footer p {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">ExpenseTrack</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#feedback">Feedback</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="nav-link btn btn-outline-light text-danger px-4 py-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#logoutModal">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Your Financial Journey, Simplified.</h1>
            <p>ExpenseTrack empowers you to effortlessly manage your spending, gain valuable insights, and achieve your financial goals with smart, intuitive tools.</p>
            <a href="#features" class="btn btn-light btn-lg">Explore Key Features <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
    </section>

    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Unlock Powerful Capabilities</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h4>Comprehensive Profile Management</h4>
                        <p>Easily view and update your personal details, ensuring your information is always current and secure within the platform.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <h4>Instant Notifications & Messages</h4>
                        <p>Stay on top of your finances with real-time alerts and direct messages for important updates and insights.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h4>Flexible Account Settings</h4>
                        <p>Take full control of your account. Customize privacy, change passwords, and manage preferences effortlessly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="feedback" class="py-5">
        <div class="container">
            <h2 class="text-center">Your Voice Matters</h2>
            <p class="text-center lead">We're constantly striving to improve. Share your feedback and help us build a better experience for you.</p>
            <form id="feedbackForm" class="mx-auto" style="max-width: 700px;">
                <div class="mb-4">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your full name" required />
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" required />
                </div>
                <div class="mb-4">
                    <label for="message" class="form-label">Your Feedback</label>
                    <textarea class="form-control" id="message" rows="6"
                        placeholder="Tell us what you think – suggestions, issues, or compliments!" required></textarea>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Feedback</button>
                </div>
                <p id="feedbackStatus" class="text-center mt-4 fw-semibold"></p>
            </form>
        </div>
    </section>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="mb-4">Are you sure you want to end your session?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-danger flex-grow-1" id="confirmLogout">Yes, Logout</button>
                        <button class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">© 2025 ExpenseTrack. All rights reserved. Designed with <i class="bi bi-heart-fill text-danger"></i> for your financial well-being.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteAllCookies() {
            document.cookie.split(";").forEach(cookie => {
                const name = cookie.split("=")[0].trim();
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';
            });
        }

        $('#confirmLogout').click(function () {
            deleteAllCookies();
            localStorage.clear();
            sessionStorage.clear();
            // Redirect to a login page or home page after successful logout
            window.location.href = "/login.html"; // Adjust this URL as needed
        });

        $('#feedbackForm').submit(function (e) {
            e.preventDefault();
            const name = $('#name').val().trim();
            const email = $('#email').val().trim();
            const message = $('#message').val().trim();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;
            const feedbackStatus = $('#feedbackStatus');

            feedbackStatus.removeClass("text-danger text-success");

            if (!name || !email || !message) {
                feedbackStatus.text("Please fill all fields.").addClass("text-danger");
                return;
            }

            if (!emailRegex.test(email)) {
                feedbackStatus.text("Invalid email address.").addClass("text-danger");
                return;
            }

            // Simulate form submission success
            feedbackStatus.text("Thank you for your feedback! We appreciate it.").addClass("text-success");
            $('#feedbackForm')[0].reset();

            // Clear the status message after a few seconds
            setTimeout(() => {
                feedbackStatus.text("").removeClass("text-success");
            }, 6000);
        });
    </script>
</body>

</html>