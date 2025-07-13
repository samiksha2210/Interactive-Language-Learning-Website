<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['answer']) || !isset($_POST['correct_answer'])) {
        $_SESSION['feedback'] = "⏳ Time's up! Moving to next question.";
    } else {
        $user_answer = trim(strtolower($_POST['answer']));
        $correct_answer = strtolower($_POST['correct_answer']);
        $score = 0;

        if ($user_answer === $correct_answer) {
            $_SESSION['score'] += 1;
            $score = 1;
            $_SESSION['feedback'] = "✅ Correct!";
        } else {
            $_SESSION['feedback'] = "❌ Wrong! The correct answer is: " . htmlspecialchars($_POST['correct_answer']);
        }

        // ✅ Update user_progress table
        $checkQuery = "SELECT * FROM user_progress WHERE user_id = ? AND activity_id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ii", $user_id, $activity_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If exists, update score
            $updateQuery = "UPDATE user_progress SET score = score + ?,completed=1, last_updated = CURRENT_TIMESTAMP WHERE user_id = ? AND activity_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("iii", $score, $user_id, $activity_id);
        } else {
            // Insert new record
            $insertQuery = "INSERT INTO user_progress (user_id, activity_id, score, completed,last_updated) VALUES (?, ?, ?, 1, CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("iii", $user_id, $activity_id, $score);
        }

        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['answered'] = true;
    header("Location: fill_in_the_blank.php?activity_id=" . $activity_id);
    exit();
}
?>