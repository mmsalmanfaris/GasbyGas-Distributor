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

    // ... [previous PHP code remains the same] ...
    ?>
</head>

<body>
    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Pending Handovers</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkReminderModal">
                Send Bulk Reminders
            </button>
        </div>

        <!-- Bulk Reminder Modal -->
        <div class="modal fade" id="bulkReminderModal" tabindex="-1" aria-labelledby="bulkReminderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkReminderModalLabel">Send Bulk Reminders</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bulkReminderForm">
                            <div class="mb-3">
                                <label for="reminderMessage" class="form-label">Message Content</label>
                                <textarea class="form-control" id="reminderMessage" rows="4" required>
Dear Customer,

This is a reminder to submit your empty cylinder and complete payment for your gas request. 
Please visit our outlet before [DATE] to ensure continuous service.

Thank you,
GasByGas Team
                                </textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="confirmBulkReminders()">Send to All</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ... [rest of the table code remains the same] ... -->

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

        function confirmBulkReminders() {
            const message = document.getElementById('reminderMessage').value;
            const consumerIds = <?= json_encode(array_column($pendingRequests, 'consumer_id')) ?>;
            
            if (!message.trim()) {
                showMessage('danger', 'Please enter a reminder message');
                return;
            }

            fetch('../includes/sendBulkReminders.inc.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    consumer_ids: consumerIds,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showMessage('success', 'Bulk reminders sent successfully!');
                    $('#bulkReminderModal').modal('hide');
                } else {
                    showMessage('danger', 'Error sending bulk reminders: ' + data.message);
                }
            })
            .catch(error => {
                showMessage('danger', 'Network error: ' + error);
            });
        }

        // ... [rest of the JavaScript code remains the same] ...
    </script>

    <?php
    include_once '../components/manager-dashboard-down.php';
    message_success();
    ?>
</body>
</html>