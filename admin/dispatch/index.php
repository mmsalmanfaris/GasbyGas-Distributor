<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Schedule - Admin</title>

    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <?php

        $dispatch = $database->getReference('dispatch_schedules')->getValue();

        $outletName = 'Unknown Outlet';

        $dispatchSchedules = [];
        $today = date('Y-m-d'); // Get today's date
        
        foreach ($dispatch as $scheduleId => $schedule) {
            // Load schedules with request_date first
            if (isset($schedule['request_date']) && $schedule['request_date'] <= $today) {
                // If sdelivery exists, ensure it's within the range
                if (!isset($schedule['sdelivery']) || $schedule['sdelivery'] >= $today) {
                    $dispatchSchedules[$scheduleId] = $schedule;
                }
            }
        }

        $outlets = $database->getReference('outlets')->getValue();
        ?>

        <div class="d-flex mt-5">
            <div class="col-3 card border p-4 me-3 bg-light">
                <h5>Total Dispatch Schedules</h5>
                <h4><?php echo count($dispatchSchedules); ?></h4>
            </div>
            <div class="col-3 card border p-4 me-3 bg-light">
                <h5>Pending Schedules</h5>
                <h4>
                    <?php
                    $pendingCount = 0;
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $schedule) {
                            if ($schedule['status'] === 'pending') {
                                $pendingCount++;
                            }
                        }
                    }
                    echo $pendingCount;
                    ?>
                </h4>
            </div>
            <div class="col-3 card border p-4 me-3 bg-light">
                <h5>Dispatched Schedules</h5>
                <h4>
                    <?php
                    $dispatchedCount = 0;
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $schedule) {
                            if ($schedule['status'] === 'dispatched') {
                                $dispatchedCount++;
                            }
                        }
                    }
                    echo $dispatchedCount;
                    ?>
                </h4>
            </div>
        </div>

        <div class="mt-5 p-4 card border bg-light">
            <form id="deliveryScheduleForm" action="../includes/updateDispatchStatus.inc.php" method="post">
                <!-- Replace 'your-script.php' with the actual path -->
                <div class="d-flex align-items-center gap-3">
                    <div class="w-25">
                        <label for="outletSelect" class="form-label">Select Outlet:</label>
                        <!-- Select Outlet Dropdown -->
                        <select class="form-select form-control-lg" name="outlet" id="outletSelect" required>
                            <option value="" disabled selected>Select an Outlet</option>
                            <?php
                            $scheduledOutlets = [];

                            // Collect outlet IDs from dispatch schedules
                            foreach ($dispatchSchedules as $schedule) {
                                if (!empty($schedule['outlet_id'])) {
                                    $scheduledOutlets[$schedule['outlet_id']] = true;
                                }
                            }

                            // Display only outlets with dispatch schedules
                            foreach ($outlets as $outletId => $outlet) {
                                if (isset($scheduledOutlets[$outletId])) {
                                    echo '<option value="' . htmlspecialchars($outletId) . '">' . htmlspecialchars($outlet['name']) . '</option>';
                                }
                            }
                            ?>
                        </select>


                    </div>

                    <div class="w-25">
                        <label for="deliveryDate" class="form-label">Scheduled Delivery Date:</label>
                        <input type="date" class="form-control form-control-lg" name="delivery_date" id="deliveryDate"
                            required>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary mt-4" style="height: 47px;">Schedule
                            Delivery</button>
                    </div>
                </div>
            </form>

        </div>


        <div class="table-responsive card mt-5 px-2 border p-2">
            <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Outlet</th>
                        <th>Request Date</th>
                        <th>Scheduled Delivery</th>
                        <th>Expected Delivery</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $scheduleId => $schedule) {

                            // Fetch outlet data using the outlet key (outlet ID)
                            $outletId = isset($schedule['outlet_id']) ? $schedule['outlet_id'] : null;

                            if ($outletId && isset($outlets[$outletId])) {
                                $outlet = $outlets[$outletId];
                                $outletName = isset($outlet['name']) ? htmlspecialchars($outlet['name']) : 'Unknown Outlet';
                            }

                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($scheduleId) . '</td>';
                            echo '<td>' . $outletName . '</td>'; // Correctly display outlet name
                            echo '<td>' . htmlspecialchars($schedule['request_date']) . '</td>';
                            echo '<td>' . (isset($schedule['sdelivery']) ? htmlspecialchars($schedule['sdelivery']) : '') . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['edelivery']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['quantity']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['status']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No dispatch schedules found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php

    message_success();
    include_once '../components/manager-dashboard-down.php';
    ?>

</html>