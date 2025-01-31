<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consumer Request Report - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
  $selectedOutlet = isset($_GET['outlet']) ? $_GET['outlet'] : 'all';

  $outlets = $database->getReference('outlets')->getValue();

  $monthlyRequests = [];
  $monthlyQuantities = [];
  $panelCounts = [];
  $deliveryStatusCounts = [];
  $requestTypeCounts = [];
  $cylinderTypeCounts = [];
  $customerTypeCounts = [];
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


  if ($crequests = $database->getReference('crequests')->getValue()) {
    foreach ($crequests as $request) {
      if (isset($request['created_at'])) {
        $requestDate = new DateTime($request['created_at']);
        if ($requestDate->format('Y') == $selectedYear) {
          if ($selectedOutlet === 'all' || $request['outlet_id'] === $selectedOutlet) {
            $month = $requestDate->format('F');
            if (!isset($monthlyRequests[$month])) {
              $monthlyRequests[$month] = 0;
              $monthlyQuantities[$month] = 0;
            }
            $monthlyRequests[$month]++;
            $monthlyQuantities[$month] += intval($request['quantity']);
            if (!isset($panelCounts[$request['panel']])) {
              $panelCounts[$request['panel']] = 0;
            }
            $panelCounts[$request['panel']]++;

            if (!isset($deliveryStatusCounts[$request['delivery_status']])) {
              $deliveryStatusCounts[$request['delivery_status']] = 0;
            }
            $deliveryStatusCounts[$request['delivery_status']]++;
            if (!isset($requestTypeCounts[$request['type']])) {
              $requestTypeCounts[$request['type']] = 0;
            }
            $requestTypeCounts[$request['type']]++;
            if (isset($request['cylinder_type'])) {
              if (!isset($cylinderTypeCounts[$request['cylinder_type']])) {
                $cylinderTypeCounts[$request['cylinder_type']] = 0;
              }
              $cylinderTypeCounts[$request['cylinder_type']] += intval($request['quantity']);
            }
            if (isset($request['type'])) {
              if (!isset($customerTypeCounts[$request['type']])) {
                $customerTypeCounts[$request['type']] = 0;
              }
              $customerTypeCounts[$request['type']] += intval($request['quantity']);
            }
          }
        }
      }
    }
  }

  $labels = $allMonths;
  $monthlyReqCounts = array_values(array_merge(array_fill_keys($allMonths, 0), array_combine(array_keys($monthlyRequests), $monthlyRequests)));
  $monthlyQuantityCounts = array_values(array_merge(array_fill_keys($allMonths, 0), array_combine(array_keys($monthlyQuantities), $monthlyQuantities)));
  $panelLabels = array_keys($panelCounts);
  $panelData = array_values($panelCounts);
  $deliveryStatusLabels = array_keys($deliveryStatusCounts);
  $deliveryStatusData = array_values($deliveryStatusCounts);
  $requestTypeLabels = array_keys($requestTypeCounts);
  $requestTypeData = array_values($requestTypeCounts);
  $cylinderTypeLabels = array_keys($cylinderTypeCounts);
  $cylinderTypeData = array_values($cylinderTypeCounts);
  $customerTypeLabels = array_keys($customerTypeCounts);
  $customerTypeData = array_values($customerTypeCounts);
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
        <label for="yearSelect" class="form-label fw-bold">Select Year:</label>
        <input type="number" class="form-control" id="yearSelect" value="<?php echo $selectedYear; ?>"
          onchange="window.location.href = '?year=' + this.value + '&outlet=' + document.getElementById('outletSelect').value ;" min="2020" max="<?php echo date('Y'); ?>">
      </div>
      <div class="col-3">
        <label for="outletSelect" class="form-label fw-bold">Select Outlet:</label>
        <select class="form-select" id="outletSelect"
          onchange="window.location.href = '?outlet=' + this.value + '&year=' + document.getElementById('yearSelect').value;">
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
        <button id="printBtn" class="btn btn-primary" onclick="window.print()">Print Report</button>
      </div>
    </div>

    <div class="chart-container">
      <canvas id="requestChart" width="600" height="300"></canvas>
    </div>
    <div class="chart-container mt-5">
      <canvas id="quantityChart" width="600" height="300"></canvas>
    </div>
    <div class="mt-5 d-flex">
      <div class="col-md-6  chart-container ">
        <canvas id="panelChart" width="400" height="200"></canvas>
      </div>
      <div class="col-md-6 chart-container">
        <canvas id="deliveryStatusChart" width="400" height="200"></canvas>
      </div>
    </div>
    <div class="mt-5 chart-container">
      <canvas id="requestTypeChart" width="600" height="300"></canvas>
    </div>
    <div class="mt-5 d-flex">
      <div class="col-md-6  chart-container ">
        <canvas id="cylinderTypeChart" width="400" height="200"></canvas>
      </div>
      <div class="col-md-6 chart-container">
        <canvas id="customerTypeChart" width="400" height="200"></canvas>
      </div>
    </div>

  </main>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <script>
    const labels = <?php echo json_encode($labels); ?>;
    const monthlyReqCounts = <?php echo json_encode($monthlyReqCounts); ?>;
    const monthlyQuantityCounts = <?php echo json_encode($monthlyQuantityCounts); ?>;
    const panelLabels = <?php echo json_encode($panelLabels); ?>;
    const panelData = <?php echo json_encode($panelData); ?>;
    const deliveryStatusLabels = <?php echo json_encode($deliveryStatusLabels); ?>;
    const deliveryStatusData = <?php echo json_encode($deliveryStatusData); ?>;
    const requestTypeLabels = <?php echo json_encode($requestTypeLabels); ?>;
    const requestTypeData = <?php echo json_encode($requestTypeData); ?>;
    const cylinderTypeLabels = <?php echo json_encode($cylinderTypeLabels); ?>;
    const cylinderTypeData = <?php echo json_encode($cylinderTypeData); ?>;
    const customerTypeLabels = <?php echo json_encode($customerTypeLabels); ?>;
    const customerTypeData = <?php echo json_encode($customerTypeData); ?>;


    const ctx1 = document.getElementById('requestChart').getContext('2d');
    const myChart = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Requests Per Month',
          data: monthlyReqCounts,
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
            text: 'Consumer Requests Per Month',
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
          label: 'Total Quantities Per Month',
          data: monthlyQuantityCounts,
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
            text: 'Total Quantities Per Month',
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
    const ctx5 = document.getElementById('requestTypeChart').getContext('2d');
    const requestTypeChart = new Chart(ctx5, {
      type: 'pie',
      data: {
        labels: requestTypeLabels,
        datasets: [{
          data: requestTypeData,
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
            text: 'Distribution of Requests by Type',
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


    const ctx6 = document.getElementById('cylinderTypeChart').getContext('2d');
    const cylinderTypeChart = new Chart(ctx6, {
      type: 'bar',
      data: {
        labels: cylinderTypeLabels,
        datasets: [{
          label: 'Total Quantities by Cylinder Type',
          data: cylinderTypeData,
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
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Total Quantities by Cylinder Type',
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
    const ctx7 = document.getElementById('customerTypeChart').getContext('2d');
    const customerTypeChart = new Chart(ctx7, {
      type: 'bar',
      data: {
        labels: customerTypeLabels,
        datasets: [{
          label: 'Total Quantities by Customer Type',
          data: customerTypeData,
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
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Total Quantities by Customer Type',
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