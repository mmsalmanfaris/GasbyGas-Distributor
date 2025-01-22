<?php
require './firebase.php';

header('Content-Type: application/json');

// Fetch data from Firebase Realtime Database
$reference = $database->getReference('chart-data'); // Path to your data
$snapshot = $reference->getValue();

// Prepare data for Chart.js
$data = [
    'labels' => array_keys($snapshot), // Assuming keys are the labels
    'datasets' => [
        [
            'label' => 'Sales',
            'data' => array_values($snapshot), // Assuming values are the data points
            'backgroundColor' => [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            'borderColor' => [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            'borderWidth' => 1
        ]
    ]
];

echo json_encode($data);
?>