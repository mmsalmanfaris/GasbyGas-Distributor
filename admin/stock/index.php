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
        $dispatch = $database->getReference('dispatch_schedules')->getValue();

        $totalStock = 0;
        if ($stockRef) {
            foreach ($stockRef as $stock) {
                $totalStock += intval($stock['available']);
            }
        }

        ?>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5 mt-4">
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100 bg-light">
                    <div class="card-body  p-4">
                        <h5 class="card-title fw-bold">Current Stock Level</h5>
                        <h2 class="card-text mt-3 display-6 fw-bold" id="currentStock"><?php echo $totalStock; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100 bg-light">
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
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 bg-light">
                <div class="card h-100 bg-light">
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
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ">
                <div class="card h-100 bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">Stock Status</h5>
                        <h2 class="card-text mt-3 display-6 fw-bold">
                            <?php
                            $stockStatus = $totalStock >= $totalRequestedUnits ?
                                '<span class="text-success">Sufficient</span>' :
                                '<span class="text-danger">Insufficient</span>';
                            echo $stockStatus;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Controls -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4">Stock Status Control</h4>
                        <div class="d-flex gap-3">
                            <select class="form-select form-select-lg" id="stockStatus" style="max-width: 350px;">
                                <option value="true">Available</option>
                                <option value="false">Out of stock</option>
                            </select>
                            <button class="btn btn-primary btn-lg" id="updateStockStatus">Update Status</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-4">New Stock Management</h4>
                        <div class="d-flex gap-3">
                            <input type="number" style="max-width: 350px;" class="form-control form-control-lg"
                                placeholder="Enter new stock" id="newStock">
                            <button class="btn btn-primary btn-lg" id="updateStock">Update Stock</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById("updateStockStatus").addEventListener("click", function () {
            const selectedStatus = document.getElementById("stockStatus").value;

            fetch("update_availability.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `is_available=${selectedStatus}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "./?status=dataupdate";
                    } else {
                        alert("Error updating stock: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error: " + error.message);
                });
        });


        document.getElementById("updateStock").addEventListener("click", function () {
            const newStock = document.getElementById("newStock").value;

            if (!newStock || newStock <= 0) {
                alert("Please enter a valid stock amount.");
                return;
            }

            fetch("add_stock.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `newStock=${newStock}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "./?status=datasuccess";
                    } else {
                        alert("Error adding stock: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error: " + error.message);
                });
        });

    </script>



    <?php

    message_success();
    include_once '../components/manager-dashboard-down.php';
    ?>
    </body>

</html>