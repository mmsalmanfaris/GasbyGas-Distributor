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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Total</th>
                        <th>Delivery</th>
                        <th>Empty</th>
                        <th>Payment</th>
                        <th>Issue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                        <td><input type="text" id="scan-result" class="form-control" readonly></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-end mt-4">
                <div class="btn btn-lg btn-primary me-3">Empty & Payment Recived</div>
                <div class="btn btn-lg btn-success">Cylinder Issued</div>
            </div>
        </div>

        <script>
            let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                document.getElementById('scan-result').value = content;
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        </script>

    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>