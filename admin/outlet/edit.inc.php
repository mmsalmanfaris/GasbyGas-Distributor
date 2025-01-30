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