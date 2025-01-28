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
            // Filter by outlet and pending status
            if ($request['outlet_id'] === $user_outlet_id && 
                ($request['empty_cylinder'] === 'pending' || 
                 $request['payment_status'] === 'pending') &&
                $request['delivery_status'] === 'pending') {
                
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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Pending Handovers</h1>
            <button class="btn btn-primary" onclick="sendBulkReminders()" data-bs-toggle="modal" data-bs-target="#bulkReminderModal">
                Send Reminders
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
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingRequests as $requestId => $request): ?>
                        <tr>
                            <td><?= htmlspecialchars(substr($requestId, 0, 8)) ?></td>
                            <td><?= htmlspecialchars($request['consumer']['name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($request['consumer']['contact'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($request['consumer']['category'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge <?= $request['empty_cylinder'] === 'received' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($request['empty_cylinder']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $request['payment_status'] === 'completed' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ucfirst($request['payment_status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($request['sdelivery'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" 
                                        onclick="sendReminder('<?= $request['consumer_id'] ?>')">
                                    <i class="bi bi-bell"></i> Remind
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Message Box Below the Table -->
        <div class="mt-5">
            <h3>Send Custom Message</h3>
            <textarea id="customMessage" class="form-control mb-3" rows="4" placeholder="Write your message here..."></textarea>
            <button class="btn btn-success" onclick="sendCustomMessage()">Send Message</button>
        </div>
    </main>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#pendingTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 1 },
                    { responsivePriority: 2, targets: 7 }
                ]
            });
        });

        function sendReminder(consumerId) {
            const message = `Dear Customer,\n\nThis is a reminder to submit your empty cylinder and complete payment for your gas request. Please visit our outlet before ${new Date(Date.now() + 3 * 24 * 60 * 60 * 1000).toLocaleDateString()} to ensure continuous service.\n\nThank you,\nGasByGas Team`;

            fetch('../includes/sendReminder.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    consumer_id: consumerId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showMessage('success', 'Reminder sent successfully!');
                } else {
                    showMessage('danger', 'Error: ' + data.message);
                }
            })
            .catch(error => {
                showMessage('danger', 'Failed to send reminder: ' + error.message);
            });
        }

        function sendCustomMessage() {
            const message = document.getElementById('customMessage').value;
            if (!message.trim()) {
                alert('Please enter a message before sending.');
                return;
            }

            fetch('../includes/sendBulkReminder.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    consumer_ids: [<?php echo implode(',', array_map(fn($req) => "'" . $req['consumer_id'] . "'", $pendingRequests)); ?>],
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Message sent successfully!');
                } else {
                    showMessage('danger', 'Error: ' + data.message);
                }
            })
            .catch(error => {
                showMessage('danger', 'Failed to send message: ' + error.message);
            });
        }

        function showMessage(type, message) {
            alert(`[${type.toUpperCase()}] ${message}`);
        }
    </script>

    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>
</body>

</html>
