<!-- Add Outlet Modal -->
<div class="modal fade" id="addOutletModal" tabindex="-1" aria-labelledby="addOutletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOutletModalLabel">Add New Outlet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../includes/outletRegister.inc.php" method="post" class="row g-3 needs-validation"
                    novalidate>
                    <!-- District Selection -->
                    <div class="mb-3">
                        <label for="district" class="form-label">District:</label>
                        <select class="form-select form-control-lg" name="district" id="district" required>
                            <option value="" disabled selected>Select District</option>
                            <option value="ampara">Ampara</option>
                            <option value="batticaloa">Batticaloa</option>
                            <option value="trincomale">Trincomale</option>
                            <option value="anuradhapura">Anuradhapura</option>
                            <option value="badulla">Badulla</option>
                            <option value="colombo">Colombo</option>
                            <option value="galle">Galle</option>
                            <option value="gampaha">Gampaha</option>
                            <option value="hambantota">Hambantota</option>
                            <option value="jaffna">Jaffna</option>
                            <option value="kalutara">Kalutara</option>
                            <option value="kandy">Kandy</option>
                            <option value="kegalle">Kegalle</option>
                            <option value="kilinochchi">Kilinochchi</option>
                            <option value="kurunegala">Kurunegala</option>
                            <option value="mannar">Mannar</option>
                            <option value="matale">Matale</option>
                            <option value="matara">Matara</option>
                            <option value="monaragala">Monaragala</option>
                            <option value="mullaitivu">Mullaitivu</option>
                            <option value="nuwara-eliya">Nuwara Eliya</option>
                            <option value="polonnaruwa">Polonnaruwa</option>
                            <option value="puttalam">Puttalam</option>
                            <option value="ratnapura">Ratnapura</option>
                            <option value="vavuniya">Vavuniya</option>
                        </select>
                        <div class="invalid-feedback">Please select a district.</div>
                    </div>

                    <!-- Name and Town -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" required>
                            <div class="invalid-feedback">Please enter the outlet name.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="town" class="form-label">Town:</label>
                            <input type="text" class="form-control form-control-lg" name="town" id="town" required>
                            <div class="invalid-feedback">Please enter the town.</div>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock:</label>
                        <input type="number" class="form-control form-control-lg" name="stock" id="stock" min="0"
                            required>
                        <div class="invalid-feedback">Please enter the stock quantity.</div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg w-100">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>