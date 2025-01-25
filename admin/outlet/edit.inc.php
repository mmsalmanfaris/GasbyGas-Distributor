<?php

include_once '../includes/firebase.php';
include_once '../../components/header-links.php';


if (isset($_GET['outlet_id'])) {
    $outletId = $_GET['outlet_id'];
    $outlet = $database->getReference("outlets/{$outletId}")->getValue();
    if ($outlet) {
        echo '<div class="modal fade bd-example-modal-lg" id="editUserModal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-black p-5">
                                    <form action="../includes/updateUser.inc.php" method="post" class="row g-3 needs-validation"
                                        novalidate>
                                        <input type="hidden" name="user_id" value="' . htmlspecialchars($outletId) . '">
                                        <!-- Row for Name and Email -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control form-control-lg" name="name" id="name" value="' . htmlspecialchars($outlet['district']) . '" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email:</label>
                                                <input type="email" class="form-control form-control-lg" name="email" id="email" value="' . htmlspecialchars($outlet['name']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Row for Password and Contact -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password:</label>
                                                <input type="password" class="form-control form-control-lg" name="password" id="password">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contact" class="form-label">Contact:</label>
                                                <input type="text" class="form-control form-control-lg" name="contact" id="contact" value="' . htmlspecialchars($outlet['stock']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Row for NIC and Admin -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="nic" class="form-label">NIC:</label>
                                                <input type="text" class="form-control form-control-lg" name="nic" id="nic" value="' . htmlspecialchars($outlet['town']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
    }
}

?>



<!-- The Modal End -->