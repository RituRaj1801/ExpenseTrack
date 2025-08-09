<!-- head.php -->

<meta charset="UTF-8" />
<!-- Ensure proper rendering and touch zooming on mobile devices -->
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!-- Title of the web page -->
<title>User Home - Expense Track</title>

<!-- Bootstrap Icons CDN for using icons like <i class="bi bi-box-arrow-right"></i> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

<!-- Google Fonts: Inter font with weights 400 (regular) and 600 (semi-bold) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

<!-- Bootstrap 4.6.2 CSS for responsive layout and UI components -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />

<!-- jQuery 3.7.1 library (required by both Bootstrap 4 and DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<!-- Chart.js library for creating responsive charts (like pie, bar, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Bootstrap 4.6.2 JavaScript for interactive components like modals, dropdowns, etc. -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>



<!-- Required for Bootstrap 4 dropdowns, tooltips, and popovers -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f8f9fa;
    }

    .top-nav {
        position: sticky;
        top: 0px;
        z-index: 20;
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

    footer {
        background-color: #2c3e50;
        color: #ccc;
        text-align: center;
        padding: 20px 0;
        margin-top: 60px;
    }
</style>