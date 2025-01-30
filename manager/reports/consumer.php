<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consumer Request Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

  $dailyRequests = [];
  $dailyQuantities = [];
  $panelCounts = [];
  $deliveryStatusCounts = [];

  if ($user_outlet_id) {
    $crequests = $database->getReference('crequests')->getValue();
    if ($crequests) {
      // Calculate the start and end dates of the selected month
      $startDate = date('Y-m-01', strtotime($selectedMonth));
      $endDate = date('Y-m-t', strtotime($selectedMonth));
      foreach ($crequests as $request) {
        if ($request['outlet_id'] == $user_outlet_id && $request['created_at']) {
          $requestDate = date('Y-m-d', strtotime($request['created_at']));
          if ($requestDate >= $startDate && $requestDate <= $endDate) {
            if (!isset($dailyRequests[$requestDate])) {
              $dailyRequests[$requestDate] = 0;
              $dailyQuantities[$requestDate] = 0;
            }
            $dailyRequests[$requestDate]++;
            $dailyQuantities[$requestDate] += intval($request['quantity']);
            if (!isset($panelCounts[$request['panel']])) {
              $panelCounts[$request['panel']] = 0;
            }
            $panelCounts[$request['panel']]++;

            if (!isset($deliveryStatusCounts[$request['delivery_status']])) {
              $deliveryStatusCounts[$request['delivery_status']] = 0;
            }
            $deliveryStatusCounts[$request['delivery_status']]++;
          }
        }
      }
    }
  }

  $labels = array_keys($dailyRequests);
  $dailyReqCounts = array_values($dailyRequests);
  $dailyQuantityCounts = array_values($dailyQuantities);
  $panelLabels = array_keys($panelCounts);
  $panelData = array_values($panelCounts);
  $deliveryStatusLabels = array_keys($deliveryStatusCounts);
  $deliveryStatusData = array_values($deliveryStatusCounts);
  ?>
  <style>
    .chart-container {
      max-width: 600px;
      margin: 20px auto;
    }
  </style>
</head>

<body>
  <!-- Main Content -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Consumer Request Report</h1>
    </div>
    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>"
          onchange="window.location.href = '?month=' + this.value;">
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary">Print Report</button>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="requestChart" width="600" height="300"></canvas>
    </div>
    <div class="chart-container mt-5">
      <canvas id="quantityChart" width="600" height="300"></canvas>
    </div>
    <div class="mt-5 row">
      <div class="col-md-6  chart-container ">
        <canvas id="panelChart" width="400" height="200"></canvas>
      </div>
      <div class="col-md-6 chart-container">
        <canvas id="deliveryStatusChart" width="400" height="200"></canvas>
      </div>
    </div>


  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const dailyReqCounts = <?php echo json_encode($dailyReqCounts); ?>;
    const dailyQuantityCounts = <?php echo json_encode($dailyQuantityCounts); ?>;
    const panelLabels = <?php echo json_encode($panelLabels); ?>;
    const panelData = <?php echo json_encode($panelData); ?>;
    const deliveryStatusLabels = <?php echo json_encode($deliveryStatusLabels); ?>;
    const deliveryStatusData = <?php echo json_encode($deliveryStatusData); ?>;

    const ctx1 = document.getElementById('requestChart').getContext('2d');
    const myChart = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Requests Per Day',
          data: dailyReqCounts,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          hoverBackgroundColor: 'rgba(54, 162, 235, 0.9)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Consumer Requests Per Day',
            font: {
              size: 18
            }
          },
          legend: {
            display: false
          }
        }
      }
    });
    const ctx3 = document.getElementById('quantityChart').getContext('2d');
    const quantityChart = new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Quantities Per Day',
          data: dailyQuantityCounts,
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          hoverBackgroundColor: 'rgba(75, 192, 192, 0.9)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Total Quantities Per Day',
            font: {
              size: 18
            }
          },
          legend: {
            display: false
          }
        }
      }
    });
    const ctx2 = document.getElementById('panelChart').getContext('2d');
    const pieChart = new Chart(ctx2, {
      type: 'pie',
      data: {
        labels: panelLabels,
        datasets: [{
          data: panelData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Distribution of Requests by Panel',
            font: {
              size: 18
            }
          },
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
    });

    const ctx4 = document.getElementById('deliveryStatusChart').getContext('2d');
    const deliveryStatusChart = new Chart(ctx4, {
      type: 'pie',
      data: {
        labels: deliveryStatusLabels,
        datasets: [{
          data: deliveryStatusData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Distribution of Requests by Delivery Status',
            font: {
              size: 18
            }
          },
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
    });
    document.getElementById('printBtn').addEventListener('click', function() {
      window.print();
    });
  </script>
  <?php
  include_once '../components/manager-dashboard-down.php';
  message_success();
  ?>
</body>

</html>