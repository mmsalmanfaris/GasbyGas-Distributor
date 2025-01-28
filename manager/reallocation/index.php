<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reallocation - Manager</title>

    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    require '../includes/firebase.php';


    $userRecord = $database->getReference("users/{$user_id}")->getValue();
    $user_outlet_id = $userRecord['outlet_id'] ?? null;

    $filteredCrequests = [];

    if ($user_outlet_id) {
        $crequests = $database->getReference('crequests')->getValue();
        $consumers = $database->getReference('consumers')->getValue();
        if ($crequests) {
            foreach ($crequests as $requestId => $request) {
                if (
                    $request['outlet_id'] == $user_outlet_id &&
                    $request['type'] === 'home' &&
                    $request['empty_cylinder'] === 'pending' &&
                    $request['payment_status'] === 'pending' &&
                    $request['delivery_status'] === 'pending'
                ) {
                    $filteredCrequests[] = $request;
                }
            }
        }
    }


    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-5">
        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Count</h5>
                <h4><?php echo count($filteredCrequests); ?></h4>
            </div>

        </div>
        <div class="table-responsive p-3 border mt-5">
            <table id="example" style="width:100%" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Consumer Name</th>
                        <th scope="col">Panel</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Empty Cylinder</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Delivery Status</th>
                        <th scope="col">Scheduled Delivery</th>
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
                            echo '<tr>';
                            echo '<td>' . $consumerName . '</td>';
                            echo '<td>' . htmlspecialchars($request['panel']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['quantity']) . '</td>';
                            echo '<td>' . ($request['empty_cylinder']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['payment_status']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['delivery_status']) . '</td>';
                            echo '<td>' . htmlspecialchars($request['sdelivery']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No matching requests found for your outlet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="btn btn-primary mt-5">
            Reallocate Tokens
        </div>

    </main>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>


    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>