<!-- The Modal Start -->
<div class="modal fade bd-example-modal-lg" id="addUserModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-black p-5">
                <form action="../includes/register.inc.php" method="post" class="row g-3 needs-validation"
                    novalidate>
                    <!-- Row for Name and Email -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control form-control-lg" name="email" id="email" required>
                        </div>
                    </div>

                    <!-- Row for Password and Contact -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control form-control-lg" name="password" id="password"
                                required>
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
                            <input type="text" class="form-control form-control-lg" name="nic" id="nic" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="isAdmin" id="isAdmin">
                                <label class="form-check-label" for="isAdmin">Is Admin</label>
                            </div>
                        </div>
                    </div>
                    <!-- Row for Outlet Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="outlet_id" class="form-label">Outlet:</label>
                            <select class="form-select form-control-lg" name="outlet_id" id="outlet_id">
                                <option value="" disabled selected>Select Outlet</option>
                                <?php
                                $outlets = $database->getReference('outlets')->getValue();
                                if ($outlets) {
                                    foreach ($outlets as $outletId => $outlet) {
                                        echo '<option value="' . htmlspecialchars($outlet['outlet_id']) . '">' . htmlspecialchars($outlet['name']) . ' - ' . htmlspecialchars($outlet['district']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
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