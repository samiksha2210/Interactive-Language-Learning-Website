<?php
session_start();
include '../db_connect.php';

$user_id = $_POST['user_id'];
$activity_id = $_POST['activity_id'];
$card_id = $_POST['card_id'];

// Check if already learned
$query = "SELECT * FROM learned_flashcards WHERE user_id = $user_id AND flashcard_id = $card_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    // Mark as learned and increase score
    $conn->query("INSERT INTO learned_flashcards (user_id, flashcard_id) VALUES ($user_id, $card_id)");
    $conn->query("UPDATE user_progress SET score = score + 1 WHERE user_id = $user_id AND activity_id = $activity_id");
}
?>