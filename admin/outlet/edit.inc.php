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
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Outlet</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-black p-5">
                                    <form action="../includes/updateOutlet.inc.php" method="post" class="row g-3 needs-validation"
                                        novalidate>
                                        <input type="hidden" name="outlet_id" value="' . htmlspecialchars($outletId) . '">
<<<<<<< HEAD
                                        <!-- Row for Name and Email -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">District:</label>
                                                <input type="text" class="form-control form-control-lg" name="district" id="name" value="' . htmlspecialchars($outlet['district']) . '" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Outlet Name:</label>
                                                <input type="email" class="form-control form-control-lg" name="name" id="email" value="' . htmlspecialchars($outlet['name']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Row for Password and Contact -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="contact" class="form-label">Available Stock:</label>
                                                <input type="text" class="form-control form-control-lg" name="stock" id="contact" value="' . htmlspecialchars($outlet['stock']) . '" required>
                                            </div>
                                             <div class="col-md-6">
                                                <label for="nic" class="form-label">Outlet Town:</label>
                                                <input type="text" class="form-control form-control-lg" name="town" id="nic" value="' . htmlspecialchars($outlet['town']) . '" required>
                                            </div>
=======
                                        
                                        <!-- District Selection -->
                                        <div class="mb-3">
                                            <label for="district" class="form-label">District:</label>
                                            <select class="form-select form-control-lg" name="district" id="district" required>
                                                <option value="ampara"' . ($outlet['district'] == 'ampara' ? ' selected' : '') . '>Ampara</option>
                                                <option value="batticaloa"' . ($outlet['district'] == 'batticaloa' ? ' selected' : '') . '>Batticaloa</option>
                                                <!-- Add other district options as needed -->
                                            </select>
                                        </div>

                                        <!-- Name and Town -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Name:</label>
                                                <input type="text" class="form-control form-control-lg" name="name" id="name" 
                                                    value="' . htmlspecialchars($outlet['name']) . '" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="town" class="form-label">Town:</label>
                                                <input type="text" class="form-control form-control-lg" name="town" id="town" 
                                                    value="' . htmlspecialchars($outlet['town']) . '" required>
                                            </div>
                                        </div>

                                        <!-- Stock -->
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock:</label>
                                            <input type="number" class="form-control form-control-lg" name="stock" id="stock" 
                                                value="' . htmlspecialchars($outlet['stock']) . '" required>
>>>>>>> 545b15317edc45004bec0b3c174eec46d4fa2393
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-lg w-100">Update Outlet</button>
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