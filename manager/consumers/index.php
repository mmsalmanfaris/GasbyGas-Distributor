<?php
// Include the Firebase initialization file
require '../../admin/includes/firebase.php'; // Update the path as needed

// Include any shared components or configuration
include_once '../components/manager-dashboard-top.php';
include_once '../../output/message.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumers</title>
    <script>
        function filterConsumers(type) {
            const rows = document.querySelectorAll(".consumer-row");
            rows.forEach(row => {
                if (type === "all" || row.dataset.type === type) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
    
</head>
<body>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <?php
    // Retrieve consumer data from Firebase
    try {
        $consumers = $database->getReference('consumers')->getValue();
        if (!is_array($consumers)) {
            $consumers = [];
        }
    } catch (Exception $e) {
        echo "Error retrieving consumers: " . $e->getMessage();
        exit;
    }

    // Separate individual and business consumers
    $individualCount = 0;
    $businessCount = 0;

    foreach ($consumers as $consumer) {
        if (isset($consumer['rnumber'])) {
            $businessCount++;
        } else {
            $individualCount++;
        }
    }
    ?>

    <!-- Dashboard Summary -->
    <div class="d-flex mt-5">
        <div class="col-3 border p-4 me-3">
            <h5>Total Consumers</h5>
            <h4><?php echo count($consumers); ?></h4>
        </div>
        <div class="col-3 border p-4 me-3">
            <h5>Individual Consumers</h5>
            <h4><?php echo $individualCount; ?></h4>
        </div>
        <div class="col-3 border p-4 me-3">
            <h5>Business Consumers</h5>
            <h4><?php echo $businessCount; ?></h4>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-center">
            <button class="btn btn-primary btn-sm h-100 w-100 fs-5" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add New Consumer
            </button>
        </div>
    </div>

    <!-- Filter Dropdown -->
    <div class="mt-4 mb-3 ">
        <label for="consumerFilter" class="form-label ">Filter Consumers:</label>
        <select id="consumerFilter" class="form-select w-50" onchange="filterConsumers(this.value)">
            <option value="all">All Consumers</option>
            <option value="individual">Individual Consumers</option>
            <option value="business">Business Consumers</option>
        </select>
    </div>

    <!-- Consumers Table -->
    <h3 class="mb-3">Consumers</h3>
    <div class="table-responsive">
        <table  id="example" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th id="number">NIC</th>
                    <th id="license">Rnumber</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>District</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($consumers as $id => $consumer) {
                    $type = isset($consumer['rnumber']) ? 'business' : 'individual';
                    echo "<tr class='consumer-row' data-type='{$type}'>
                        <td>" . htmlspecialchars($consumer['name'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['nic'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['rnumber'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['contact'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['email'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['address'] ?? '') . "</td>
                        <td>" . htmlspecialchars($consumer['district'] ?? '') . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<?php
    include_once 'addconsumer.php';
    message_success();
    include_once '../components/manager-dashboard-down.php';
    ?>
</body>
</html>
