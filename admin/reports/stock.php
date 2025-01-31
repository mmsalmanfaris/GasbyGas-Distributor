<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Report - Admin</title>

  <?php
  include_once '../components/manager-dashboard-top.php';
  include_once '../../output/message.php';
  require '../includes/firebase.php';

  $outlets = $database->getReference('outlets')->getValue();
  $crequests = $database->getReference('crequests')->getValue();

  $totalStock = 0;
  $totalEmptyCylindersReceived = 0;

  if (is_array($outlets)) {
    foreach ($outlets as $outlet) {
      if (isset($outlet['stock'])) {
        $totalStock += intval($outlet['stock']);
      }
    }
  }
  if (is_array($crequests)) {
    foreach ($crequests as $request) {
      if ($request['empty_cylinder'] === 'received') {
        $totalEmptyCylindersReceived += intval($request['quantity']);
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
      <h1 class="h2">Stock Report</h1>
    </div>

    <?php if ($totalStock > 0) { ?>
      <!-- <div class="d-flex justify-content-end mb-3">
        <button id="printBtn" class="btn btn-primary me-2" onclick="window.print()">Print Report</button>
      </div> -->
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">Stock Summary</h5>
          <p class="card-text">
            <strong>Total Cylinders In Stock:</strong> <?php echo $totalStock; ?>
            <span class="ms-5"><strong>Total Empty Cylinders Received:</strong> <?php echo $totalEmptyCylindersReceived; ?>
          </p>
        </div>
      </div>

    <?php } ?>
    <div class="table-responsive mt-5 px-2 border">
      <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
        <thead>
          <tr>
            <th>Outlet Name</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (is_array($outlets)) {
            foreach ($outlets as $outlet) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars($outlet['name']) . '</td>';
              echo '<td>' . htmlspecialchars($outlet['stock']) . '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="3">No data available for the selected period.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script>
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