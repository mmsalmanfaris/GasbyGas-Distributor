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
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Count</h5>
                <h4>10</h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Issue Date</h5>
                <h4>15-01-2025</h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Delivery Date</h5>
                <h4>15-01-2025</h4>
            </div>
        </div>
        <div class="table-responsive p-3 border mt-5">
            <table style="width:100%" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">User Id</th>
                        <th scope="col">Category</th>
                        <th scope="col">Delivery Date</th>
                        <th scope="col">Total</th>
                        <th scope="col">Empty</th>
                        <th scope="col">Issue Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example data, replace with your actual data retrieval logic
                    $crequests = [
                        ['id' => 1, 'user_id' => 101, 'category' => 'Home', 'delivery_date' => '2025-01-01', 'total' => 1, 'empty' => 'Null', 'issue_date' => '2025-01-01'],
                        ['id' => 2, 'user_id' => 102, 'category' => 'Industry', 'delivery_date' => '2025-01-02', 'total' => 3, 'empty' => 'Null', 'issue_date' => '2025-01-02'],
                        ['id' => 3, 'user_id' => 103, 'category' => 'Home', 'delivery_date' => '2025-01-03', 'total' => 2, 'empty' => 'Null', 'issue_date' => '2025-01-03'],
                        ['id' => 4, 'user_id' => 104, 'category' => 'Industry', 'delivery_date' => '2025-01-04', 'total' => 4, 'empty' => 'Null', 'issue_date' => '2025-01-04'],
                        ['id' => 5, 'user_id' => 105, 'category' => 'Home', 'delivery_date' => '2025-01-05', 'total' => 12, 'empty' => 'Null', 'issue_date' => '2025-01-05']
                    ];

                    foreach ($crequests as $request) {
                        echo "<tr>";
                        echo "<td>{$request['id']}</td>";
                        echo "<td>{$request['user_id']}</td>";
                        echo "<td>{$request['category']}</td>";
                        echo "<td>{$request['delivery_date']}</td>";
                        echo "<td>{$request['total']}</td>";
                        echo "<td>{$request['empty']}</td>";
                        echo "<td>{$request['issue_date']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="btn btn-primary mt-5">
            Reallocate Tokens
        </div>

    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>