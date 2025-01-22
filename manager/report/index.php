<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Dashboard</title>


    <?php
    include_once '../components/manager-dashboard-top.php';
    ?>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Report</h1>
        </div>

        <!-- <div class="d-flex justify-content-between">
            <div class="col-3">
                <canvas id="myChart1"></canvas>
            </div>
            <div class="col-3">
                <canvas id="myChart2"></canvas>
            </div>
            <div class="col-3">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        <div class="mt-5">
            <canvas id="myLine"></canvas>
        </div>

        <script>
            // Chart.js configuration
            const ctx1 = document.getElementById('myChart1').getContext('2d');
            const myChart1 = new Chart(ctx1, {
                type: 'pie', // Change to 'line', 'pie', etc.
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 7, 10, 15, 8, 6, 11, 9],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <script>
            // Chart.js configuration
            const ctx2 = document.getElementById('myChart2').getContext('2d');
            const myChart2 = new Chart(ctx2, {
                type: 'pie', // Change to 'line', 'pie', etc.
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 7, 10, 15, 8, 6, 11, 9],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <script>
            // Chart.js configuration
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'pie', // Change to 'line', 'pie', etc.
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 7, 10, 15, 8, 6, 11, 9],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <script>
            // Fetch data from PHP endpoint
            fetch('data.php')
                .then(response => response.json())
                .then(data => {
                    const ctxl = document.getElementById('myLine').getContext('2d');
                    new Chart(ctxl, {
                        type: 'bar', // Change to 'line', 'pie', etc.
                        data: data,
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        </script> -->


        <div style="width: 80%; margin: auto;">
            <canvas id="myLineChart"></canvas>
        </div>

        <script>
            // Firebase URL
            const firebaseUrl = "https://gasbygas-97e19-default-rtdb.firebaseio.com/chart-data.json";

            // Fetch data from Firebase
            async function fetchChartData() {
                try {
                    const response = await fetch(firebaseUrl); // Get data from Firebase
                    const data = await response.json(); // Parse JSON
                    const labels = Object.keys(data); // Extract keys (e.g., 'January', 'February')
                    const values = Object.values(data); // Extract values (e.g., 12, 19, 3)

                    // Call function to render chart
                    renderLineChart(labels, values);
                } catch (error) {
                    console.error("Error fetching data:", error);
                }
            }

            // Render Line Chart
            function renderLineChart(labels, data) {
                const ctx = document.getElementById('myLineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // Line chart type
                    data: {
                        labels: labels, // X-axis labels
                        datasets: [{
                            label: 'Monthly Sales',
                            data: data, // Y-axis data
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            tension: 0.4 // Curve the line
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Months'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Sales'
                                }
                            }
                        }
                    }
                });
            }

            // Fetch and render chart data
            fetchChartData();
        </script>






    </main>


    <?php
    include_once '../components/manager-dashboard-down.php';
    ?>