<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$_SESSION['current_question']++; // Move to next question
$_SESSION['answered'] = false;
$_SESSION['feedback'] = null; // Clear feedback to remove previous remarks

if ($_SESSION['current_question'] >= count($_SESSION['fill_in_blank'])) {
    $final_score = $_SESSION['score'];
    
    $update_score_query = "UPDATE users SET score = score + $final_score WHERE id = $user_id";
    $conn->query($update_score_query);

    unset($_SESSION['fill_in_blank']);
    unset($_SESSION['current_question']);
    unset($_SESSION['score']);
    unset($_SESSION['answered']);
    unset($_SESSION['feedback']);

    header("Location: ../profile.php");
    exit();
}

header("Location: fill_in_the_blank.php?activity_id=" . $_GET['activity_id']);
exit();
?>