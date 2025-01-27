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
        <div class="container d-flex">
            <div class="col-md-5 col-md-offset-3">
                <video id="preview" class="img-thumbnail"></video>
            </div>


        </div>
        <div class="col-md-12 col-md-offset-3 px-3 mt-5">

            <!-- Output Section -->
            <div id="output"></div>

            <div class="d-flex justify-content-end mt-4">
                <div class="btn btn-lg btn-primary me-3">Empty & Payment Recived</div>
                <div class="btn btn-lg btn-success">Cylinder Issued</div>
            </div>
        </div>

        <script>

            // Initialize the scanner
            let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

            // Add listener to handle scanned content
            scanner.addListener('scan', function (content) {
                console.log('Scanned content:', content); // Debugging log
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
                    <div class="card">
                        <h3>Consumer Request</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Consumer ID</th>
                                    <th>Gas Type</th>
                                    <th>Quantity</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${data.crequest.data.consumer_id}</td>
                                    <td>${data.crequest.data.gas_type}</td>
                                    <td>${data.crequest.data.quantity}</td>
                                    <td>${data.crequest.data.address}</td>
                                    <td>${data.crequest.data.status}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                `;
                        } else {
                            outputDiv.innerHTML = `<p>No data found for the scanned ID.</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('output').innerHTML = `<p>Error fetching data.</p>`;
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
        </script>

    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>