<?php
include_once '../components/header-links.php';
require '../includes/firebase.php';

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
                        <a class="nav-link bg-danger fs-5 rounded-2 px-3 text-white" href="../includes/logout.inc.php">
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
                            <a class="nav-link active fs-5 rounded-2" aria-current="page" href="./">
                                <i class="bi bi-speedometer2 pe-2 "></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./dispatch/">
                                <i class="bi bi-truck pe-2"></i> Dispatch
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./user/">
                                <i class="bi bi-clock pe-2"></i> Manage Users
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./outlet/">
                                <i class="bi bi-shop pe-2"></i> Manage Outlets
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./stock/">
                                <i class="bi bi-box-seam pe-2"></i> Stock Management
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./sales-report/">
                                <i class="bi bi-bar-chart pe-2"></i> Sales Report
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2" href="./soon/">
                                <i class="bi bi-clock pe-2"></i> Soon
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                </div>

                <!-- Metrics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Available Stock</h5>
                                <h2 class="card-text"><?= number_format($currentStock) ?></h2>
                                <p class="mb-0">Cylinders in stock</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">Pending Requests</h5>
                                <h2 class="card-text"><?= $pendingRequests ?></h2>
                                <p class="mb-0">Awaiting approval</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Branch Requests</h5>
                                <h2 class="card-text"><?= number_format($branchRequests) ?></h2>
                                <p class="mb-0">Total requested units</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
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
                                <div style="height: 300px;">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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