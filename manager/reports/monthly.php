<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monthly Sales Report - Manager</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $userRecord = $database->getReference("users/{$user_id}")->getValue();
  $user_outlet_id = $userRecord['outlet_id'] ?? null;

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

  $monthlySalesData = [];
  $labels = [];

  if ($user_outlet_id) {
    $crequests = $database->getReference('crequests')->getValue();
    if ($crequests) {
      // Calculate the start and end dates of the selected month
      $startDate = date('Y-m-01', strtotime($selectedMonth));
      $endDate = date('Y-m-t', strtotime($selectedMonth));

      foreach ($crequests as $request) {
        if (
          $request['outlet_id'] == $user_outlet_id &&
          $request['sdelivery'] &&
          $request['payment_status'] === 'received' &&
          $request['delivery_status'] === 'issued'
        ) {
          $requestDate = date('Y-m-d', strtotime($request['sdelivery']));
          if ($requestDate >= $startDate && $requestDate <= $endDate) {
            $dayOfMonth = date('j', strtotime($requestDate));
            if (!isset($monthlySalesData[$dayOfMonth])) {
              $monthlySalesData[$dayOfMonth] = 0;
            }
            $monthlySalesData[$dayOfMonth] += intval($request['quantity']);
          }
        }
      }
      $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($selectedMonth)), date('Y', strtotime($selectedMonth)));
      for ($day = 1; $day <= $daysInMonth; $day++) {
        $labels[] = $day;
        $monthlySales[] = isset($monthlySalesData[$day]) ? $monthlySalesData[$day] : 0;
      }
    }
  }

  ?>
  <style>
    body {
      overflow-y: scroll;
    }
  </style>
</head>

<body>
  <!-- Main Content -->
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Monthly Sales Report</h1>
    </div>

    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>"
          onchange="window.location.href = '?month=' + this.value;">
      </div>

      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary me-2">Print Report</button>
        <button id="exportJpgBtn" class="btn btn-primary">Export as JPG</button>
      </div>
    </div>

    <canvas id="monthlySalesChart" width="400" height="200"></canvas>

  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const monthlySales = <?php echo json_encode($monthlySales); ?>;
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');

    const myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Cylinders Sold',
          data: monthlySales,
          backgroundColor: 'rgba(54, 162, 235, 0.7)', // Blue with opacity
          borderColor: 'rgba(54, 162, 235, 1)', // Blue border
          borderWidth: 1,
          hoverBackgroundColor: 'rgba(54, 162, 235, 0.9)' // Darker blue on hover
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        },
        plugins: {
          legend: {
            display: false // Hide the legend
          },

          // Add background to the chart
        }
      },
      plugins: [{
        beforeDraw: (chart) => {
          const ctx = chart.canvas.getContext('2d');
          ctx.fillStyle = 'white';
          ctx.fillRect(0, 0, chart.width, chart.height);
        }
      }]
    });

    document.getElementById('exportJpgBtn').addEventListener('click', function() {
      const chartCanvas = document.getElementById('monthlySalesChart');
      const chartImage = chartCanvas.toDataURL('image/jpeg', 1.0);
      const a = document.createElement('a');
      a.href = chartImage;
      a.download = 'monthly_sales_report.jpg';
      a.click();
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