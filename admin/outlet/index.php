<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outlet Management - Admin</title>
    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.bootstrap5.min.css">
</head>

<body>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php
        $outlets = $database->getReference('outlets')->getValue();
        if (!is_array($outlets)) {
            $outlets = [];
        }
        ?>

        <!-- Dashboard Summary -->
        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Outlets</h5>
                <h4><?php echo count($outlets); ?></h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Outlet Managers Count</h5>
                <h4>
                    <?php
                    $managerCount = 0;
                    foreach ($outlets as $outlet) {
                        if (isset($outlet['manager_id'])) {
                            $managerCount++;
                        }
                    }
                    echo $managerCount;
                    ?>
                </h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Registered Outlets Count</h5>
                <h4><?php echo count($outlets); ?></h4>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center">
                <button class="btn btn-primary btn-sm h-100 w-100 fs-5" data-bs-toggle="modal" data-bs-target="#addOutletModal">
                    Add New Outlet
                </button>
            </div>
        </div>

        <!-- Outlets Table -->
        <div class="table-responsive mt-5 px-2 border">
            <table id="outletTable" class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">District</th>
                        <th scope="col">Name</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Town</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
$index = 1;
if ($outlets) {
    foreach ($outlets as $outletId => $outlet) {
        if (is_array($outlet)) {
            echo '<tr>';
            echo '<td>' . $index++ . '</td>';
            echo '<td>' . htmlspecialchars($outlet['district']) . '</td>';
            echo '<td>' . htmlspecialchars($outlet['name']) . '</td>';
            echo '<td>' . htmlspecialchars($outlet['stock']) . '</td>';
            echo '<td>' . htmlspecialchars($outlet['town']) . '</td>';
            echo '<td>
                    <a href="edit.inc.php?outlet_id=' . urlencode($outletId) . '" class="btn btn-warning btn-sm">Edit</a>
                    <a href="../includes/delete.inc.php?type=outlet&id=' . urlencode($outletId) . '" class="btn btn-danger btn-sm">Delete</a>
                  </td>';
            echo '</tr>';
        }
    }
} else {
    echo '<tr><td colspan="6">No outlets found in the database.</td></tr>';
}
?>

                </tbody>
            </table>
        </div>
    </main>

    <!-- Include Add Outlet Modal -->
    <?php
    include_once 'add.inc.php';
    include_once 'edit.inc.php';
    message_success();
    include_once '../components/manager-dashboard-down.php';
    ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-OUYdbPoJ6JXOD7ALksO4MiOdKx1lg5P2KTAiU4VgrS4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#outletTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
</body>

</html>
