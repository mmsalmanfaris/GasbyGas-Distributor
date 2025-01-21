<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard</title>
    <!-- Bootstrap CSS -->

    <?php
    include_once '../components/header-links.php';
    ?>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
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
                        <a class="nav-link bg-danger fs-5 rounded-2 px-3 text-white" href="#">
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
                            <a class="nav-link active fs-5 rounded-2" aria-current="page" href="#">
                                <i class="bi bi-speedometer2 pe-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="#">
                                <i class="bi bi-shop pe-2"></i> Outlet
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="#">
                                <i class="bi bi-truck pe-2"></i> Dispatch
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="#">
                                <i class="bi bi-graph-up pe-2"></i> Sales Report
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="#">
                                <i class="bi bi-building pe-2"></i> Industry
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="#">
                                <i class="bi bi-gear pe-2"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Sales</div>
                            <div class="card-body">
                                <h5 class="card-title">$12,345</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">New Orders</div>
                            <div class="card-body">
                                <h5 class="card-title">120</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Pending Requests</div>
                            <div class="card-body">
                                <h5 class="card-title">15</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php
    include_once '../components/footer-links.php';
    ?>

</body>

</html>