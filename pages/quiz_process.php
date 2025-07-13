<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['quiz'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$activity_id = $_SESSION['activity_id'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

$current_question_index = $_SESSION['current_question'];
$total_questions = count($_SESSION['quiz']);

if ($action == 'next' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['answer'])) {
        $selected_answer = $_POST['answer'];
        $correct_answer = $_SESSION['quiz'][$current_question_index]['correct_answer'];

        if ($selected_answer === $correct_answer) {
            $_SESSION['score'] += 1;

            // ✅ Update user_progress instead of users table
            $checkQuery = "SELECT * FROM user_progress WHERE user_id = ? AND activity_id = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("ii", $user_id, $activity_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // If exists, update score
                $updateQuery = "UPDATE user_progress SET score = score + 1, last_updated = CURRENT_TIMESTAMP WHERE user_id = ? AND activity_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ii", $user_id, $activity_id);
            } else {
                // Insert a new record
                $insertQuery = "INSERT INTO user_progress (user_id, activity_id, score, completed) VALUES (?, ?, 1, 0)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ii", $user_id, $activity_id);
            }

            if ($stmt->execute()) {
                error_log("✅ Score updated in user_progress for User ID: $user_id");
            } else {
                error_log("❌ Score update failed: " . $stmt->error);
            }

            $stmt->close();
        }
    }
}

// Move to next or previous question
if ($action == 'next') {
    $_SESSION['current_question']++;

    if ($_SESSION['current_question'] >= $total_questions) {
        // ✅ Mark activity as completed in user_progress
        $stmt = $conn->prepare("UPDATE user_progress SET completed = 1 WHERE user_id = ? AND activity_id = ?");
        $stmt->bind_param("ii", $user_id, $activity_id);
        $stmt->execute();
        $stmt->close();

        header("Location: ../profile.php");
        exit();
    }
} elseif ($action == 'prev' && $_SESSION['current_question'] > 0) {
    $_SESSION['current_question']--;
}

header("Location: quiz.php?activity_id=$activity_id");
exit();
?>