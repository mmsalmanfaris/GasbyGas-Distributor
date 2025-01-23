<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>



    <?php
    include_once '../components/manager-dashboard-top.php';

    // include_once '../../includes/register.inc.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <!-- The Modal Start -->
        <div class="modal fade bd-example-modal-lg" id="addOutletModal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-black p-5">
                        <form action="./includes/addOutlet.inc.php" method="post" class="row g-3 needs-validation"
                            novalidate>
                            <!-- Row for Name and Email -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control form-control-lg" name="name" id="name"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control form-control-lg" name="email" id="email"
                                        required>
                                </div>
                            </div>

                            <!-- Row for Password and Contact -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control form-control-lg" name="password"
                                        id="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact" class="form-label">Contact:</label>
                                    <input type="text" class="form-control form-control-lg" name="contact" id="contact"
                                        required>
                                </div>
                            </div>

                            <!-- Row for NIC and Admin -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="nic" class="form-label">NIC:</label>
                                    <input type="text" class="form-control form-control-lg" name="nic" id="nic"
                                        required>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="isAdmin" id="isAdmin">
                                        <label class="form-check-label" for="isAdmin">Is Admin</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- The Modal End -->


        <div class="d-flex mt-5">
            <div class="col-3 border p-4 me-3">
                <h5>Total Users</h5>
                <h4>10</h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Admin Cout</h5>
                <h4>15-01-2025</h4>
            </div>
            <div class="col-3 border p-4 me-3">
                <h5>Manager Count</h5>
                <h4>15-01-2025</h4>
            </div>
            <div class="col-2 btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#addOutletModal">
                Add New
            </div>
        </div>


        <div class="table-responsive mt-5 px-2 border">
            <table id="example" class=" p-2 display nowrap" style="width:100%" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Manager</th>
                        <th scope="col">Outlet Name</th>
                        <th scope="col">District</th>
                        <th scope="col">Town</th>
                        <th scope="col">Stocks</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example data, replace with your actual data retrieval logic
                    $outlets = [
                        ['id' => 1, 'name' => 'John Doe', 'outlet' => 'Kalmunai Distributors', 'district' => 'Ampara', 'town' => 'Kalmunai', 'stock' => '25'],
                        ['id' => 2, 'name' => 'Jane Smith', 'outlet' => 'Batticaloa Distributors', 'district' => 'Batticaloa', 'town' => 'Town B', 'stock' => '30'],
                        ['id' => 3, 'name' => 'Michael Johnson', 'outlet' => 'Trincomale Distributors', 'district' => 'Trincomale', 'town' => 'Town C', 'stock' => '20'],
                        ['id' => 4, 'name' => 'Emily Davis', 'outlet' => 'Ampara Distributors', 'district' => 'Ampara', 'town' => 'Town D', 'stock' => '15'],
                        ['id' => 5, 'name' => 'Chris Brown', 'outlet' => 'Batticaloa Distributors', 'district' => 'Batticaloa', 'town' => 'Town E', 'stock' => '40'],
                        ['id' => 6, 'name' => 'Patricia Miller', 'outlet' => 'Trincomale Distributors', 'district' => 'Trincomale', 'town' => 'Town F', 'stock' => '35'],
                        ['id' => 7, 'name' => 'Robert Wilson', 'outlet' => 'Ampara Distributors', 'district' => 'Ampara', 'town' => 'Town G', 'stock' => '50'],
                        ['id' => 8, 'name' => 'Linda Moore', 'outlet' => 'Batticaloa Distributors', 'district' => 'Batticaloa', 'town' => 'Town H', 'stock' => '45'],
                        ['id' => 9, 'name' => 'James Taylor', 'outlet' => 'Trincomale Distributors', 'district' => 'Trincomale', 'town' => 'Town I', 'stock' => '60'],
                        ['id' => 10, 'name' => 'Barbara Anderson', 'outlet' => 'Ampara Distributors', 'district' => 'Ampara', 'town' => 'Town J', 'stock' => '55'],
                        ['id' => 11, 'name' => 'Steven Harris', 'outlet' => 'Batticaloa Distributors', 'district' => 'Batticaloa', 'town' => 'Town K', 'stock' => '70'],
                        ['id' => 12, 'name' => 'Nancy Clark', 'outlet' => 'Trincomale Distributors', 'district' => 'Trincomale', 'town' => 'Town L', 'stock' => '65'],
                        ['id' => 13, 'name' => 'Kevin Lewis', 'outlet' => 'Ampara Distributors', 'district' => 'Ampara', 'town' => 'Town M', 'stock' => '80'],
                        ['id' => 14, 'name' => 'Karen Walker', 'outlet' => 'Batticaloa Distributors', 'district' => 'Batticaloa', 'town' => 'Town N', 'stock' => '75'],
                        ['id' => 15, 'name' => 'Brian Hall', 'outlet' => 'Trincomale Distributors', 'district' => 'Trincomale', 'town' => 'Town O', 'stock' => '90'],
                    ];


                    foreach ($outlets as $outlet) {
                        echo "<tr>";
                        echo "<td>{$outlet['id']}</td>";
                        echo "<td>{$outlet['name']}</td>";
                        echo "<td>{$outlet['outlet']}</td>";
                        echo "<td>{$outlet['district']}</td>";
                        echo "<td>{$outlet['town']}</td>";
                        echo "<td>{$outlet['stock']}</td>";
                        echo "<td>
                                <a href='edit.php?id={$outlet['id']}' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete.php?id={$outlet['id']}' class='btn btn-sm btn-danger'>Delete</a>
                              </td>";
                        echo "</tr>";
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
    </script>



    <?php
    // Sweet Message
    output_message();

    include_once '../components/manager-dashboard-down.php';
    ?>