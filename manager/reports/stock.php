<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Level Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $user_outlet_id = null;
  $userRecord = $database->getReference("users/{$user_id}")->getValue();

  if ($userRecord && isset($userRecord['outlet_id'])) {
    $user_outlet_id = $userRecord['outlet_id'];
  }


  $outletData = [];
  $filledStock = 0;
  $emptyStock = 0;


  if ($user_outlet_id) {
    $outlets = $database->getReference('outlets')->getValue();
    $crequests = $database->getReference('crequests')->getValue();
    if ($outlets) {
      foreach ($outlets as $outletKey => $outlet) {
        if (isset($outlet['outlet_id']) && $outlet['outlet_id'] == $user_outlet_id) {
          $outletData = $outlet;
          $filledStock = isset($outlet['stock']) ? intval($outlet['stock']) : 0;
          break;
        }
      }
    }
    if ($crequests) {
      foreach ($crequests as $request) {
        if (isset($request['outlet_id']) && $request['outlet_id'] == $user_outlet_id && $request['empty_cylinder'] == 'received') {
          $emptyStock += intval($request['quantity']);
        }
      }
    }
  }
  ?>
  <style>
    .chart-container {
      max-width: 300px;
      margin: 0 auto;
      /* Center the chart horizontally */
    }
  </style>
</head>

<body>
  <!-- Main Content -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Stock Level Report</h1>
    </div>
    <?php if (!empty($outletData)) { ?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($outletData['name']); ?></h5>
          <p class="card-text">
            <strong>District:</strong> <?php echo htmlspecialchars($outletData['district']); ?><br>
            <strong>Town:</strong> <?php echo htmlspecialchars($outletData['town']); ?>
          </p>
          <div class="d-flex justify-content-end">
            <p class="card-text me-4">
              <strong>Filled Cylinders:</strong> <?php echo $filledStock; ?>
            </p>
            <p class="card-text">
              <strong>Empty Cylinders:</strong> <?php echo $emptyStock; ?>
            </p>
            <button id="printBtn" class="btn btn-primary ms-5">Print Report</button>
          </div>
        </div>
      </div>
      <div class="mt-5 d-flex justify-content-center chart-container">
        <canvas id="stockChart"></canvas>
      </div>
    <?php } else { ?>
      <div class="alert alert-warning" role="alert">
        No outlet data found for this user.
      </div>
    <?php } ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const filledStock = <?php echo json_encode($filledStock); ?>;
    const emptyStock = <?php echo json_encode($emptyStock); ?>;
    const ctx = document.getElementById('stockChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Filled Cylinders', 'Empty Cylinders'],
        datasets: [{
          data: [filledStock, emptyStock],
          backgroundColor: [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 99, 132, 1)'
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