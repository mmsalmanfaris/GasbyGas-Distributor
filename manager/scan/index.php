<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>



    <?php
    include_once '../components/manager-dashboard-top.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Scanner Token</h1>
        </div>
        <div class="container d-flex mt-5">
            <div class="col-md-5 col-md-offset-3 me-5">
                <video id="preview" class="img-thumbnail"></video>
            </div>
            <!-- Output Section -->
            <div class="">
                <div id="output" class="w-100"></div>
                <div class="d-flex justify-content-end mt-5">
                    <div class="btn btn-lg btn-primary me-3" id="empty-payment-btn">Empty & Payment Received</div>
                    <div class="btn btn-lg btn-success" id="cylinder-issued-btn">Cylinder Issued</div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-md-offset-3 px-3 mt-5">



        </div>

        <script>
            // Initialize the scanner
            let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

            // Variable to store the last scanned consumer_id
            let currentConsumerId = '';

            // Add listener to handle scanned content
            scanner.addListener('scan', function (content) {
                console.log('Scanned content:', content); // Debugging log

                // Store the scanned consumer ID
                currentConsumerId = content;

                // Fetch data for the scanned ID
                fetch('fetch_crequest.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ consumer_id: content }) // Send scanned consumer_id
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response from server:', data); // Debugging log
                        let outputDiv = document.getElementById('output');
                        if (data.success) {
                            outputDiv.innerHTML = `
                        <div>
                            <h3>Consumer Request</h3>
                            <table class="table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>Consumer</th>
                                        <th>Quantity</th>
                                        <th>Cylinder</th>
                                        <th>Payment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${data.crequest.data.consumer_id}</td>
                                        <td>${data.crequest.data.quantity}</td>
                                        <td>${data.crequest.data.empty_cylinder}</td>
                                        <td>${data.crequest.data.payment_status}</td>
                                        <td>${data.crequest.data.sdelivery}</td>
                                        <td>${data.crequest.data.delivery_status}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                        } else {
                            outputDiv.innerHTML = `<p class="alert alert-danger""> Wrong QR Code. Please check your QR Code.</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('output').innerHTML = `<p class="alert alert-danger">Error: System functional error.</p>`;
                    });
            });

            // Start the scanner
            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]); // Start the first available camera
                    } else {
                        alert('No cameras found.');
                    }
                })
                .catch(function (e) {
                    console.error(e);
                });

            // Button: Empty & Payment Received
            document.getElementById('empty-payment-btn').addEventListener('click', function () {
                if (currentConsumerId) {
                    fetch('update_empty_payment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ consumer_id: currentConsumerId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Optionally, refresh the output
                                document.getElementById('output').innerHTML = `<p class="alert alert-success">Cylinder & Payment status updated successfully!</p>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    document.getElementById('output').innerHTML = `<p class="alert alert-danger">Please scan QR Code and update data.</p>`;
                }
            });

            // Button: Cylinder Issued
            document.getElementById('cylinder-issued-btn').addEventListener('click', function () {
                if (currentConsumerId) {
                    fetch('update_cylinder_issued.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ consumer_id: currentConsumerId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Optionally, refresh the output
                                document.getElementById('output').innerHTML = `<p class="alert alert-success">Cylinder issued status updated successfully!</p>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    alert('No Consumer ID available. Scan a QR code first.');
                }
            });

        </script>


    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>