<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard</title>



    <?php
    include_once '../components/manager-dashboard-top.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-5">
        <div class="d-flex ">
            <div class="col-4 border p-4 rounded-4 me-5">
                <h3 class="mb-3">01st Dispatch</h3>
                <div class="d-flex justify-content-between">
                    <h5>Total Request</h5>
                    <h5>45</h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5>15-01-2025</h5>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Home Requests</h5>
                    <h5>20</h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Industrial Requests</h5>
                    <h5>25</h5>
                </div>
                <div class="btn btn-primary w-100 mt-3">Request to Head Office</div>
            </div>
            <div class="col-4 border p-4 rounded-4 me-5">
                <h3 class="mb-3">01st Dispatch</h3>
                <div class="d-flex justify-content-between">
                    <h5>Total Request</h5>
                    <h5>45</h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Delivery Date</h5>
                    <h5>15-01-2025</h5>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Home Requests</h5>
                    <h5>20</h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Industrial Requests</h5>
                    <h5>25</h5>
                </div>
                <div class="btn btn-primary w-100 mt-3">Request to Head Office</div>
            </div>
        </div>

        <div class="table-responsive p-3 border mt-5">
            <table style="width:100%" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Count</th>
                        <th scope="col">Requested Date</th>
                        <th scope="col">Scheduled Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Home</th>
                        <th scope="col">Industry</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example data, replace with your actual data retrieval logic
                    $crequests = [
                        ['id' => 1, 'count' => 45, 'requested_date' => '2025-01-01', 'scheduled_date' => '2025-01-15', 'status' => 'Pending', 'home' => 20, 'industry' => 25],
                        ['id' => 2, 'count' => 30, 'requested_date' => '2025-01-02', 'scheduled_date' => '2025-01-16', 'status' => 'Completed', 'home' => 10, 'industry' => 20],
                        ['id' => 3, 'count' => 20, 'requested_date' => '2025-01-03', 'scheduled_date' => '2025-01-17', 'status' => 'Pending', 'home' => 5, 'industry' => 15],
                        ['id' => 4, 'count' => 15, 'requested_date' => '2025-01-04', 'scheduled_date' => '2025-01-18', 'status' => 'Completed', 'home' => 7, 'industry' => 8],
                        ['id' => 5, 'count' => 40, 'requested_date' => '2025-01-05', 'scheduled_date' => '2025-01-19', 'status' => 'Pending', 'home' => 20, 'industry' => 20]
                    ];

                    foreach ($crequests as $request) {
                        echo "<tr>";
                        echo "<td>{$request['id']}</td>";
                        echo "<td>{$request['count']}</td>";
                        echo "<td>{$request['requested_date']}</td>";
                        echo "<td>{$request['scheduled_date']}</td>";
                        echo "<td>{$request['status']}</td>";
                        echo "<td>{$request['home']}</td>";
                        echo "<td>{$request['industry']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>