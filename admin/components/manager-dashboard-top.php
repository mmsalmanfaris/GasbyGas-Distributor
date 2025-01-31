<?php
include_once '../../components/header-links.php';


session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../../login.php'); // Redirect to login if not admin
    exit;
}


require '../../vendor/autoload.php';

use Kreait\Firebase\Factory;

// Initialize Firebase
$factory = (new Factory)
    ->withServiceAccount('../../gasbygas-97e19-firebase-adminsdk-fbsvc-21d66d3153.json')
    ->withDatabaseUri('https://gasbygas-97e19-default-rtdb.firebaseio.com/');

$database = $factory->createDatabase();

?>


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <div class="container-fluid">
            <!-- <p class="navbar-brand fs-3 pt-3">Admin Dashboard, <?php echo $_SESSION['name'] ?></p> -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <a class="nav-link bg-primary fs-5 rounded-2 px-3 text-white" href="#">
                            <i class="bi bi-person"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link bg-primary fs-5 rounded-2 px-3 text-white" href="#">
                            <i class="bi bi-gear"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-danger fs-5 rounded-2 px-3 text-white"
                            href="../../includes/logout.inc.php">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-3"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar vh-100">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a class="nav-link active fs-5 rounded-2" aria-current="page" href="../">
                                <i class="bi bi-speedometer2 pe-2 "></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../dispatch/">
                                <i class="bi bi-truck pe-2"></i> Dispatch
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../user/">
                                <i class="bi bi-clock pe-2"></i> Users Manage
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../outlet/">
                                <i class="bi bi-shop pe-2"></i> Outlet Manage
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex justify-content-between align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubMenu" aria-expanded="false">
                                <span><i class="bi bi-file-earmark-text pe-2"></i> Reports</span>
                                <span class="rotate-icon"><i class="bi bi-caret-down-fill reports-icon"></i></span>
                            </a>

                            <ul class="nav flex-column ms-3 collapse" id="reportsSubMenu">
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/yearly.php"><i class="bi bi-calendar-month pe-1"></i>Yearly Sales</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/monthly.php"><i class="bi bi-calendar-month pe-1"></i>Monthly Sales</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/stock.php"><i class="bi bi-stack pe-1"></i>Stock Level</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/payment.php"><i class="bi bi-credit-card-2-front-fill pe-1"></i>Payment Status</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/consumer.php"><i class="bi bi-person-lines-fill pe-1"></i>Consumer Request</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/dispatch.php"><i class="bi bi-calendar-event pe-1"></i>Dispatch Schedule</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/reallocation.php"><i class="bi bi-arrow-left-right pe-1"></i>Reallocation</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center" href="../reports/not-issued.php"><i class="bi bi-exclamation-triangle-fill pe-1"></i>Not-Issued</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const reportsMenuLink = document.querySelector('[data-bs-target="#reportsSubMenu"]');
                    const reportsIcon = document.querySelector('.reports-icon');
                    const reportsSubMenu = document.getElementById('reportsSubMenu');

                    reportsMenuLink.addEventListener('click', function() {
                        reportsIcon.classList.toggle('bi-caret-down-fill');
                        reportsIcon.classList.toggle('bi-caret-up-fill');
                        reportsIcon.classList.toggle('rotated');
                    });
                });
            </script>