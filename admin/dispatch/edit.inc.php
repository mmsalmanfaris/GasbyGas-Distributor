<?php

include_once '../includes/firebase.php';
include_once '../../components/header-links.php';


if (isset($_GET['schedule_id'])) {
  $scheduleId = $_GET['schedule_id'];
  $schedule = $database->getReference("dispatch_schedules/{$scheduleId}")->getValue();
  if ($schedule) {
    echo '<div class="modal fade bd-example-modal-lg" id="editDispatchModal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-black p-5">
                                    <form action="../includes/updateDispatch.inc.php" method="post" class="row g-3 needs-validation"
                                        novalidate>
                                        <input type="hidden" name="schedule_id" value="' . htmlspecialchars($scheduleId) . '">
                                        <div class="row mb-3">
                                             <div class="col-md-6">
                                                <label for="outlet_id" class="form-label">Outlet:</label>
                                                <select class="form-select form-control-lg" name="outlet_id" id="outlet_id" required>
                                                    <option value="" disabled >Select Outlet</option>';
    $outlets = $database->getReference('outlets')->getValue();
    if ($outlets) {
      foreach ($outlets as $outletId => $outlet) {
        $selected = ($outlet['outlet_id'] == $schedule['outlet_id']) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($outlet['outlet_id']) . '" ' . $selected . '>' . htmlspecialchars($outlet['name']) . ' - ' . htmlspecialchars($outlet['district']) . '</option>';
      }
    }
    echo '</select>
                                             </div>
                                            <div class="col-md-6">
                                                <label for="request_date" class="form-label">Request Date:</label>
                                                <input type="date" class="form-control form-control-lg" name="request_date" id="request_date" value="' . htmlspecialchars($schedule['request_date']) . '" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="sdelivery" class="form-label">Scheduled Delivery Date:</label>
                                                <input type="date" class="form-control form-control-lg" name="sdelivery" id="sdelivery"  value="' . htmlspecialchars($schedule['sdelivery']) . '"required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="edelivery" class="form-label">Expected Delivery Date:</label>
                                                <input type="date" class="form-control form-control-lg" name="edelivery" id="edelivery"  value="' . htmlspecialchars($schedule['edelivery']) . '"required>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                           <div class="col-md-6">
                                               <label for="quantity" class="form-label">Quantity:</label>
                                               <input type="number" class="form-control form-control-lg" name="quantity" id="quantity" value="' . htmlspecialchars($schedule['quantity']) . '" required>
                                           </div>
                                           <div class="col-md-6">
                                               <label for="status" class="form-label">Status:</label>
                                                <select class="form-select form-control-lg" name="status" id="status" required>
                                                    <option value="pending" ' . ($schedule['status'] === 'pending' ? 'selected' : '') . '>Pending</option>
                                                    <option value="approved" ' . ($schedule['status'] === 'approved' ? 'selected' : '') . '>Approved</option>
                                                    <option value="dispatched" ' . ($schedule['status'] === 'dispatched' ? 'selected' : '') . '>Dispatched</option>
                                                    <option value="completed" ' . ($schedule['status'] === 'completed' ? 'selected' : '') . '>Completed</option>
                                                </select>
                                           </div>
                                       </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-lg w-100">Update Schedule</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
  }
}
