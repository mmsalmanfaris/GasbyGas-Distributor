<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - Admin</title>

    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php
        $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();
        $outlets = $database->getReference('outlets')->getValue();
        $stockRef = $database->getReference('stock')->getValue();
        $currentStock = isset($stockRef['available']) ? $stockRef['available'] : 0;
        ?>

        <!-- Stock Overview -->
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Stock Management</h1>
        </div>

        <div class="d-flex mt-4">
            <div class="col-3 border p-4 me-3 bg-light rounded shadow-sm">
                <h5>Current Stock Level</h5>
                <h4><?php echo $currentStock; ?> units</h4>
            </div>
            <div class="col-3 border p-4 me-3 bg-light rounded shadow-sm">
                <h5>Pending Requests</h5>
                <h4>
                    <?php
                    $totalPendingRequests = 0;
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $schedule) {
                            if ($schedule['status'] === 'pending') {
                                $totalPendingRequests++;
                            }
                        }
                    }
                    echo $totalPendingRequests;
                    ?>
                </h4>
            </div>
            <div class="col-3 border p-4 me-3 bg-light rounded shadow-sm">
                <h5>Total Requested Units</h5>
                <h4>
                    <?php
                    $totalRequestedUnits = 0;
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $schedule) {
                            if ($schedule['status'] === 'pending') {
                                $totalRequestedUnits += intval($schedule['quantity']);
                            }
                        }
                    }
                    echo $totalRequestedUnits;
                    ?>
                </h4>
            </div>
            <div class="col-3 border p-4 bg-light rounded shadow-sm">
                <h5>Stock Status</h5>
                <h4>
                    <?php
                    $stockStatus = $currentStock >= $totalRequestedUnits ?
                        '<span class="text-success">Sufficient</span>' :
                        '<span class="text-danger">Insufficient</span>';
                    echo $stockStatus;
                    ?>
                </h4>
            </div>
        </div>
    </main>

    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>
    </body>

</html>