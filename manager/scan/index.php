<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <style>
        .scanner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        #output {
            margin-top: 20px;
            width: 100%;
            max-width: 500px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <main class="scanner-container">
        <h1>QR Code Scanner</h1>
        <video id="preview" class="img-thumbnail" style="width: 100%; max-width: 400px;"></video>

        <!-- Output Section -->
        <div id="output"></div>

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
                            // Display fetched data
                            outputDiv.innerHTML = `
                                <div class="card">
                                    <p><strong>Outlet Id:</strong> ${data.crequest.outlet_id}</p>
                                    <p><strong>Quantity:</strong> ${data.crequest.quantity}</p>
                                    <p><strong>Panel:</strong> ${data.crequest.panel}</p>
                                    <p><strong>Empty:</strong> ${data.crequest.empty_cylinder}</p>
                                    <p><strong>Payment:</strong> ${data.crequest.payment_status}</p>
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
</body>

</html>