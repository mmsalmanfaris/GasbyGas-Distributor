<?php
include_once '../components/header-links.php';
require '../includes/firebase.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../'); // Redirect to login if not manager
    exit;
}

// Fetch data from Firebase
$outlets = $database->getReference('outlets')->getValue() ?? [];
$dispatchSchedules = $database->getReference('dispatch_schedules')->getValue() ?? [];
$stockRef = $database->getReference('stock')->getValue();
$currentStock = isset($stockRef['available']) ? $stockRef['available'] : 0;

// Calculate metrics
$totalOutlets = count($outlets);
$pendingRequests = 0;
$branchRequests = 0;
$totalSales = 0;
$monthlyIssuedByDistrict = [];

foreach ($dispatchSchedules as $schedule) {
    if ($schedule['status'] === 'pending') {
        $pendingRequests++;
        $branchRequests += intval($schedule['quantity']);
    }

    // Calculate monthly issued by district
    if (isset($schedule['outlet_id'], $outlets[$schedule['outlet_id']])) {
        $district = $outlets[$schedule['outlet_id']]['district'];
        $month = date('Y-m', strtotime($schedule['request_date']));

        if (!isset($monthlyIssuedByDistrict[$district][$month])) {
            $monthlyIssuedByDistrict[$district][$month] = 0;
        }
        $monthlyIssuedByDistrict[$district][$month] += intval($schedule['quantity']);
    }
}

// Calculate upcoming dispatch dates
$currentMonth = date('m');
$currentYear = date('Y');
$upcomingDates = [
    'first' => date('d-m-Y', strtotime("{$currentYear}-{$currentMonth}-14")),
    'second' => date('d-m-Y', strtotime("{$currentYear}-{$currentMonth}-28"))
];
?>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <div class="container-fluid">
            <p class="navbar-brand fs-3 pt-3"><?php echo $_SESSION['name'] ?> Dashboard</p>
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
                        <a class="nav-link bg-danger fs-5 rounded-2 px-3 text-white" href="../includes/logout.inc.php">
                            <i class="bi bi-box-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid ">
        <div class="row ">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar vh-100" style="background-color: black;">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a class="nav-link active fs-5 rounded-2 text-white" aria-current="page" href="./">
                                <i class="bi bi-speedometer2 pe-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 text-white" href="./dispatch/">
                                <i class="bi bi-truck pe-2"></i> Dispatch
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 text-white" href="./user/">
                                <i class="bi bi-person pe-2"></i> Manage Users
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 text-white" href="./outlet/">
                                <i class="bi bi-shop pe-2"></i> Manage Outlets
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 text-white" href="./stock/">
                                <i class="bi bi-box-seam pe-2"></i> Stock Level
                            </a>
                        </li>
                        <li class="nav-item mb-3 ">
                            <a class="nav-link fs-5 rounded-2 d-flex justify-content-between align-items-center text-white"
                                href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubMenu"
                                aria-expanded="false">
                                <span><i class="bi bi-file-earmark-text pe-2"></i> Reports</span>
                                <span class="rotate-icon"><i class="bi bi-caret-down-fill reports-icon"></i></span>
                            </a>

                            <ul class="nav flex-column ms-3 collapse" id="reportsSubMenu">
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/yearly.php"><i class="bi bi-calendar-month pe-1"></i>Yearly
                                        Sales</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/monthly.php"><i class="bi bi-calendar-month pe-1"></i>Monthly
                                        Sales</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/stock.php"><i class="bi bi-stack pe-1"></i>Stock Level</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/payment.php"><i
                                            class="bi bi-credit-card-2-front-fill pe-1"></i>Payment Status</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/consumer.php"><i
                                            class="bi bi-person-lines-fill pe-1"></i>Consumer Request</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/dispatch.php"><i class="bi bi-calendar-event pe-1"></i>Dispatch
                                        Schedule</a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/reallocation.php"><i
                                            class="bi bi-arrow-left-right pe-1"></i>Reallocation</a>
                                </li>
                                <li class="nav-item mb-1 pb-5">
                                    <a class="nav-link fs-6 rounded-2 text-primary d-flex align-items-center text-white"
                                        href="./reports/not-issued.php"><i
                                            class="bi bi-exclamation-triangle-fill pe-1"></i>Not-Issued</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">

                <!-- Metrics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Available Stock</h5>
                                <h2 class="card-text"><?= number_format($currentStock) ?></h2>
                                <p class="mb-0">Cylinders in stock</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Pending Requests</h5>
                                <h2 class="card-text"><?= $pendingRequests ?></h2>
                                <p class="mb-0">Awaiting approval</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Outlet Requests</h5>
                                <h2 class="card-text"><?= number_format($branchRequests) ?></h2>
                                <p class="mb-0">Total requested units</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Registered Outlets</h5>
                                <h2 class="card-text"><?= $totalOutlets ?></h2>
                                <p class="mb-0">Active branches</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">District-wise Monthly Distribution</h5>
                                <div style="height: 330px;">
                                    <canvas id="districtChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Upcoming Dispatch Dates</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>First Dispatch:</strong>
                                    <span><?= $upcomingDates['first'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <strong>Second Dispatch:</strong>
                                    <span><?= $upcomingDates['second'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportsMenuLink = document.querySelector('[data-bs-target="#reportsSubMenu"]');
            const reportsIcon = document.querySelector('.reports-icon');
            const reportsSubMenu = document.getElementById('reportsSubMenu');

            reportsMenuLink.addEventListener('click', function () {
                reportsIcon.classList.toggle('bi-caret-down-fill');
                reportsIcon.classList.toggle('bi-caret-up-fill');
                reportsIcon.classList.toggle('rotated');
            });
        });
    </script>


    <script>
        // District-wise Monthly Distribution Chart
        const districtData = <?= json_encode($monthlyIssuedByDistrict) ?>;
        const districts = Object.keys(districtData);
        const months = [...new Set(Object.values(districtData).flatMap(d => Object.keys(d)))].sort();

        const datasets = districts.map((district, index) => ({
            label: district,
            data: months.map(month => districtData[district][month] || 0),
            backgroundColor: `hsl(${index * 360 / districts.length}, 70%, 50%, 0.2)`,
            borderColor: `hsl(${index * 360 / districts.length}, 70%, 50%)`,
            borderWidth: 2
        }));

        new Chart(document.getElementById('districtChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Units Distributed'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>

    <?php
    include_once '../components/footer-links.php';
    ?>

</body>

</html>