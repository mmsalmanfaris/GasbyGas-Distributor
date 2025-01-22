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
            <div class="col-3 me-3">
                <label for="issueMonth">Select Issue Month:</label>
                <select class="form-control" id="issueMonth" name="issueMonth">
                    <?php
                    // Example months, replace with your actual data retrieval logic
                    $issueMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    $currentMonth = date('F'); // Get current month
                    
                    foreach ($issueMonths as $month) {
                        $selected = ($month == $currentMonth) ? 'selected' : '';
                        echo "<option value=\"$month\" $selected>$month</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-3 me-3">
                <label for="issuePanel">Select Issue Panel:</label>
                <select class="form-control" id="issuePanel" name="issuePanel">
                    <?php
                    // Example panels, replace with your actual data retrieval logic
                    $issuePanels = ['Panel 1', 'Panel 2'];
                    $defaultPanel = 'Panel 1'; // Set default panel
                    
                    foreach ($issuePanels as $panel) {
                        $selected = ($panel == $defaultPanel) ? 'selected' : '';
                        echo "<option value=\"$panel\" $selected>$panel</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" id="searchButton">Search</button>
            </div>
        </div>


        <div class="table-responsive p-3 border mt-5">
            <table style="width:100%" class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Category</th>
                        <th scope="col">Empty</th>
                        <th scope="col">Payment</th>
                        <th scope="col">Issue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example data, replace with your actual data retrieval logic
                    $crequests = [
                        ['id' => 1, 'name' => 'John Doe', 'contact' => '1234567890', 'category' => 'Home', 'empty' => 'No', 'payment' => 'Paid', 'issue' => '2025-01-01'],
                        ['id' => 2, 'name' => 'Jane Smith', 'contact' => '0987654321', 'category' => 'Industry', 'empty' => 'Yes', 'payment' => 'Pending', 'issue' => '2025-01-02'],
                        ['id' => 3, 'name' => 'Alice Johnson', 'contact' => '1122334455', 'category' => 'Home', 'empty' => 'No', 'payment' => 'Paid', 'issue' => '2025-01-03'],
                        ['id' => 4, 'name' => 'Bob Brown', 'contact' => '5566778899', 'category' => 'Industry', 'empty' => 'Yes', 'payment' => 'Pending', 'issue' => '2025-01-04'],
                        ['id' => 5, 'name' => 'Charlie Davis', 'contact' => '6677889900', 'category' => 'Home', 'empty' => 'No', 'payment' => 'Paid', 'issue' => '2025-01-05']
                    ];

                    foreach ($crequests as $request) {
                        echo "<tr>";
                        echo "<td>{$request['id']}</td>";
                        echo "<td>{$request['name']}</td>";
                        echo "<td>{$request['contact']}</td>";
                        echo "<td>{$request['category']}</td>";
                        echo "<td>{$request['empty']}</td>";
                        echo "<td>{$request['payment']}</td>";
                        echo "<td>{$request['issue']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="btn btn-success mt-5">Send SMS Notification</div>

    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>