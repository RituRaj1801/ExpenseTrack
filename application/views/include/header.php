<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="#">ExpenseTrack</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="featuresDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Features
          </a>
          <div class="dropdown-menu" aria-labelledby="featuresDropdown">
            <a class="dropdown-item" href="<?php echo site_url('add_expense') ?>">Add Expense</a>
            <a class="dropdown-item" href="<?php echo site_url('show_expense') ?>">Show Expense</a>
            <a class="dropdown-item" href="#">Monthly Summary</a>
            <a class="dropdown-item" href="#">Budget Tracker</a>
            <a class="dropdown-item" href="#">Spending Trends</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="#"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>