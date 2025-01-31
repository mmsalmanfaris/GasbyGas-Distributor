<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monthly Sales Report - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

  $monthlySalesData = [];
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
  $totalCylindersSold = 0;
  $totalSalesAmount = 0;


  if ($crequests = $database->getReference('crequests')->getValue()) {
    foreach ($crequests as $request) {
      if (
        isset($request['created_at']) &&
        $request['payment_status'] === 'received' &&
        isset($request['total_price'])
      ) {
        $requestDate = new DateTime($request['created_at']);
        if ($requestDate->format('Y') == $selectedYear) {
          $month = $requestDate->format('F');
          if (!isset($monthlySalesData[$month])) {
            $monthlySalesData[$month] = 0;
          }
          $monthlySalesData[$month] += intval($request['total_price']);
          $totalCylindersSold += intval($request['quantity']);
          $totalSalesAmount += intval($request['total_price']);
        }
      }
    }
    foreach ($allMonths as $month) {
      $labels[] = $month;
      $monthlySales[] = isset($monthlySalesData[$month]) ? $monthlySalesData[$month] : 0;
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
        <label for="yearSelect" class="form-label fw-bold">Select Year:</label>
        <input type="number" class="form-control" id="yearSelect" value="<?php echo $selectedYear; ?>"
          onchange="window.location.href = '?year=' + this.value;" min="2020" max="<?php echo date('Y'); ?>">
      </div>
      <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary me-2">Print Report</button>
        <!-- <button id="exportJpgBtn" class="btn btn-primary">Export as JPG</button> -->
      </div>
    </div>
    <?php if ($totalCylindersSold > 0) { ?>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Monthly Sales Summary</h5>
          <p class="card-text">
            <strong>Total Cylinders Sold:</strong> <?php echo $totalCylindersSold; ?>
            <span class="ms-5"><strong>Total Sales Amount:</strong> <?php echo $totalSalesAmount; ?></span>
          </p>
        </div>
      </div>
    <?php } ?>

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
          label: 'Total Revenue',
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