<!-- The Modal Start -->
<div class="modal fade bd-example-modal-lg" id="addUserModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-black p-5">
                <form action="../../includes/consumer_reg.inc.php" method="post" class="row g-3 needs-validation" novalidate>
                    <!-- Row for Name and Email -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name:</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control form-control-lg" name="email" id="email" required>
                        </div>
                    </div>

                    <!-- Row for NIC and Rnumber -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type:</label>
                            <select class="form-control form-control-lg" name="type" id="type" required>
                                <option value="individual">Individual</option>
                                <option value="business">Business</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3" id="nicRow">
                        <div class="col-md-6">
                            <label for="nic" class="form-label">NIC:</label>
                            <input type="text" class="form-control form-control-lg" name="nic" id="nic">
                        </div>
                    </div>
                    <div class="row mb-3" id="rnumberRow" style="display: none;">
                        <div class="col-md-6">
                            <label for="rnumber" class="form-label">Rnumber:</label>
                            <input type="text" class="form-control form-control-lg" name="rnumber" id="rnumber">
                        </div>
                    </div>
                    <script>
                        document.getElementById('type').addEventListener('change', function() {
                            var type = this.value;
                            if (type === 'individual') {
                                document.getElementById('nicRow').style.display = 'block';
                                document.getElementById('nic').required = true;
                                document.getElementById('rnumberRow').style.display = 'none';
                                document.getElementById('rnumber').required = false;
                            } else if (type === 'business') {
                                document.getElementById('nicRow').style.display = 'none';
                                document.getElementById('nic').required = false;
                                document.getElementById('rnumberRow').style.display = 'block';
                                document.getElementById('rnumber').required = true;
                            }
                        });
                    </script>

                    <!-- Row for Contact and Address -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contact" class="form-label">Contact:</label>
                            <input type="text" class="form-control form-control-lg" name="contact" id="contact" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" class="form-control form-control-lg" name="address" id="address" required>
                        </div>
                    </div>

                    <!-- Row for District -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="district" class="form-label">District:</label>
                            <input type="text" class="form-control form-control-lg" name="district" id="district" required>
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
