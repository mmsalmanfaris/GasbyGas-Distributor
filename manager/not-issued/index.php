<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Handovers - Manager</title>

    <?php
    include_once '../components/manager-dashboard-top.php';
    include_once '../../output/message.php';
    require '../includes/firebase.php';

    // Get manager's outlet ID
    $userRecord = $database->getReference("users/{$user_id}")->getValue();
    $user_outlet_id = $userRecord['outlet_id'] ?? null;

    // Fetch crequests and consumers data
    $crequests = $database->getReference('crequests')->getValue();
    $consumers = $database->getReference('consumers')->getValue();

    $pendingRequests = [];
    if ($crequests && $user_outlet_id) {
        foreach ($crequests as $requestId => $request) {
            // Filter by outlet and received status
            if (
                $request['outlet_id'] === $user_outlet_id &&
                $request['empty_cylinder'] === 'received' &&
                $request['payment_status'] === 'received' &&
                $request['delivery_status'] === 'pending'
            ) {

                // Add consumer details
                if (isset($consumers[$request['consumer_id']])) {
                    $request['consumer'] = $consumers[$request['consumer_id']];
                    $pendingRequests[$requestId] = $request;
                }
            }
        }
    }
    ?>
</head>

<body>
    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Pending Handovers</h1>
            <button class="btn btn-primary" onclick="sendBulkReminders()">
                Send Bulk Reminders
            </button>
        </div>

        <div class="table-responsive mt-4">
            <table id="pendingTable" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Category</th>
                        <th scope="col">Empty Cylinder</th>
                        <th scope="col">Payment</th>
                        <th scope="col">Issue Date</th>
                        <th scope="col">Delivery Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRequests as $requestId => $request): ?>
                        <tr data-consumer-id="<?= htmlspecialchars($request['consumer_id']) ?>">
                            <td><?= htmlspecialchars(substr($requestId, 0, 8)) ?></td>
                            <td><?= htmlspecialchars($request['consumer']['name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($request['consumer']['contact'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($request['consumer']['category'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge bg-success <?= $request['empty_cylinder'] === 'received' ?>">
                                    <?= ucfirst($request['empty_cylinder']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success <?= $request['payment_status'] === 'received' ?>">
                                    <?= ucfirst($request['payment_status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($request['sdelivery'])) ?></td>
                            <td><span class="badge bg-danger">
                                    <?= ucfirst($request['delivery_status']) ?>
                                </span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </main>

    <script>
        // Initialize DataTable
        $(document).ready(function () {
            $('#pendingTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 7 }
                ]
            });
        });

        function sendBulkReminders() {
            let consumerIds = [];

            // Collect all consumers with pending delivery status
            document.querySelectorAll("#pendingTable tbody tr").forEach(row => {
                let deliveryStatus = row.querySelector("td:nth-child(8").innerText.trim().toLowerCase();
                let consumerId = row.dataset.consumerId;

                if (deliveryStatus === "pending" && consumerId) {
                    consumerIds.push(consumerId);
                }
            });

            if (consumerIds.length === 0) {
                showMessage('warning', 'No consumers with pending delivery status.');
                return;
            }

            // Prepare the message template
            const message = `Dear Consumer, \n\nThis is a reminder to collect your Cylinder. Please visit our outlet before ${new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toLocaleDateString()} to ensure continuous service.\n\nThank you.`;

            // Send request to backend
            fetch('../includes/sendReminder.inc.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ consumer_ids: consumerIds, message: message })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = window.location.pathname + '?status=smssuccess';
                    } else {
                        showMessage('danger', 'Error: ' + data.message);
                    }
                })
                .catch(error => {
                    showMessage('danger', 'Failed to send reminders: ' + error.message);
                });
        }

    </script>



    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>
</body>

</html>