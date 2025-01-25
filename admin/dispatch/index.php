<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch Schedule Management - Admin</title>

    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <?php
        $dispatchSchedules = $database->getReference('dispatch_schedules')->getValue();
        $outlets = $database->getReference('outlets')->getValue();
        ?>

        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Dispatch Schedules</h5>
                <h4><?php echo count($dispatchSchedules); ?></h4>
            </div>
            <div class="col-3 border p-4 me-3">
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
            <div class="col-3 border p-4 me-3">
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
            <div class="col-2 d-flex align-items-center justify-content-center"><button
                    class="btn btn-primary btn-sm h-100 w-100 fs-5" data-bs-toggle="modal"
                    data-bs-target="#addDispatchModal">
                    Add New Schedule</button>
            </div>
        </div>


        <div class="table-responsive mt-5 px-2 border">
            <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Outlet ID</th>
                        <th>Request Date</th>
                        <th>Scheduled Delivery</th>
                        <th>Expected Delivery</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($dispatchSchedules) {
                        foreach ($dispatchSchedules as $scheduleId => $schedule) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($scheduleId) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['outlet_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['request_date']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['sdelivery']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['edelivery']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['quantity']) . '</td>';
                            echo '<td>' . htmlspecialchars($schedule['status']) . '</td>';
                            echo "<td>
                                  <a href='?schedule_id={$scheduleId}' class='btn btn-sm btn-warning'>Edit</a>
                                  <a href='../includes/deleteDispatch.inc.php?schedule_id={$scheduleId}' class='btn btn-sm btn-danger'>Delete</a>
                                  </td>";
                            echo '</tr>';
                        }
                    } else {
                        echo 'No dispatch schedules found.';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-5 p-4 border">
            <h4>Delivery Schedule</h4>
            <form id="deliveryScheduleForm" action="../includes/updateDispatchStatus.inc.php" method="post">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="districtSelect" class="form-label">Select District:</label>
                        <select class="form-select form-control-lg" name="district" id="districtSelect" required>
                            <option value="" disabled selected>Select a District</option>
                            <?php
                            if ($outlets) {
                                $districts = array_unique(array_column($outlets, 'district'));
                                foreach ($districts as $district) {
                                    echo '<option value="' . htmlspecialchars($district) . '">' . htmlspecialchars($district) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="totalQuantity" class="form-label">Total Quantity:</label>
                        <input type="text" class="form-control form-control-lg" id="totalQuantity" value="0" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="deliveryDate" class="form-label">Scheduled Delivery Date:</label>
                        <input type="date" class="form-control form-control-lg" name="delivery_date" id="deliveryDate" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Mark as Dispatched</button>
            </form>
        </div>
    </main>

    <script>
        document.querySelector('.btn-primary').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('addDispatchModal'));
            myModal.show();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Check if the URL contains the `schedule_id` parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('schedule_id')) {
                // Trigger the modal
                const editModal = new bootstrap.Modal(document.getElementById('editDispatchModal'));
                editModal.show();
            }

            const districtSelect = document.getElementById('districtSelect');
            const totalQuantityInput = document.getElementById('totalQuantity');

            districtSelect.addEventListener('change', function() {
                const selectedDistrict = districtSelect.value;
                let totalQuantity = 0;

                if (selectedDistrict) {
                    <?php
                    if ($dispatchSchedules) { ?>
                        const schedules = <?php echo json_encode($dispatchSchedules); ?>;
                        Object.values(schedules).forEach(schedule => {
                            <?php
                            if ($outlets) { ?>
                                const outlets = <?php echo json_encode($outlets); ?>;
                                Object.values(outlets).forEach(outlet => {
                                    if (outlet.district == selectedDistrict && outlet.outlet_id == schedule.outlet_id) {
                                        totalQuantity += parseInt(schedule.quantity);
                                    }
                                });
                            <?php } ?>
                        });
                    <?php } ?>
                }
                totalQuantityInput.value = totalQuantity;
            });
        });
    </script>

    <?php
    include_once 'edit.inc.php';
    include_once 'add.inc.php';
    message_success();
    include_once '../components/manager-dashboard-down.php';
    ?>