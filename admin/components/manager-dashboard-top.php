<?php
include_once '../../components/header-links.php';
?>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container-fluid">
            <a class="navbar-brand fs-4" href="#">Admin Dashboard</a>
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
                            <a class="nav-link fs-5 rounded-2" href="../sales-report/">
                                <i class="bi bi-bar-chart pe-2"></i> Sales Report
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../soon/">
                                <i class="bi bi-clock pe-2"></i> Soon
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>