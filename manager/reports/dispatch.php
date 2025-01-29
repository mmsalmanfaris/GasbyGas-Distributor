<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dispatch Schedule Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

  $dailyDispatches = [];
  $dispatchStatusCounts = [];
  if ($user_outlet_id) {
    $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();
    if ($dispatchSchedules) {
      // Calculate the start and end dates of the selected month
      $startDate = date('Y-m-01', strtotime($selectedMonth));
      $endDate = date('Y-m-t', strtotime($selectedMonth));
      foreach ($dispatchSchedules as $schedule) {
        if ($schedule['outlet_id'] == $user_outlet_id && $schedule['request_date']) {
          $scheduleDate = date('Y-m-d', strtotime($schedule['request_date']));
          if ($scheduleDate >= $startDate && $scheduleDate <= $endDate) {
            if (!isset($dailyDispatches[$scheduleDate])) {
              $dailyDispatches[$scheduleDate] = 0;
            }
            $dailyDispatches[$scheduleDate] += intval($schedule['quantity']);
            if (!isset($dispatchStatusCounts[$schedule['status']])) {
              $dispatchStatusCounts[$schedule['status']] = 0;
            }
            $dispatchStatusCounts[$schedule['status']]++;
          }
        }
      }
    }
  }
  $labels = array_keys($dailyDispatches);
  $dailyDispatchCounts = array_values($dailyDispatches);
  $statusLabels = array_keys($dispatchStatusCounts);
  $statusData = array_values($dispatchStatusCounts);
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
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>"
          onchange="window.location.href = '?month=' + this.value;">
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary">Print Report</button>
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
          label: 'Total Quantity Requested Per Day',
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
            text: 'Total Quantity Requested Per Day',
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