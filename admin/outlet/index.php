<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outlet Management - Admin</title>


    <?php
    include_once '../components/manager-dashboard-top.php';

    // <Output Messages
    include_once '../../output/message.php';
    ?>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php
        $outlets = $database->getReference('outlets')->getValue();
        $users = $database->getReference('users')->getValue();
        if (!is_array($outlets)) {
            $outlets = [];
        }
        ?>

        <!-- Dashboard Summary -->
        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3 card bg-light">
                <h5>Registered Outlets</h5>
                <h4><?php echo count($outlets); ?></h4>
            </div>
            <div class="col-3 border p-4 me-3 card bg-light">
                <h5>Outlet Managers</h5>
                <h4>
                    <?php
                    $managerCount = 0;
                    foreach ($users as $user) {
                        if ($user['is_admin'] == false) {
                            $managerCount++;
                        }
                    }
                    echo $managerCount;
                    ?>
                </h4>
            </div>
            <div class="col-3 border p-4 me-3 card bg-light">
                <h5>Total Stocks</h5>
                <h4>
                    <?php
                    $totalStock = 0;
                    foreach ($outlets as $outlet) {
                        if (isset($outlet['stock'])) {
                            $totalStock += (int) $outlet['stock'];
                        }
                    }
                    echo $totalStock;
                    ?>
                </h4>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center card">
                <button class="btn btn-primary btn-sm h-100 w-100 fs-5" data-bs-toggle="modal"
                    data-bs-target="#addOutletModal">
                    Add New Outlet
                </button>
            </div>
        </div>

        <!-- Outlets Table -->
        <div class="table-responsive mt-5 px-2 border card p-2">
            <table id="example" class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">District</th>
                        <th scope="col">Town</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    if ($outlets) {
                        foreach ($outlets as $outletId => $outlet) {
                            if (is_array($outlet)) {
                                echo '<tr>';
                                echo '<td>' . $index++ . '</td>';
                                echo '<td>' . htmlspecialchars($outlet['name']) . '</td>';
                                echo '<td>' . htmlspecialchars($outlet['district']) . '</td>';
                                echo '<td>' . htmlspecialchars($outlet['town']) . '</td>';
                                echo '<td>' . htmlspecialchars($outlet['stock']) . '</td>';
                                echo '<td>
                    <a href="?outlet_id=' . urlencode($outletId) . '" class="btn btn-warning btn-sm">Edit</a>
                   <a href="../includes/deleteOutlet.inc.php?outlet_id={$outlet_id}' . ' " class="btn btn-danger btn-sm">Delete</a>
                  </td>';
                                echo '</tr>';
                            }
                        }
                    } else {
                        echo '<tr><td colspan="6">No outlets found in the database.</td></tr>';
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </main>

    <script>
        document.querySelector('.btn-primary').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('addOutletModal'));
            myModal.show();
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Check if the URL contains the `user_id` parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('outlet_id')) {
                // Trigger the modal
                const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editModal.show();
            }
        });


    </script>

    <?php

    // Insert Update
    include_once 'edit.inc.php';
    include_once 'add.inc.php';


    // Output Message
    
    message_success();


    include_once '../components/manager-dashboard-down.php';
    ?>