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
    $selectedPanel = isset($_GET['panel']) ? $_GET['panel'] : 'all';
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
                    $request['delivery_status'] === 'pending' &&
                    ($selectedPanel === 'all' || $request['panel'] === $selectedPanel)
                ) {
                    $filteredCrequests[] = $request;
                }
            }
        }
    }
    ?>
</head>

<body>
    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 p-5">
        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Count</h5>
                <h4><?php echo count($filteredCrequests); ?></h4>
            </div>
        </div>
        <div class="col-3 me-auto mt-4">
            <label for="panelSelect" class="form-label fw-bold">Filter by Panel:</label>
            <select class="form-select" id="panelSelect" onchange="window.location.href = '?panel=' + this.value;">
                <option value="all" <?php echo ($selectedPanel === 'all' ? 'selected' : ''); ?>>All Panels</option>
                <option value="A" <?php echo ($selectedPanel === 'A' ? 'selected' : ''); ?>>Panel A</option>
                <option value="B" <?php echo ($selectedPanel === 'B' ? 'selected' : ''); ?>>Panel B</option>
            </select>
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
                        foreach ($filteredCrequests as $requestId => $request) {
                            $consumerName = 'N/A';
                            if (isset($consumers[$request['consumer_id']])) {
                                $consumerName = htmlspecialchars($consumers[$request['consumer_id']]['name']);
                            }
                            echo '<tr data-request-id="' . htmlspecialchars($requestId) . '" data-outlet-id="' . htmlspecialchars($request['outlet_id']) . '" data-consumer-id="' . htmlspecialchars($request['consumer_id']) . '" data-quantity="' . htmlspecialchars($request['quantity']) . '" data-panel="' . htmlspecialchars($request['panel']) . '">';
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
        <button id="reallocateBtn" class="btn btn-primary mt-5" <?php if ($selectedPanel === 'all' || empty($filteredCrequests)) echo 'disabled'; ?>>
            Reallocate Tokens
        </button>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();

            document.getElementById('reallocateBtn').addEventListener('click', function() {
                console.log("Reallocate button clicked!");
                const tableRows = document.querySelectorAll('#example tbody tr');
                const cancellations = [];
                const today = new Date().toISOString().slice(0, 10);
                const selectedPanel = document.getElementById('panelSelect').value;

                tableRows.forEach(row => {
                    const outlet_id = row.getAttribute('data-outlet-id');
                    const consumer_id = row.getAttribute('data-consumer-id');
                    const quantity = row.getAttribute('data-quantity');
                    const panel = row.getAttribute('data-panel');
                    if (outlet_id && consumer_id && quantity && (selectedPanel === 'all' || panel === selectedPanel)) {
                        cancellations.push({
                            outlet_id: outlet_id,
                            consumer_id: consumer_id,
                            quantity: quantity,
                            date: today,
                        });
                    } else {
                        console.log("Missing required attributes or panel mismatch in row:", row);
                    }
                });

                console.log("Data to send:", cancellations);

                fetch('../includes/addCancellations.inc.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            cancellations: cancellations,
                            selectedPanel: selectedPanel
                        })
                    })
                    .then(response => {
                        console.log("Raw Response:", response);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Response data:", data);
                        if (data.status === 'success') {
                            window.location.href = '?status=datasuccess';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Reallocation Failed',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            });
        });
    </script>

    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>
</body>

</html>