<?php
$host = "localhost";
$db = "quiz_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $_POST['student_name'];
$correct = $_POST['correct'];
$incorrect = $_POST['incorrect'];

$stmt = $conn->prepare("INSERT INTO results (student_name, correct, incorrect) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $name, $correct, $incorrect);
$stmt->execute();

echo "success";
$conn->close();
