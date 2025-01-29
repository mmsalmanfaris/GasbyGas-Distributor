<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reallocation Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
  $dailyReallocations = [];
  $dailyReallocatedQuantities = [];


  if ($user_outlet_id) {
    $cancellations = $database->getReference('cancellations')->getValue();
    if ($cancellations) {
      // Calculate the start and end dates of the selected month
      $startDate = date('Y-m-01', strtotime($selectedMonth));
      $endDate = date('Y-m-t', strtotime($selectedMonth));
      foreach ($cancellations as $cancellation) {
        if ($cancellation['outlet_id'] == $user_outlet_id && $cancellation['date']) {
          $cancellationDate = date('Y-m-d', strtotime($cancellation['date']));
          if ($cancellationDate >= $startDate && $cancellationDate <= $endDate) {
            if (!isset($dailyReallocations[$cancellationDate])) {
              $dailyReallocations[$cancellationDate] = 0;
              $dailyReallocatedQuantities[$cancellationDate] = 0;
            }
            $dailyReallocations[$cancellationDate]++;
            $dailyReallocatedQuantities[$cancellationDate] += intval($cancellation['quantity']);
          }
        }
      }
    }
  }
  $labels = array_keys($dailyReallocations);
  $reallocationCounts = array_values($dailyReallocations);
  $reallocationQuantities = array_values($dailyReallocatedQuantities);

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
      <h1 class="h2">Reallocation Report</h1>
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
      <canvas id="reallocationChart" width="600" height="300"></canvas>
    </div>
    <div class="table-responsive p-3 border mt-5">
      <table style="width:100%" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Date</th>
            <th scope="col">Total Reallocation Count</th>
            <th scope="col">Total Reallocated Quantity</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($dailyReallocations) {
            foreach ($dailyReallocations as $date => $count) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars($date) . '</td>';
              echo '<td>' . $count . '</td>';
              echo '<td>' . (isset($dailyReallocatedQuantities[$date]) ? $dailyReallocatedQuantities[$date] : 0) . '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="3">No reallocation data found for this month.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const reallocationCounts = <?php echo json_encode($reallocationCounts); ?>;
    const reallocationQuantities = <?php echo json_encode($reallocationQuantities); ?>;
    const ctx = document.getElementById('reallocationChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
            label: 'Total Reallocations Per Day',
            data: reallocationCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            hoverBackgroundColor: 'rgba(75, 192, 192, 0.9)'
          },
          {
            label: 'Total Reallocated Quantity Per Day',
            data: reallocationQuantities,
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            hoverBackgroundColor: 'rgba(255, 99, 132, 0.9)'
          }
        ]
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
            text: 'Total Reallocations Per Day and Total Reallocated Quantity Per Day',
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