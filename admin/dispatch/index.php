<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gasbygas</title>



    <?php
    include_once '../components/manager-dashboard-top.php';
    require __DIR__ . '/../vendor/autoload.php';

    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dispatch Schedule</h1>
    </div>
    <div class="table-responsive">
        <table id="example" class="display nowrap" style="width:100%" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Outlet ID</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Request Date</th>
                    <th scope="col">Expected Delivery</th>
                    <th scope="col">Scheduled Delivery</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include Firebase connection
                include_once '../../includes/firebase.php';

                // Firebase Realtime Database URL
                $firebase_url = $firebase->getDatabaseUrl() . '/dispatch_schedules.json';

                // Initialize cURL
                $ch = curl_init();

                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $firebase_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute the request and get the response
                $response = curl_exec($ch);

                // Check for errors
                if ($response === false) {
                    echo '<tr><td colspan="9">Error: ' . curl_error($ch) . '</td></tr>';
                } else {
                    // Decode the JSON response
                    $dispatch_schedules = json_decode($response, true);

                    // Check if decoding was successful and the data is an array
                    if (is_array($dispatch_schedules)) {
                        // Loop through the dispatch schedules and display them in the table
                        foreach ($dispatch_schedules as $id => $schedule) {
                            echo "<tr>";
                            echo "<td>{$id}</td>";
                            echo "<td>{$schedule['outlet_id']}</td>";
                            echo "<td>{$schedule['quantity']}</td>";
                            echo "<td>{$schedule['request_date']}</td>";
                            echo "<td>{$schedule['edelivery']}</td>";
                            echo "<td>{$schedule['sdelivery']}</td>";
                            echo "<td>{$schedule['status']}</td>";
                            echo "<td>{$schedule['created_at']}</td>";
                            echo "<td>
                                    <a href='edit.php?id={$id}' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='delete.php?id={$id}' class='btn btn-sm btn-danger'>Delete</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo '<tr><td colspan="9">No data found or invalid response from Firebase.</td></tr>';
                    }
                }

                // Close the cURL session
                curl_close($ch);
                ?>
            </tbody>
        </table>
    </div>
</main>


        
    


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>