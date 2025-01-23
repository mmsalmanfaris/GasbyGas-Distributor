<?php

include_once '../firebase.php';
include_once '../../components/header-links.php';

// <!-- Edit Model -->
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $user = $database->getReference("users/{$userId}")->getValue();
    if ($user) {
        echo '<div class="modal fade bd-example-modal-lg" id="editUserModal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-black p-5">
                                    <form action="../../includes/update.inc.php" method="post" class="row g-3 needs-validation"
                                        novalidate>
                                        <input type="hidden" name="user_id" value="' . htmlspecialchars($userId) . '">
                                        <!-- Row for Name and Email -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control form-control-lg" name="name" id="name" value="' . htmlspecialchars($user['name']) . '" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email:</label>
                                                <input type="email" class="form-control form-control-lg" name="email" id="email" value="' . htmlspecialchars($user['email']) . '" required>
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
                                                <input type="text" class="form-control form-control-lg" name="contact" id="contact" value="' . htmlspecialchars($user['contact']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Row for NIC and Admin -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label for="nic" class="form-label">NIC:</label>
                                                <input type="text" class="form-control form-control-lg" name="nic" id="nic" value="' . htmlspecialchars($user['nic']) . '" required>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" name="isAdmin" id="isAdmin"' . ($user['is_admin'] ? ' checked' : '') . '>
                                                    <label class="form-check-label" for="isAdmin">Is Admin</label>
                                                </div>
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