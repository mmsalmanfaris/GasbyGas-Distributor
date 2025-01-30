<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Not Issued Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');


  $notIssuedRequests = [];
  $consumerNames = [];
  $dailyNotIssuedQuantities = [];

  $totalNotIssuedCount = 0;
  $totalNotIssuedQuantity = 0;

  if ($user_outlet_id) {
    $crequests = $database->getReference('crequests')->getValue();
    $consumers = $database->getReference('consumers')->getValue();
    if ($crequests && $consumers) {
      // Calculate the start and end dates of the selected month
      $startDate = date('Y-m-01', strtotime($selectedMonth));
      $endDate = date('Y-m-t', strtotime($selectedMonth));

      foreach ($crequests as $requestId => $request) {
        if (
          $request['outlet_id'] == $user_outlet_id &&
          $request['empty_cylinder'] === 'received' &&
          $request['payment_status'] === 'received' &&
          $request['delivery_status'] === 'pending'
        ) {
          $notIssuedRequests[] = $request;
          if (isset($consumers[$request['consumer_id']])) {
            $consumerNames[$request['consumer_id']] = htmlspecialchars($consumers[$request['consumer_id']]['name']);
          }
          if ($request['created_at']) {
            $requestDate = date('Y-m-d', strtotime($request['created_at']));
            if ($requestDate >= $startDate && $requestDate <= $endDate) {
              if (!isset($dailyNotIssuedQuantities[$requestDate])) {
                $dailyNotIssuedQuantities[$requestDate] = 0;
              }
              $dailyNotIssuedQuantities[$requestDate] += intval($request['quantity']);
              $totalNotIssuedQuantity += intval($request['quantity']);
            }
          }
          $totalNotIssuedCount++;
        }
      }
    }
  }
  $labels = array_keys($dailyNotIssuedQuantities);
  $dailyCounts = array_values($dailyNotIssuedQuantities);
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
      <h1 class="h2">Not Issued Report</h1>
    </div>
    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>" onchange="window.location.href = '?month=' + this.value;">
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary">Print Report</button>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Not Issued Summary</h5>
        <p class="card-text">
          <strong>Total Not Issued Requests:</strong> <?php echo $totalNotIssuedCount; ?><br>
          <strong>Total Not Issued Quantity:</strong> <?php echo $totalNotIssuedQuantity; ?>
        </p>
      </div>
    </div>
    <div class="chart-container mt-5">
      <canvas id="quantityChart" width="600" height="300"></canvas>
    </div>
    <div class="table-responsive p-3 border mt-5">
      <table style="width:100%" class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Consumer Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Scheduled Delivery</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($notIssuedRequests) {
            foreach ($notIssuedRequests as $request) {
              $consumerName = 'N/A';
              if (isset($consumerNames[$request['consumer_id']])) {
                $consumerName = $consumerNames[$request['consumer_id']];
              }
              echo '<tr>';
              echo '<td>' . $consumerName . '</td>';
              echo '<td>' . htmlspecialchars($request['quantity']) . '</td>';
              echo '<td>' . htmlspecialchars($request['sdelivery']) . '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="3">No matching requests found for your outlet.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const dailyCounts = <?php echo json_encode($dailyCounts); ?>;
    const ctx2 = document.getElementById('quantityChart').getContext('2d');
    const quantityChart = new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Quantity Not Issued Per Day',
          data: dailyCounts,
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
            text: 'Total Quantity Not Issued Per Day',
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