<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monthly Sales by Outlet - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

  $outletMonthlySales = [];
  $outlets = $database->getReference('outlets')->getValue();
  $totalSalesAmount = 0;

  function processTimestamp($timestamp)
  {
    if (is_numeric($timestamp)) {
      return  $timestamp / 1000;
    } else {
      return strtotime($timestamp);
    }
  }


  $crequests = $database->getReference('crequests')->getValue();
  if (is_array($crequests) && $outlets) {
    foreach ($crequests as $request) {
      if (
        isset($request['created_at']) &&
        $request['payment_status'] === 'received' &&
        isset($request['total_price'])
      ) {
        $timestamp = processTimestamp($request['created_at']);
        if ($timestamp !== false) {
          $requestDate = new DateTime("@$timestamp");
          if ($requestDate->format('Y-m') == date('Y-m', strtotime($selectedMonth))) {
            $month = $requestDate->format('F');
            $outletId = $request['outlet_id'];
            if (isset($outlets[$outletId])) {
              $outletName = $outlets[$outletId]['name'];
              if (!isset($outletMonthlySales[$outletName][$month])) {
                $outletMonthlySales[$outletName][$month] = 0;
              }
              $outletMonthlySales[$outletName][$month] += intval($request['total_price']);
              $totalSalesAmount += intval($request['total_price']);
            } else {
  ?>
              <script>
                console.log("Unknown outlet", <?php echo json_encode($outletId); ?>);
              </script>
  <?php
            }
          }
        }
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
      <h1 class="h2">Monthly Sales by Outlet</h1>
    </div>
    <div class="d-flex  justify-content-between mb-3">
      <div class="col-3">
        <label for="monthSelect" class="form-label fw-bold">Select Month:</label>
        <input type="month" class="form-control" id="monthSelect" value="<?php echo $selectedMonth; ?>"
          onchange="window.location.href = '?month=' + this.value;">
      </div>
      <!-- <div class="col-3 d-flex justify-content-end">
        <button id="printBtn" class="btn btn-primary me-2" onclick="window.print()">Print Report</button>
        <button id="exportJpgBtn" class="btn btn-primary">Export as JPG</button>
      </div> -->
    </div>
    <?php if ($totalSalesAmount > 0) { ?>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Monthly Sales Summary</h5>
          <p class="card-text">
            <strong>Total Sales Amount:</strong> <?php echo $totalSalesAmount; ?>
          </p>
        </div>
      </div>
    <?php } ?>
    <div class="table-responsive mt-5 px-2 border">
      <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
        <thead>
          <tr>
            <th>Outlet Name</th>
            <th>Month</th>
            <th>Total Sales</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($outletMonthlySales)) {
            foreach ($outletMonthlySales as $outlet => $salesData) {
              foreach ($salesData as $month => $totalQuantity) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($outlet) . '</td>';
                echo '<td>' . htmlspecialchars($month) . '</td>';
                echo '<td>' . htmlspecialchars($totalQuantity) . '</td>';
                echo '</tr>';
              }
            }
          } else {
            echo '<tr><td colspan="3">No sales data available for the selected period.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <script>
    document.getElementById('exportJpgBtn').addEventListener('click', function() {
      const chartCanvas = document.getElementById('example');
      html2canvas(chartCanvas).then(canvas => {
        const chartImage = canvas.toDataURL('image/jpeg', 1.0);
        const a = document.createElement('a');
        a.href = chartImage;
        a.download = 'monthly_sales_by_outlet_report.jpg';
        a.click();
      });
    });
    $(document).ready(function() {
      $('#example').DataTable();
    });
  </script>
  <?php
  include_once '../components/manager-dashboard-down.php';
  message_success();
  ?>
</body>

</html>