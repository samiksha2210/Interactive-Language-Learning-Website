<?php
session_start();
include '../db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

// Initialize quiz session if not already set
if (!isset($_SESSION['fill_in_blank'])) {
    $query = "SELECT * FROM fill_in_the_blanks WHERE activity_id = $activity_id";
    $result = $conn->query($query);

    $_SESSION['fill_in_blank'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['fill_in_blank'][] = $row;
    }

    $_SESSION['current_question'] = 0;
    $_SESSION['score'] = 0;
    $_SESSION['answered'] = false;
    $_SESSION['feedback'] = null;
}

// Get current question
$current_question_index = $_SESSION['current_question'];
$total_questions = count($_SESSION['fill_in_blank']);

// Check if there are questions to display
if ($total_questions == 0) {
    echo "<div style='color: red; text-align: center;'>No questions available for this activity.</div>";
    exit();
}

if ($current_question_index >= $total_questions) {
    header("Location: ../profile.php");
    exit();
}

$current_question = $_SESSION['fill_in_blank'][$current_question_index];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill in the Blank</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #6E85B7, #B1C8E4);
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 450px;
            transition: all 0.3s;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: #4E5D78;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        p {
            background-color: #E6ECF5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #B1C8E4;
            width: 100%;
            margin-bottom: 15px;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: #6E85B7;
            outline: none;
        }

        button {
            margin-top: 10px;
            padding: 10px 30px;
            background-color: #6E85B7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #4E5D78;
        }

        button:disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        #timer {
            margin-top: 10px;
            color: #FF6B6B;
            font-weight: bold;
        }

        .feedback {
            margin-top: 15px;
            padding: 10px;
            background-color: #FFD6D6;
            border-radius: 5px;
            color: #FF6B6B;
        }
    </style>
    <script>
        let timeLeft = 60;
        function startTimer() {
            let timer = setInterval(function () {
                document.getElementById("timer").innerText = "Time Left: " + timeLeft + "s";
                timeLeft--;
                if (timeLeft < 0) {
                    clearInterval(timer);
                    document.getElementById("quizForm").submit();
                }
            }, 1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body>
    <div class="container">
        <h2>Question <?php echo ($current_question_index + 1) . " of " . $total_questions; ?></h2>
        <p><?php echo $current_question['sentence']; ?></p>

        <form id="quizForm" action="submit_fill_in_the_blank.php?activity_id=<?php echo $activity_id; ?>" method="POST">
            <input type="hidden" name="correct_answer" value="<?php echo htmlspecialchars($current_question['correct_word']); ?>">
            <input type="text" name="answer" placeholder="Type your answer here..." required>
            <div id="timer">Time Left: 60s</div>
            <button type="submit" <?php if ($_SESSION['answered']) echo 'disabled'; ?>>Submit</button>
        </form>

        <?php if (isset($_SESSION['feedback']) && $_SESSION['feedback'] !== null) { ?>
            <div class="feedback"><?php echo $_SESSION['feedback']; ?></div>
        <?php } ?>

        <form action="previous_question.php?activity_id=<?php echo $activity_id; ?>" method="POST" style="display:inline;">
            <?php if ($_SESSION['current_question'] > 0) { ?>
                <button type="submit">Previous</button>
            <?php } ?>
        </form>

        <?php if ($_SESSION['answered']) { ?>
            <form action="next_question.php?activity_id=<?php echo $activity_id; ?>" method="POST" style="display:inline;">
                <button type="submit">Next</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
