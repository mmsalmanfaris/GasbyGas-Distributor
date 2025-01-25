<!-- Add Dispatch Modal Start -->
<div class="modal fade bd-example-modal-lg" id="addDispatchModal" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-black p-5">
        <form action="../includes/addDispatch.inc.php" method="post" class="row g-3 needs-validation"
          novalidate>
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="outlet_id" class="form-label">Outlet:</label>
              <select class="form-select form-control-lg" name="outlet_id" id="outlet_id" required>
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
            <div class="col-md-6">
              <label for="request_date" class="form-label">Request Date:</label>
              <input type="date" class="form-control form-control-lg" name="request_date"
                id="request_date" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="sdelivery" class="form-label">Scheduled Delivery Date:</label>
              <input type="date" class="form-control form-control-lg" name="sdelivery" id="sdelivery"
                required>
            </div>
            <div class="col-md-6">
              <label for="edelivery" class="form-label">Expected Delivery Date:</label>
              <input type="date" class="form-control form-control-lg" name="edelivery" id="edelivery"
                required>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-6">
              <label for="quantity" class="form-label">Quantity:</label>
              <input type="number" class="form-control form-control-lg" name="quantity" id="quantity"
                required>
            </div>
            <div class="col-md-6">
              <label for="status" class="form-label">Status:</label>
              <select class="form-select form-control-lg" name="status" id="status" required>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="dispatched">Dispatched</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-lg w-100">Add Schedule</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Add Dispatch Modal End -->