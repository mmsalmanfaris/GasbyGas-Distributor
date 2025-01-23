<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>


    <?php
    include_once '../components/manager-dashboard-top.php';

    // <Output Messages
    include_once '../../output/message.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <?php
        $users = $database->getReference('users')->getValue();
        ?>

        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Users</h5>

                <h4><?php echo count($users); ?></h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Admin Counts</h5>
                <h4>
                    <?php
                    $adminCount = 0;
                    foreach ($users as $user) {
                        if (isset($user['is_admin']) && $user['is_admin'] == true) {
                            $adminCount++;
                        }
                    }
                    echo $adminCount;
                    ?>
                </h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Manager Counts</h5>
                <h4><?php
                $adminCount = 0;
                foreach ($users as $user) {
                    if (isset($user['is_admin']) && $user['is_admin'] == false) {
                        $adminCount++;
                    }
                }
                echo $adminCount;
                ?></h4>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-center"><button
                    class="btn btn-primary btn-sm h-100 w-100 fs-5" data-bs-toggle="modal"
                    data-bs-target="#addUserModel">
                    Add
                    New User </button></div>
        </div>


        <div class="table-responsive mt-5 px-2 border">
            <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>NIC</th>
                        <th>Is Admin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    if ($users) {
                        foreach ($users as $userId => $user) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($userId) . '</td>';
                            echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['contact']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['nic']) . '</td>';
                            echo '<td>' . ($user['is_admin'] ? 'Yes' : 'No') . '</td>';
                            echo "<td>

                            <a href='?user_id={$userId}' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='../includes/delete.inc.php?user_id={$userId}' class='btn btn-sm btn-danger'>Delete</a>
                                  </td>";
                            echo '</tr>';
                        }
                    } else {
                        echo 'No users found in the database.';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>


    <script>
        document.querySelector('.btn-primary').addEventListener('click', function () {
            var myModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            myModal.show();
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Check if the URL contains the `user_id` parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('user_id')) {
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
    // message_delete();
    message_success();


    include_once '../components/manager-dashboard-down.php';
    ?>