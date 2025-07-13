<?php
session_start();
include '../db_connect.php'; // Adjust the path as needed

// Debugging: Check if the session is working
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));
}

// Debugging: Check if POST data is received
if (!isset($_POST['activity_id']) || !isset($_POST['score'])) {
    die("Invalid request. activity_id: " . (isset($_POST['activity_id']) ? $_POST['activity_id'] : 'Not set') . ", score: " . (isset($_POST['score']) ? $_POST['score'] : 'Not set'));
}

// Get the user ID, activity ID, and score from the request
$user_id = $_SESSION['user_id'];
$activity_id = intval($_POST['activity_id']);
$score = intval($_POST['score']);

// Debugging: Check the values of variables
echo "User ID: $user_id, Activity ID: $activity_id, Score: $score<br>";

// Check if the user has already completed this activity
$check_query = "SELECT * FROM user_progress WHERE user_id = $user_id AND activity_id = $activity_id";
$check_result = $conn->query($check_query);

if (!$check_result) {
    die("Query failed: " . $conn->error);
}

if ($check_result->num_rows > 0) {
    // User has already completed the activity
    $row = $check_result->fetch_assoc();
    $current_score = intval($row['score']);

    // Debugging: Check the current score
    echo "Current score: $current_score<br>";

    // Update the record only if the new score is higher
    if ($score > $current_score) {
        $update_query = "UPDATE user_progress SET score = $score, completed = 1, last_updated = NOW() WHERE user_id = $user_id AND activity_id = $activity_id";
        if ($conn->query($update_query)) {
            echo "Score updated successfully.";
        } else {
            echo "Error updating score: " . $conn->error;
        }
    } else {
        echo "New score is not higher than the current score. No update performed.";
    }
} else {
    // User is completing the activity for the first time
    $insert_query = "INSERT INTO user_progress (user_id, activity_id, score, completed, last_updated) VALUES ($user_id, $activity_id, $score, 1, NOW())";
    if ($conn->query($insert_query)) {
        echo "Score recorded successfully.";
    } else {
        echo "Error recording score: " . $conn->error;
    }
}

$conn->close();
?>