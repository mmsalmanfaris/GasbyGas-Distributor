<?php

include_once '../includes/firebase.php';
include_once '../components/manager-dashboard-top.php';

if (isset($_GET['outlet_id'])) {
    $outletId = $_GET['outlet_id'];
    $outlet = $database->getReference("outlets/{$outletId}")->getValue();
    if ($outlet) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Outlet</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </head>
        <body>
            <div class="container mt-5">
                <div class="modal fade bd-example-modal-lg show" id="editOutletModal" tabindex="-1" role="dialog"
                    aria-labelledby="editOutletModalLabel" aria-hidden="true" style="display:block;">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body text-black p-5">
                                <form action="../includes/updateUser.inc.php" method="POST">
                                    <!-- Hidden input for type -->
                                    <input type="hidden" name="type" value="outlet">

                                    <!-- Hidden input for ID -->
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($outletId); ?>">

                                    <!-- Outlet fields -->
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <input type="text" class="form-control" name="district" id="district" value="<?php echo htmlspecialchars($outlet['district']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="town">Town</label>
                                        <input type="text" class="form-control" name="town" id="town" value="<?php echo htmlspecialchars($outlet['town']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($outlet['name']); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="number" class="form-control" name="stock" id="stock" value="<?php echo htmlspecialchars($outlet['stock']); ?>" required>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Automatically show the modal
                $(document).ready(function() {
                    $('#editOutletModal').modal('show');
                });
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "Outlet not found.";
    }
} else {
    echo "No outlet ID provided.";
}
?>
