<!-- index.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <?php $this->load->view('include/head'); ?>
  <style>
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
  </style>
</head>

<body>
  <?php $this->load->view('include/header'); ?>

  <!-- Hero Section -->
  <section class="py-5 text-center bg-light">
    <div class="container">
      <h1 class="display-5 fw-bold">Welcome, <?php echo $user_data['user_name'] ?? "User!"; ?></h1>
      <p class="lead">Your personalized dashboard to manage and track all your expenses easily.</p>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features">
    <div class="container">
      <h2 class="text-center mb-5">Smart Tools to Manage Your Expenses</h2>
      <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
          <a href="<?= site_url('expense/add_expense'); ?>">
            <div class="feature-card">
              <div class="icon-wrapper"><i class="bi bi-plus-circle-fill"></i></div>
              <h4>Add Expense</h4>
              <p>Quickly log your daily, weekly, or monthly expenses.</p>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-4">
          <a href="<?= site_url('expense/show_expense'); ?>">
            <div class="feature-card">
              <div class="icon-wrapper"><i class="bi bi-bar-chart-fill"></i></div>
              <h4>View Expenses</h4>
              <p>Get a detailed overview of where your money is going.</p>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="icon-wrapper"><i class="bi bi-calendar3"></i></div>
            <h4>Monthly Summary</h4>
            <p><strong>Total Expenses:</strong> ₹<span id="monthlyTotal"><?php echo $total_spend['amount'] ?? 0 ?></span><br>
              <!-- <small>This value will be loaded from backend.</small> -->
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php $this->load->view('include/footer'); ?>
  <?php $this->load->view('include/foot'); ?>

</body>

</html>