<?php
session_start();
include '../db_connect.php';

if (!isset($_GET['activity_id'])) {
    die("Activity not found.");
}

$user_id = $_SESSION['user_id'];
$activity_id = intval($_GET['activity_id']);

// Count the number of flashcards learned for this activity
$query = "SELECT COUNT(*) AS learned_count 
          FROM learned_flashcards 
          JOIN flashcards ON learned_flashcards.flashcard_id = flashcards.id
          WHERE learned_flashcards.user_id = $user_id 
          AND flashcards.activity_id = $activity_id";

$result = $conn->query($query);
$row = $result->fetch_assoc();
$learned_count = $row['learned_count'];

// Count total flashcards for the activity
$query2 = "SELECT COUNT(*) AS total_count 
           FROM flashcards 
           WHERE activity_id = $activity_id";

$result2 = $conn->query($query2);
$row2 = $result2->fetch_assoc();
$total_count = $row2['total_count'];

// If the user has learned all flashcards, mark activity as completed
if ($learned_count == $total_count) {
    // Update user_progress table
    $score = $learned_count; // Score is the number of flashcards learned
    $query3 = "INSERT INTO user_progress (user_id, activity_id, score, completed) 
               VALUES ($user_id, $activity_id, $score, 1)
               ON DUPLICATE KEY UPDATE 
               score = score + $score, 
               completed = 1";

    $conn->query($query3);

    // Redirect to profile page
    header("Location: ../profile.php");
    exit();
} else {
    // If not all flashcards are learned, do not submit
    echo "You have not completed all flashcards yet.";
}
?>