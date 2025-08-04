<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Home - Expense Track</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f8f9fa;
    }

    .navbar {
      background-color: #2c3e50;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      color: #fff !important;
    }

    .nav-link {
      color: #ddd !important;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #fff !important;
      background-color: #34495e;
      border-radius: 6px;
    }

    .feature-card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      transition: 0.3s;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .icon-wrapper {
      background-color: #2c3e50;
      border-radius: 50%;
      width: 70px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }

    .icon-wrapper i {
      color: #fff;
      font-size: 1.8rem;
    }

    footer {
      background-color: #2c3e50;
      color: #ccc;
      text-align: center;
      padding: 20px 0;
      margin-top: 60px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#">ExpenseTrack</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
          </li>

          <!-- Features Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="featuresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Features
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="featuresDropdown">
              <li><a class="dropdown-item" href="<?= site_url('expense/add_expense'); ?>"><i class="bi bi-plus-circle me-2"></i>Add Expense</a></li>
              <li><a class="dropdown-item" href="<?= site_url('expense/show_expense'); ?>"><i class="bi bi-bar-chart me-2"></i>Show Expense</a></li>
              <li><a class="dropdown-item" href="#monthlyTotal"><i class="bi bi-calendar3 me-2"></i>Monthly Summary</a></li>
              <li><a class="dropdown-item" href="#budgetLeft"><i class="bi bi-wallet me-2"></i>Budget Tracker</a></li>
              <li><a class="dropdown-item" href="#trendMessage"><i class="bi bi-graph-up-arrow me-2"></i>Spending Trends</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php site_url("contact") ?>">Contact Us</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-danger" href="#"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="py-5 text-center bg-light">
    <div class="container">
      <h1 class="display-5 fw-bold">Welcome, <?= ucfirst($user_data['user_name']) ?>!</h1>
      <p class="lead">Your personalized dashboard to manage and track all your expenses easily.</p>
    </div>
  </section>

  <!-- Features Section (Don't Remove) -->
  <section id="features" class="py-5">
    <div class="container">
      <h2 class="text-center mb-5">Smart Tools to Manage Your Expenses</h2>
      <div class="row g-4 justify-content-center">

        <!-- Add Expense -->
        <div class="col-md-6 col-lg-4">
          <a href="<?= site_url('expense/add_expense'); ?>">
            <div class="feature-card">
              <div class="icon-wrapper"><i class="bi bi-plus-circle-fill"></i></div>
              <h4>Add Expense</h4>
              <p>Quickly log your daily, weekly, or monthly expenses.</p>
            </div>
          </a>
        </div>

        <!-- View Expense -->
        <div class="col-md-6 col-lg-4">
          <a href="<?= site_url('expense/show_expense'); ?>">
            <div class="feature-card">
              <div class="icon-wrapper"><i class="bi bi-bar-chart-fill"></i></div>
              <h4>View Expenses</h4>
              <p>Get a detailed overview of where your money is going.</p>
            </div>
          </a>
        </div>

        <!-- Monthly Summary -->
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="icon-wrapper"><i class="bi bi-calendar3"></i></div>
            <h4>Monthly Summary</h4>
            <p><strong>Total Expenses:</strong> ₹<span id="monthlyTotal">Loading...</span><br>
              <small>This value will be loaded from backend.</small>
            </p>
          </div>
        </div>

        <!-- Budget Tracker -->
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="icon-wrapper"><i class="bi bi-wallet-fill"></i></div>
            <h4>Budget Tracker</h4>
            <p><strong>Remaining Budget:</strong> ₹<span id="budgetLeft">--</span><br>
              <small>Based on your set budget and current expenses.</small>
            </p>
          </div>
        </div>

        <!-- Trends -->
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="icon-wrapper"><i class="bi bi-graph-up-arrow"></i></div>
            <h4>Spending Trends</h4>
            <p><span id="trendMessage">Analyzing your habits...</span><br>
              <small>Insight will appear here once data is available.</small>
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; 2025 ExpenseTrack. All rights reserved.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>