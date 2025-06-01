<?php
$host = "localhost";
$db = "quiz_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch the most recent result
$sql = "SELECT student_name, correct, incorrect FROM results ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Quiz Result</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 30px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }
        canvas {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Quiz Result</h1>
        <?php if ($data): ?>
            <h3><?php echo htmlspecialchars($data['student_name']); ?></h3>
            <p>Correct: <?php echo $data['correct']; ?></p>
            <p>Incorrect: <?php echo $data['incorrect']; ?></p>
            <canvas id="resultChart" width="400" height="200"></canvas>
        <?php else: ?>
            <p>No results found. Please take a quiz first.</p>
        <?php endif; ?>
    </div>

    <?php if ($data): ?>
    <script>
        new Chart(document.getElementById("resultChart"), {
            type: 'bar',
            data: {
                labels: ["Correct", "Incorrect"],
                datasets: [{
                    label: "Quiz Score",
                    data: [<?php echo $data['correct']; ?>, <?php echo $data['incorrect']; ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: "Your Quiz Performance"
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
