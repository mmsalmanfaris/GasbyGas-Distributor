<?php
include_once '../../components/header-links.php';


// session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== false) {
//     header('Location: ../../login.php'); // Redirect to login if not manager
//     exit;
// }

?>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <div class="container-fluid">
            <!-- <p class="navbar-brand fs-3 pt-3">Outlet Dashboard, <?php echo $_SESSION['name'] ?></p> -->
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
                            <a class="nav-link fs-5 rounded-2" href="../scan/">
                                <i class="bi bi-upc-scan pe-2"></i> Scan Token
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../cylinder/">
                                <i class="bi bi-box-seam pe-2"></i> Cylinder Request
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../reallocation/">
                                <i class="bi bi-arrow-repeat pe-2"></i> Reallocation
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../consumer/">
                                <i class="bi bi-check-circle pe-2"></i> Consumer
                            </a>
                                
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../not-issued/">
                                <i class="bi bi-x-circle pe-2"></i> Not Issued
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="../report/">
                                <i class="bi bi-file-earmark-text pe-2"></i> Report
                            </a>
                        </li>
                    </ul>
                </div>
                </li></a></li>
            </nav>