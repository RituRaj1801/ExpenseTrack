<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Home - XYZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
        }

        .hero {
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .feature-box {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease-in-out;
        }

        .feature-box:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #0f3460;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">XYZ User Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#feedback">Feedback</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to Your Dashboard</h1>
            <p>Manage your profile, explore features, and stay updated with the latest news.</p>
            <a href="#features" class="btn btn-light btn-lg">Explore Features</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">What You Can Do</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <h4>🧾 View Profile</h4>
                        <p>See and edit your personal information securely.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <h4>📩 Messages</h4>
                        <p>Check messages and notifications sent to your account.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <h4>🛠️ Settings</h4>
                        <p>Manage your password, privacy, and account settings easily.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Form Section -->
    <section id="feedback" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">We Value Your Feedback</h2>
            <form id="feedbackForm" class="mx-auto" style="max-width: 600px;">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" required />
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Your Feedback</label>
                    <textarea class="form-control" id="message" rows="4" required></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
                <p id="feedbackStatus" class="text-center mt-3 fw-semibold"></p>
            </form>
        </div>
    </section>

    <!-- logout pop up -->
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to logout?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-danger" id="confirmLogout">Yes, Logout</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- logout pop up -->

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© 2025 XYZ.com | All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS Links -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Helper: Delete All Cookies
        function deleteAllCookies() {
            document.cookie.split(";").forEach(cookie => {
                const name = cookie.split("=")[0].trim();
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';
            });
        }

        // On Confirm Logout
        $('#confirmLogout').click(function() {
            deleteAllCookies();
            localStorage.clear();
            sessionStorage.clear(); // optional
            location.reload(); // reload the page after logout
        });
        // Feedback Form JS
        $('#feedbackForm').submit(function(e) {
            e.preventDefault();
            const name = $('#name').val().trim();
            const email = $('#email').val().trim();
            const message = $('#message').val().trim();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/;

            if (!name || !email || !message) {
                $('#feedbackStatus').text("Please fill all fields.").addClass("text-danger").removeClass("text-success");
                return;
            }

            if (!emailRegex.test(email)) {
                $('#feedbackStatus').text("Invalid email address.").addClass("text-danger").removeClass("text-success");
                return;
            }

            $('#feedbackStatus').text("Thanks for your feedback!").addClass("text-success").removeClass("text-danger");
            $('#feedbackForm')[0].reset();
        });
    </script>
</body>

</html>