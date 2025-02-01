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

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5 mt-4">
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100 ">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Current Stock Level</h5>
                        <h2 class="card-text mt-3 display-6 fw-bold "><?php echo $currentStock; ?></h2>
                        <p class="text-muted">Available Units</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100">
                    <div class="card-body  p-4">
                        <h5 class="card-title fw-bold">Pending Requests</h5>
                        <h2 class="card-text mt-3 display-6 fw-bold">
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
                        </h2>
                        <p class="text-muted">Active Requests</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">Total Requested Units</h5>
                        <h2 class="card-text mt-3 display-6 fw-bold">
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
                        </h2>
                        <p class="text-muted">Units Requested</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ">
                <div class="card h-100 bg-light">
                    <h5 class="card-title fw-bold">Stock Status</h5>
                    <h2 class="card-text mt-3 display-6 fw-bold">
                        <?php
                        $stockStatus = $currentStock >= $totalRequestedUnits ?
                            '<span class="text-success">Sufficient</span>' :
                            '<span class="text-danger">Insufficient</span>';
                        echo $stockStatus;
                        ?>
                    </h2>
                    <p class="text-muted">Current Status</p>
                </div>
            </div>
        </div>

        <!-- Stock Controls -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-4">Stock Management Controls</h4>
                        <div class="d-flex justify-content-center gap-3">
                            <select class="form-select form-select-lg w-50" id="stockStatus" style="max-width: 300px;">
                                <option value="true">Available</option>
                                <option value="false">Unavailable</option>
                            </select>
                            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                data-bs-target="#addStockModal">
                                <i class="fas fa-plus-circle me-2"></i>Add Stock
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Stock Modal -->
        <div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStockModalLabel">Add Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../includes/add_stock.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="stockQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="stockQuantity" name="quantity" required
                                    min="1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>
    </body>

</html>