<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Status Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $pendingCount = 0;
  $receivedCount = 0;
  $cancelledCount = 0;
  $totalPendingAmount = 0;
  $totalReceivedAmount = 0;
  $totalCancelledAmount = 0;
  if ($user_outlet_id) {
    $crequests = $database->getReference('crequests')->getValue();
    if ($crequests) {
      foreach ($crequests as $request) {
        if ($request['outlet_id'] == $user_outlet_id) {
          if ($request['payment_status'] === 'pending') {
            $pendingCount++;
            $totalPendingAmount += intval($request['quantity']) * 4000;
          } elseif ($request['payment_status'] === 'received') {
            $receivedCount++;
            $totalReceivedAmount += intval($request['quantity']) * 4000;
          } elseif ($request['payment_status'] === 'cancelled') {
            $cancelledCount++;
            $totalCancelledAmount += intval($request['quantity']) * 4000;
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

    <div class="card">
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
        <div class="d-flex justify-content-end">
          <button id="printBtn" class="btn btn-primary">Print Report</button>
        </div>
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