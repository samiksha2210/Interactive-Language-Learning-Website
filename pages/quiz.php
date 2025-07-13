<?php 
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

if (!isset($_SESSION['quiz']) || $_SESSION['activity_id'] != $activity_id) {
    $_SESSION['quiz'] = [];
    $_SESSION['current_question'] = 0;
    $_SESSION['score'] = 0;
    $_SESSION['activity_id'] = $activity_id;
    $_SESSION['feedback'] = "";

    $query = "SELECT * FROM quizzes WHERE activity_id = $activity_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['quiz'][] = $row;
        }
    }
}

$total_questions = isset($_SESSION['quiz']) ? count($_SESSION['quiz']) : 0;
$current_question_index = isset($_SESSION['current_question']) ? $_SESSION['current_question'] : 0;

if ($total_questions == 0) {
    echo "<h2>No questions found for this activity.</h2>";
    exit();
}

if ($current_question_index >= $total_questions) {
    header("Location: ../profile.php");
    exit();
}

$current_question = $_SESSION['quiz'][$current_question_index];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6D83F2, #A0DBF5);
            transition: background 0.5s;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .quiz-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 700px;
            text-align: center;
            animation: fadeIn 0.6s;
        }
        h2 {
            color: #3C4A93;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        button {
            background-color: #6D83F2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        button:disabled {
            background-color: grey;
            cursor: not-allowed;
        }
        button:hover:enabled {
            background-color: #3C4A93;
        }
        #feedback {
            margin-top: 15px;
            color: #3C4A93;
            font-weight: bold;
        }
        #timer {
            margin-bottom: 20px;
            font-weight: bold;
            color: #FF5A5A;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script>
        let timeLeft = 60;
        let timer;

        function startTimer() {
            timer = setInterval(function () {
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    autoSubmit();
                } else {
                    document.getElementById("timer").innerText = "Time Left: " + timeLeft + "s";
                    timeLeft--;
                }
            }, 1000);
        }

        function autoSubmit() {
            let selectedAnswer = document.querySelector('input[name="answer"]:checked');
            if (!selectedAnswer) {
                document.getElementById("feedback").innerHTML = "⏳ Time's up! No answer selected.";
            } else {
                handleSubmit();
            }
            document.getElementById("submitBtn").disabled = true;
            document.getElementById("nextBtn").disabled = false;
        }

        function handleSubmit(event) {
            if (event) event.preventDefault();

            let selectedAnswer = document.querySelector('input[name="answer"]:checked');
            if (!selectedAnswer) return;

            let correctAnswer = document.getElementById("correct_answer").value;
            let feedback = document.getElementById("feedback");

            if (selectedAnswer.value === correctAnswer) {
                feedback.innerHTML = "✅ Correct!";
            } else {
                feedback.innerHTML = "❌ Wrong! The correct answer is: " + correctAnswer;
            }

            document.getElementById("submitBtn").disabled = true;
            document.getElementById("nextBtn").disabled = false;
            clearInterval(timer);

            let formData = new FormData(document.getElementById("quizForm"));
            fetch("quiz_process.php?action=next&activity_id=<?php echo $activity_id; ?>", {
                method: "POST",
                body: formData
            }).then(response => console.log("Answer submitted."));
        }

        function goToNext() {
            window.location.href = "quiz.php?activity_id=<?php echo $activity_id; ?>";
        }

        window.onload = function () {
            startTimer();
        };
    </script>
</head>
<body>

    <div class="quiz-container">
        <h2>Question <?php echo ($current_question_index + 1) . " of " . $total_questions; ?></h2>

        <p><?php echo htmlspecialchars($current_question['question']); ?></p>

        <form id="quizForm" onsubmit="handleSubmit(event)">
            <input type="hidden" id="correct_answer" name="correct_answer" value="<?php echo htmlspecialchars($current_question['correct_answer']); ?>">

            <?php
            $options = json_decode($current_question['options'], true);
            if (is_array($options)) {
                foreach ($options as $option) {
                    echo "<label><input type='radio' name='answer' value='".htmlspecialchars($option)."' required onclick='document.getElementById(\"submitBtn\").disabled = false'> ".htmlspecialchars($option)."</label>";
                }
            }
            ?>

            <p id="timer">Time Left: 60s</p>
            <button type="submit" id="submitBtn" disabled>Submit</button>
        </form>

        <p id="feedback"><?php echo isset($_SESSION['feedback']) ? $_SESSION['feedback'] : ''; ?></p>

        <button id="prevBtn" onclick="window.location.href='quiz.php?activity_id=<?php echo $activity_id; ?>&action=prev'" <?php echo $current_question_index == 0 ? 'disabled' : ''; ?>>Previous</button>

        <button id="nextBtn" onclick="goToNext()" disabled>Next</button>
    </div>

</body>
</html>
