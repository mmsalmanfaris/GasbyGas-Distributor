<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cylinder Requests</title>

    <?php
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== false) {
        header('Location: ../login.php'); // Redirect to login if not manager
        exit;
    }

    // $userName = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
    $user_id = $_SESSION['user_id']; // Get the user id from session
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
        if ($filteredCrequests) {
            foreach ($filteredCrequests as $request) {
                if ($request['panel'] === $panel) {
                    $total++;
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

        return ['total' => $total, 'home' => $home, 'industrial' => $industrial];
    }

    // Calculate counts for Panel A
    $dispatchA = calculateRequestCounts($filteredCrequests, 'A', $consumers);

    // Calculate counts for Panel B
    $dispatchB = calculateRequestCounts($filteredCrequests, 'B', $consumers);


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
                <!-- Hardcoded delivery date-->
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5>15-01-2025</h5>
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
                <div class="btn btn-primary w-100 mt-3">Request to Head Office</div>
            </div>
            <div class="col-4 border p-4 rounded-4 me-5">
                <h3 class="mb-3">2nd Dispatch</h3>
                <div class="d-flex justify-content-between">
                    <h5>Total Request</h5>
                    <h5><?php echo $dispatchB['total']; ?></h5>
                </div>
                <!-- Hardcoded delivery date-->
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5>15-01-2025</h5>
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
                <div class="btn btn-primary w-100 mt-3">Request to Head Office</div>
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


    <!-- <script>
        console.log("User Name: ", "<?php echo $userName; ?>");
        <?php if ($userRecord) { ?>
            const userDetails = <?php echo json_encode($userRecord); ?>;
            console.log('User Details:', userDetails);
        <?php } ?>
    </script> -->


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>