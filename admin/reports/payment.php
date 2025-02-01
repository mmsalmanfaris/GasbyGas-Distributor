<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Status Report - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
  $selectedOutlet = isset($_GET['outlet']) ? $_GET['outlet'] : 'all';
  $outlets = $database->getReference('outlets')->getValue();

  $pendingCount = 0;
  $receivedCount = 0;
  $cancelledCount = 0;
  $totalPendingAmount = 0;
  $totalReceivedAmount = 0;
  $totalCancelledAmount = 0;

  $crequests = $database->getReference('crequests')->getValue();
  if ($crequests) {
    $startDate = date('Y-m-01', strtotime($selectedMonth));
    $endDate = date('Y-m-t', strtotime($selectedMonth));
    foreach ($crequests as $request) {
      if (isset($request['created_at'])) {
        $requestDate = date('Y-m-d', strtotime($request['created_at']));
        if ($requestDate >= $startDate && $requestDate <= $endDate) {
          if ($selectedOutlet === 'all' || $request['outlet_id'] === $selectedOutlet) {
            if ($request['payment_status'] === 'pending') {
              $pendingCount++;
              if (isset($request['total_price'])) $totalPendingAmount += intval($request['total_price']);
            } elseif ($request['payment_status'] === 'received') {
              $receivedCount++;
              if (isset($request['total_price'])) $totalReceivedAmount += intval($request['total_price']);
            } elseif ($request['payment_status'] === 'cancelled') {
              $cancelledCount++;
              if (isset($request['total_price'])) $totalCancelledAmount += intval($request['total_price']);
            }
          }
        }
      }
    }
  }


  ?>
  <style>
    .chart-container {
      max-width: 600px;
      /* Increased max-width for the chart */
      margin: 20px auto;
    }
  </style>
</head>

<body>
  <!-- Main Content -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Payment Status Report</h1>
    </div>
    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>"
          onchange="window.location.href = '?month=' + this.value + '&outlet=' + document.getElementById('outletSelect').value ;">
      </div>
      <div class="col-3">
        <label for="outletSelect" class="form-label fw-bold">Select Outlet:</label>
        <select class="form-select" id="outletSelect"
          onchange="window.location.href = '?month=' + document.getElementById('monthSelect').value + '&outlet=' + this.value;">
          <option value="all" <?php echo ($selectedOutlet === 'all' ? 'selected' : ''); ?>>All Outlets</option>
          <?php
          if (is_array($outlets)) {
            foreach ($outlets as $outlet) {
              $selected = ($outlet['outlet_id'] === $selectedOutlet) ? 'selected' : '';
              echo '<option value="' . htmlspecialchars($outlet['outlet_id']) . '" ' . $selected . '>' . htmlspecialchars($outlet['name']) . '</option>';
            }
          }
          ?>
        </select>
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary me-2" onclick="window.print()">Print Report</button>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Payment Summary</h5>
        <p class="card-text">
          <strong>Pending Payments:</strong> <?php echo $pendingCount; ?><br>
          <strong>Received Payments:</strong> <?php echo $receivedCount; ?><br>
          <strong>Cancelled Payments:</strong> <?php echo $cancelledCount; ?>
        </p>
        <p class="card-text">
          <strong>Total Pending Amount:</strong> <?php echo number_format($totalPendingAmount, 2); ?> LKR<br>
          <strong>Total Received Amount:</strong> <?php echo number_format($totalReceivedAmount, 2); ?> LKR<br>
          <strong>Total Cancelled Amount:</strong> <?php echo number_format($totalCancelledAmount, 2); ?> LKR
        </p>
      </div>
    </div>

    <div class="chart-container mt-5">
      <canvas id="paymentChart" width="600" height="300"></canvas>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const pendingCount = <?php echo json_encode($pendingCount); ?>;
    const receivedCount = <?php echo json_encode($receivedCount); ?>;
    const cancelledCount = <?php echo json_encode($cancelledCount); ?>;
    const totalPendingAmount = <?php echo json_encode($totalPendingAmount); ?>;
    const totalReceivedAmount = <?php echo json_encode($totalReceivedAmount); ?>;
    const totalCancelledAmount = <?php echo json_encode($totalCancelledAmount); ?>;

    const ctx = document.getElementById('paymentChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Pending', 'Received', 'Cancelled'],
        datasets: [{
          data: [pendingCount, receivedCount, cancelledCount],
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label;
                const value = context.dataset.data[context.dataIndex];
                let amount = 0;
                if (label === "Pending") {
                  amount = totalPendingAmount;
                } else if (label === "Received") {
                  amount = totalReceivedAmount;
                } else if (label === "Cancelled") {
                  amount = totalCancelledAmount;
                }
                return `${label}: ${value} Payments, Amount: ${amount} LKR`;
              }
            }
          }
        }
      }
    });
  </script>
  <?php
  include_once '../components/manager-dashboard-down.php';
  message_success();
  ?>
</body>

</html>