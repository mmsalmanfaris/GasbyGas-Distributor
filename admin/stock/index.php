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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
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

        <!-- District-wise Stock Requests -->
        <div class="mt-5">
            <h4 class="mb-4">District-wise Stock Allocation</h4>
            <div class="accordion" id="districtAccordion">
                <?php
                if ($outlets) {
                    $districts = array();
                    // Group outlets by district
                    foreach ($outlets as $outletId => $outlet) {
                        $district = $outlet['district'];
                        if (!isset($districts[$district])) {
                            $districts[$district] = array();
                        }
                        $districts[$district][$outletId] = $outlet;
                    }

                    foreach ($districts as $district => $districtOutlets) {
                        $districtRequests = array();
                        $totalDistrictRequest = 0;
                        
                        // Calculate requests for this district
                        if ($dispatchSchedules) {
                            foreach ($dispatchSchedules as $scheduleId => $schedule) {
                                if ($schedule['status'] === 'pending' && 
                                    isset($districtOutlets[$schedule['outlet_id']])) {
                                    $outletId = $schedule['outlet_id'];
                                    if (!isset($districtRequests[$outletId])) {
                                        $districtRequests[$outletId] = 0;
                                    }
                                    $districtRequests[$outletId] += intval($schedule['quantity']);
                                    $totalDistrictRequest += intval($schedule['quantity']);
                                }
                            }
                        }
                        ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#district<?php echo md5($district); ?>">
                                    <div class="d-flex justify-content-between w-100 me-3">
                                        <span><?php echo htmlspecialchars($district); ?></span>
                                        <span>Requested: <?php echo $totalDistrictRequest; ?> units</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="district<?php echo md5($district); ?>" 
                                class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Outlet Name</th>
                                                    <th>Requested Quantity</th>
                                                    <th>Can Fulfill</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $remainingStock = $currentStock;
                                                foreach ($districtOutlets as $outletId => $outlet) {
                                                    $requested = isset($districtRequests[$outletId]) ? 
                                                        $districtRequests[$outletId] : 0;
                                                    $canFulfill = $remainingStock >= $requested;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($outlet['name']); ?></td>
                                                        <td><?php echo $requested; ?></td>
                                                        <td>
                                                            <?php if ($requested > 0): ?>
                                                                <?php if ($canFulfill): ?>
                                                                    <span class="badge bg-success">Yes</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger">No</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">No Request</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($requested > 0): ?>
                                                                <?php if ($canFulfill): ?>
                                                                    <button class="btn btn-success btn-sm" 
                                                                        onclick="allocateStock('<?php echo $outletId; ?>', <?php echo $requested; ?>)">
                                                                        Allocate Stock
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button class="btn btn-warning btn-sm" 
                                                                        onclick="scheduleDelay('<?php echo $outletId; ?>')">
                                                                        Schedule Delay
                                                                    </button>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    if ($canFulfill) {
                                                        $remainingStock -= $requested;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <script>
        function allocateStock(outletId, quantity) {
            if (confirm('Allocate ' + quantity + ' units to this outlet?')) {
                fetch('../includes/allocateStock.inc.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        outlet_id: outletId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to allocate stock: ' + data.message);
                    }
                });
            }
        }

        function scheduleDelay(outletId) {
            if (confirm('Schedule delay notification for this outlet?')) {
                fetch('../includes/scheduleDelay.inc.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        outlet_id: outletId,
                        delay_days: 3
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to schedule delay: ' + data.message);
                    }
                });
            }
        }
    </script>

    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>
</body>
</html>
