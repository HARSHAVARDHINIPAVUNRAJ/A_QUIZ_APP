<?php
$host = "localhost";
$db = "quiz_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM quizzes");
$quizzes = [];

while ($row = $result->fetch_assoc()) {
    $quizzes[] = [
        'question' => $row['question'],
        'options' => [$row['option1'], $row['option2'], $row['option3'], $row['option4']],
        'correctAnswer' => $row['correct_answer']
    ];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .quiz-container { margin-top: 20px; }
        .quiz { margin-bottom: 20px; padding: 15px; background-color: #eee; border-radius: 8px; }
        button { padding: 10px 15px; margin-top: 10px; font-size: 16px; color: white; background-color: #007BFF; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .option-btn { display: block; margin: 10px auto; width: 80%; background-color: #007BFF; }
        .option-btn:disabled { cursor: default; opacity: 0.7; }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Dashboard - Take a Quiz</h1>
    <div id="quiz-container" class="quiz-container">
        <p>Loading quizzes...</p>
    </div>
</div>

<script>
    const quizzes = <?php echo json_encode($quizzes); ?>;
    let correctCount = 0;
    let totalQuestions = quizzes.length;
    let answered = [];

    function loadQuiz() {
        const quizContainer = document.getElementById("quiz-container");
        quizContainer.innerHTML = "";

        if (quizzes.length === 0) {
            quizContainer.innerHTML = "<p>No quizzes available.</p>";
            return;
        }

        quizzes.forEach((quiz, index) => {
            let quizDiv = document.createElement("div");
            quizDiv.className = "quiz";
            quizDiv.innerHTML = `
                <h3>${quiz.question}</h3>
                ${quiz.options.map(option => `
                    <button class="option-btn" onclick="checkAnswer(this, '${option}', '${quiz.correctAnswer}', ${index})">${option}</button>
                `).join('')}
                <hr>
            `;
            quizContainer.appendChild(quizDiv);
        });
    }

    function checkAnswer(button, selected, correct, index) {
        if (answered[index]) return;
        answered[index] = true;

        const buttons = button.parentElement.querySelectorAll(".option-btn");
        buttons.forEach(btn => btn.disabled = true);

        if (selected === correct) {
            button.style.backgroundColor = "green";
            correctCount++;
        } else {
            button.style.backgroundColor = "red";
            buttons.forEach(btn => {
                if (btn.textContent === correct) {
                    btn.style.backgroundColor = "green";
                }
            });
        }

        if (answered.filter(Boolean).length === totalQuestions) {
            setTimeout(() => {
                const name = prompt("Enter your name to view your result:");
                if (name) {
                    const form = new FormData();
                    form.append("student_name", name);
                    form.append("correct", correctCount);
                    form.append("incorrect", totalQuestions - correctCount);

                    fetch("save_result.php", {
                        method: "POST",
                        body: form
                    })
                    .then(res => res.text())
                    .then(data => {
                        alert("Your result has been submitted.");
                        window.location.href = "result.php";
                    });
                }
            }, 500);
        }
    }

    window.onload = loadQuiz;
</script>
</body>
</html>
