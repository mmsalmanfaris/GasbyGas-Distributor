<?php
session_start();

include_once '../components/header-links.php';
require '../includes/firebase.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== false) {
    header('Location: ../'); // Redirect to login if not manager
    exit;
}

// User session handling
$user_id = $_SESSION['user_id'] ?? null;
$userRecord = [];
$user_outlet_id = null;

if ($user_id) {
    try {
        $userRecord = $database->getReference("users/{$user_id}")->getValue();
        $user_outlet_id = (string) ($userRecord['outlet_id'] ?? null);
    } catch (Exception $e) {
        die("Firebase error: " . $e->getMessage());
    }
}

// Data initialization
$crequests = [];
$consumers = [];
$outlets = [];

if ($user_outlet_id) {
    try {
        $crequests = $database->getReference('crequests')->getValue() ?? [];
        $consumers = $database->getReference('consumers')->getValue() ?? [];
        $outlets = $database->getReference('outlets')->getValue() ?? [];
    } catch (Exception $e) {
        die("Firebase error: " . $e->getMessage());
    }
}

// Metrics initialization
$metrics = [
    'handedOver' => 0,
    'pendingRequests' => 0,
    'notIssued' => 0,
    'homeA' => 0,
    'industryA' => 0,
    'homeB' => 0,
    'industryB' => 0,
    'totalSales' => 0
];

$monthlyIssued = [];
$panelARequests = [];
$panelBRequests = [];

if ($user_outlet_id) {
    foreach ($crequests as $requestId => $request) {
        if ((string) ($request['outlet_id'] ?? '') === $user_outlet_id) {
            // Handle timestamps
            $createdAt = $request['created_at'] ?? null;
            $timestamp = is_numeric($createdAt) ? $createdAt / 1000 : strtotime($createdAt);

            // Metrics calculations
            if (
                ($request['empty_cylinder'] ?? '') === 'received' &&
                ($request['payment_status'] ?? '') === 'received'
            ) {
                $quantity = (int) ($request['quantity'] ?? 0);
                $metrics['handedOver'] += $quantity;
                $metrics['totalSales'] += $quantity * 1000;
            }

            if (
                ($request['empty_cylinder'] ?? '') === 'pending' ||
                ($request['payment_status'] ?? '') === 'pending'
            ) {
                $metrics['pendingRequests'] += (int) ($request['quantity'] ?? 0);
            }

            if (
                ($request['delivery_status'] ?? '') === 'pending' &&
                (($request['empty_cylinder'] ?? '') === 'pending' ||
                    ($request['payment_status'] ?? '') === 'pending')
            ) {
                $metrics['notIssued']++;
            }

            // Monthly data
            if ($timestamp) {
                $monthYear = date('Y-m', $timestamp);
                $monthlyIssued[$monthYear] = ($monthlyIssued[$monthYear] ?? 0) + (int) ($request['quantity'] ?? 0);
            }

            // Panel categorization
            if (isset($request['consumer_id'], $consumers[$request['consumer_id']])) {
                $category = $consumers[$request['consumer_id']]['category'] ?? 'home';
                $quantity = (int) ($request['quantity'] ?? 1);

                if (($request['panel'] ?? '') === 'A') {
                    $panelARequests[] = $request;
                    $category === 'home' ? $metrics['homeA'] += $quantity : $metrics['industryA'] += $quantity;
                } elseif (($request['panel'] ?? '') === 'B') {
                    $panelBRequests[] = $request;
                    $category === 'home' ? $metrics['homeB'] += $quantity : $metrics['industryB'] += $quantity;
                }
            }
        }
    }
}

// Schedule dates calculation
$scheduleDates = [
    'A' => date('d-m-Y', strtotime(date('Y-m-14'))),
    'B' => date('d-m-Y', strtotime(date('Y-m-28')))
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background: #f8f9fa;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            height: 300px;
        }
    </style>
</head>

<body class=" m-0 p-0">
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
                <div class="container-fluid">
                    <p class="navbar-brand fs-3 pt-3">Outlet Dashboard, <?php echo $_SESSION['name'] ?? 'Guest' ?></p>
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
                                    href="../includes/logout.inc.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar vh-100" style="background-color: black;">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-3">
                            <a class="nav-link active fs-5 rounded-2 d-flex align-items-center  text-white"
                                aria-current="page" href="../">
                                <i class="bi bi-speedometer2 pe-2 "></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex align-items-center  text-white" href="./scan/">
                                <i class="bi bi-upc-scan pe-2"></i> Scan Token
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex align-items-center  text-white" href="./cylinder/">
                                <i class="bi bi-box-seam pe-2"></i> Cylinder Request
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex align-items-center  text-white"
                                href="./reallocation/">
                                <i class="bi bi-arrow-repeat pe-2"></i> Reallocation
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex align-items-center  text-white"
                                href="./consumers/">
                                <i class="bi bi-check-circle pe-2"></i> Consumer
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex align-items-center  text-white"
                                href="./not-issued/">
                                <i class="bi bi-x-circle pe-2"></i> Not Issued
                            </a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link fs-5 rounded-2 d-flex justify-content-between align-items-center text-white"
                                href="#" data-bs-toggle="collapse" data-bs-target="#reportsSubMenu"
                                aria-expanded="false">
                                <span><i class="bi bi-file-earmark-text pe-2"></i> Reports</span>
                                <span class="rotate-icon"><i class="bi bi-caret-down-fill reports-icon"></i></span>
                            </a>

                            <ul class="nav flex-column ms-3 collapse" id="reportsSubMenu">
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
                                <li class="nav-item mb-1">
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pb-5">

                <!-- Metrics Cards -->
                <div class="row row-cols-1 row-cols-md-4 g-4 mb-4 mt-3">
                    <div class="col">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5 class="card-title">Handed Over</h5>
                                <h2 class="card-text"><?= $metrics['handedOver'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5 class="card-title">Pending Requests</h5>
                                <h2 class="card-text"><?= $metrics['pendingRequests'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5 class="card-title">Not Issued</h5>
                                <h2 class="card-text"><?= $metrics['notIssued'] ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Sales</h5>
                                <h2 class="card-text">Rs.<?= number_format($metrics['totalSales']) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card h-100">
                            <div class="card-body chart-container">
                                <h5 class="card-title">Monthly Distribution</h5>
                                <canvas id="monthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body chart-container">
                                <h5 class="card-title">Request Types</h5>
                                <canvas id="typeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Schedules -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                Panel A Schedule (<?= $scheduleDates['A'] ?>)
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5>Total Requests: <?= count($panelARequests) ?></h5>
                                <div class="row mt-auto g-2">
                                    <div class="col-6">
                                        <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h6>Home Requests</h6>
                                                <span class="fs-4"><?= $metrics['homeA'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h6>Industry Requests</h6>
                                                <span class="fs-4"><?= $metrics['industryA'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                Panel B Schedule (<?= $scheduleDates['B'] ?>)
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5>Total Requests: <?= count($panelBRequests) ?></h5>
                                <div class="row mt-auto g-2">
                                    <div class="col-6">
                                        <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h6>Home Requests</h6>
                                                <span class="fs-4"><?= $metrics['homeB'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h6>Industry Requests</h6>
                                                <span class="fs-4"><?= $metrics['industryB'] ?></span>
                                            </div>
                                        </div>
                                    </div>
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
        // Monthly Distribution Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($monthlyIssued)) ?>,
                datasets: [{
                    label: 'Cylinders Distributed',
                    data: <?= json_encode(array_values($monthlyIssued)) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Units'
                        }
                    }
                }
            }
        });

        // Request Type Chart
        const typeCtx = document.getElementById('typeChart');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Home', 'Industry'],
                datasets: [{
                    data: [
                        <?= $metrics['homeA'] + $metrics['homeB'] ?>,
                        <?= $metrics['industryA'] + $metrics['industryB'] ?>
                    ],
                    backgroundColor: ['#4bc0c0', '#ff6384']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

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

    <?php include_once '../components/footer-links.php'; ?>
</body>

</html>