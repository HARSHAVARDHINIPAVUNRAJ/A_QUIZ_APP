<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "quiz_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form inputs
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

// Validate user
$sql = "SELECT * FROM users WHERE username = ? AND password = ? AND role = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    if ($role === "teacher") {
        header("Location: teacher.php");
    } else {
        header("Location: student.php");
    }
    exit();
} else {
    echo "<script>alert('Invalid credentials!'); window.location.href='index.html';</script>";
}
?>
