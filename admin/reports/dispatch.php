<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dispatch Schedule Report - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

  $monthlyDispatches = [];
  $dispatchStatusCounts = [];
  $labels = [];
  $allMonths = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];
  if ($dispatchSchedules = $database->getReference('dispatch_schedules')->getValue()) {
    foreach ($dispatchSchedules as $schedule) {
      if (isset($schedule['request_date'])) {
        $requestDate = new DateTime($schedule['request_date']);
        if ($requestDate->format('Y') == $selectedYear) {
          $month = $requestDate->format('F');
          if (!isset($monthlyDispatches[$month])) {
            $monthlyDispatches[$month] = 0;
          }
          $monthlyDispatches[$month] += intval($schedule['quantity']);
          if (!isset($dispatchStatusCounts[$schedule['status']])) {
            $dispatchStatusCounts[$schedule['status']] = 0;
          }
          $dispatchStatusCounts[$schedule['status']]++;
        }
      }
    }
    foreach ($allMonths as $month) {
      $labels[] = $month;
      $dailyDispatchCounts[] = isset($monthlyDispatches[$month]) ? $monthlyDispatches[$month] : 0;
    }
  }

  $statusLabels = array_keys($dispatchStatusCounts);
  $statusData = array_values($dispatchStatusCounts);
  $dailyDispatchCounts = array_values(array_merge(array_fill_keys($allMonths, 0), array_combine(array_keys($monthlyDispatches), $monthlyDispatches)));
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
      <h1 class="h2">Dispatch Schedule Report</h1>
    </div>
    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="yearSelect" class="form-label fw-bold">Select Year:</label>
        <input type="number" class="form-control" id="yearSelect" value="<?php echo $selectedYear; ?>"
          onchange="window.location.href = '?year=' + this.value;" min="2020" max="<?php echo date('Y'); ?>">
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary" onclick="window.print()">Print Report</button>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="dispatchChart" width="600" height="300"></canvas>
    </div>
    <div class="mt-5 row chart-container d-flex justify-content-center">
      <canvas id="statusChart" width="400" height="200"></canvas>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const dailyDispatchCounts = <?php echo json_encode($dailyDispatchCounts); ?>;
    const statusLabels = <?php echo json_encode($statusLabels); ?>;
    const statusData = <?php echo json_encode($statusData); ?>;


    const ctx1 = document.getElementById('dispatchChart').getContext('2d');
    const myChart = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Quantity Requested Per Month',
          data: dailyDispatchCounts,
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
            text: 'Total Quantity Requested Per Month',
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
    const ctx2 = document.getElementById('statusChart').getContext('2d');
    const pieChart = new Chart(ctx2, {
      type: 'pie',
      data: {
        labels: statusLabels,
        datasets: [{
          data: statusData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
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
            text: 'Distribution of Dispatches by Status',
            font: {
              size: 18
            }
          },
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      },
      plugins: [{
        beforeDraw: (chart) => {
          const ctx = chart.canvas.getContext('2d');
          let sum = 0;
          chart.data.datasets[0].data.forEach(value => {
            sum += value;
          });
          ctx.font = "20px sans-serif";
          ctx.fillStyle = "black";
          ctx.textAlign = "center";
          ctx.fillText(`Total: ${sum}`, chart.width / 2, chart.height / 2 + 10);
        }
      }]
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