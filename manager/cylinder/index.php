<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cylinder Requests</title>

    <?php
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== false) {
        header('Location: ../login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';

    // Fetch user data to get the user's outlet_id
    $userRecord = $database->getReference("users/{$user_id}")->getValue();
    $user_outlet_id = $userRecord['outlet_id'] ?? null;

    // Fetch crequests data
    $crequests = $database->getReference('crequests')->getValue();

    // Fetch consumers data
    $consumers = $database->getReference('consumers')->getValue();


    // Fetch outlets data
    $outlets = $database->getReference('outlets')->getValue();

    $filteredCrequests = [];
    if ($crequests && $user_outlet_id) {
        foreach ($crequests as $request) {
            if ($request['outlet_id'] === $user_outlet_id) {
                $filteredCrequests[] = $request;
            }
        }
    }

    // Function to calculate request counts
    function calculateRequestCounts($filteredCrequests, $panel, $consumers)
    {
        $total = 0;
        $home = 0;
        $industrial = 0;
        $quantity = 0;
        if ($filteredCrequests) {
            foreach ($filteredCrequests as $request) {
                if ($request['panel'] === $panel) {
                    $total++;
                    $quantity += intval($request['quantity']);
                    if (isset($consumers[$request['consumer_id']])) {
                        if ($consumers[$request['consumer_id']]['category'] === 'home') {
                            $home++;
                        } else if ($consumers[$request['consumer_id']]['category'] === 'industry') {
                            $industrial++;
                        }
                    }
                }
            }
        }

        return ['total' => $total, 'home' => $home, 'industrial' => $industrial, 'quantity' => $quantity];
    }

    // Calculate counts for Panel A
    $dispatchA = calculateRequestCounts($filteredCrequests, 'A', $consumers);

    // Calculate counts for Panel B
    $dispatchB = calculateRequestCounts($filteredCrequests, 'B', $consumers);

    // Get current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');
    $currentDay = date('d');

    // Create formatted dates
    $firstDispatchDate = date('d-m-Y', strtotime("{$currentYear}-{$currentMonth}-14"));
    $secondDispatchDate = date('d-m-Y', strtotime("{$currentYear}-{$currentMonth}-28"));

    $todayDate = date('Y-m-d');


    // Determine if the buttons should be enabled
    $firstDispatchButtonEnabled = ($currentDay >= 1 && $currentDay <= 14);
    $secondDispatchButtonEnabled = ($currentDay >= 15 && $currentDay <= 31);

    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-5">
        <div class="d-flex ">
            <div class="col-4 border p-4 rounded-4 me-5">
                <h3 class="mb-3">1st Dispatch</h3>
                <div class="d-flex justify-content-between">
                    <h5>Total Request</h5>
                    <h5><?php echo $dispatchA['total']; ?></h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5><?php echo $firstDispatchDate; ?></h5>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Home Requests</h5>
                    <h5><?php echo $dispatchA['home']; ?></h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Industrial Requests</h5>
                    <h5><?php echo $dispatchA['industrial']; ?></h5>
                </div>
                <button class="btn btn-primary w-100 mt-3" onclick="requestDispatch('A')" <?php echo $firstDispatchButtonEnabled ? '' : 'disabled'; ?>>Request to Head Office</button>
            </div>
            <div class="col-4 border p-4 rounded-4 me-5">
                <h3 class="mb-3">2nd Dispatch</h3>
                <div class="d-flex justify-content-between">
                    <h5>Total Request</h5>
                    <h5><?php echo $dispatchB['total']; ?></h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5><?php echo $secondDispatchDate; ?></h5>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Home Requests</h5>
                    <h5><?php echo $dispatchB['home']; ?></h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Industrial Requests</h5>
                    <h5><?php echo $dispatchB['industrial']; ?></h5>
                </div>
                <button class="btn btn-primary w-100 mt-3" onclick="requestDispatch('B')" <?php echo $secondDispatchButtonEnabled ? '' : 'disabled'; ?>>Request to Head Office</button>
            </div>
        </div>

        <div class="table-responsive mt-5 px-2 border">
            <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Consumer Name</th>
                        <th>Outlet Name</th>
                        <th>Quantity</th>
                        <th>Panel</th>
                        <th>Empty Cylinder</th>
                        <th>Payment Status</th>
                        <th>Expected Delivery</th>
                        <th>Scheduled Delivery</th>
                        <th>Delivery Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($filteredCrequests) {
                        foreach ($filteredCrequests as $request) {
                            $consumerName = 'N/A';
                            if (isset($consumers[$request['consumer_id']])) {
                                $consumerName = htmlspecialchars($consumers[$request['consumer_id']]['name']);
                            }

                            $outletName = 'N/A';
                            if ($outlets) {
                                foreach ($outlets as $outletKey => $outlet) {
                                    if ($outlet['outlet_id'] === $request['outlet_id']) {
                                        $outletName = htmlspecialchars($outlet['name']);
                                        break;
                                    }
                                }
                            }

                            $emptyCylinder = $request['empty_cylinder'] ? 'Yes' : 'No';
                            $createdAtDate = isset($request['created_at']) ? date('Y-m-d', strtotime($request['created_at'])) : 'N/A';

                            echo '<tr>';
                            echo '<td>' . $consumerName . '</td>';
                            echo '<td>' . $outletName . '</td>';
                            echo '<td>' . htmlspecialchars($request['quantity']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['panel']) . '</td>';
                            echo '<td>' . $emptyCylinder . '</td>';
                            echo '<td>' . htmlspecialchars($request['payment_status']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['edelivery']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['sdelivery']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['delivery_status']) . '</td>';
                            echo '<td>' . $createdAtDate . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10">No customer requests found for your outlet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function requestDispatch(panel) {
            const dispatchData = {
                outlet_id: "<?php echo $user_outlet_id; ?>",
                request_date: "<?php echo $todayDate; ?>",
                status: 'pending',
                created_at: new Date().toISOString()
            };

            if (panel === 'A') {
                dispatchData.quantity = <?php echo $dispatchA['quantity']; ?>;
                dispatchData.edelivery = "<?php echo date('Y-m-d', strtotime($firstDispatchDate)); ?>";
            } else if (panel === 'B') {
                dispatchData.quantity = <?php echo $dispatchB['quantity']; ?>;
                dispatchData.edelivery = "<?php echo date('Y-m-d', strtotime($secondDispatchDate)); ?>";
            }

            fetch('../includes/addDispatchRequest.inc.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dispatchData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = window.location.pathname + '?status=datasuccess';
                    } else {
                        window.location.href = window.location.pathname + '?status=dataerror';
                    }
                    console.log('Success:', data);
                })
                .catch((error) => {
                    window.location.href = window.location.pathname + '?status=dataerror';
                    console.error('Error:', error);
                });
        }
    </script>
    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>