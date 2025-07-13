<?php
session_start();
include '../db_connect.php';

if (!isset($_GET['activity_id'])) {
    die("Activity not found.");
}

$activity_id = intval($_GET['activity_id']);
$user_id = $_SESSION['user_id'];

// Fetch flashcards for the activity
$query = "SELECT f.*, IFNULL(l.learned, 0) AS learned FROM flashcards f
          LEFT JOIN learned_flashcards l ON f.id = l.flashcard_id AND l.user_id = $user_id
          WHERE f.activity_id = $activity_id";
$result = $conn->query($query);

$flashcards = [];
while ($row = $result->fetch_assoc()) {
    $flashcards[] = $row;
}

if (count($flashcards) == 0) {
    die("No flashcards available for this activity.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #6D83F2, #F279A9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: white;
            margin-bottom: 20px;
        }
        .flashcard-container {
            perspective: 1000px;
            margin-bottom: 40px;
        }
        .flashcard {
            width: 320px;
            height: 400px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
            cursor: pointer;
            margin-top: -10px;
        }
        .flashcard.flip {
            transform: rotateY(180deg);
        }
        .card-side {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .front {
            background: white;
        }
        .back {
            background: #f279a9;
            color: white;
            transform: rotateY(180deg);
        }
        .controls {
            margin-top: 30px;
            text-align: center;
        }
        button {
            padding: 10px 25px;
            margin: 5px;
            border: none;
            border-radius: 8px;
            background-color: #f279a9;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #6d83f2;
        }
        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .card-count {
            margin-top: 15px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
<h2>Flashcards</h2>
<div class="flashcard-container" onclick="flipCard()">
    <div class="flashcard" id="flashcard">
        <div class="card-side front" id="front"></div>
        <div class="card-side back" id="back"></div>
    </div>
</div>
<div class="controls">
    <button onclick="previousCard()">Previous</button>
    <button onclick="nextCard()">Next</button>
    <button onclick="shuffleCards()">Shuffle</button>
    <button id="markLearnedBtn" onclick="markLearned()">Mark as Learned</button>
    <?php if (count($flashcards) > 0): ?>
        <button onclick="submitActivity()">Submit</button>
    <?php endif; ?>
    <div class="card-count" id="counter"></div>
</div>
<script>
let flashcards = <?php echo json_encode($flashcards); ?>;
let currentIndex = 0;
let flipped = false;

function loadCard() {
    let card = flashcards[currentIndex];
    document.getElementById('front').innerHTML = `<img src='../${card.image_url}' style='max-width: 90%; max-height: 90%; border-radius: 15px;'>`;
    document.getElementById('back').innerText = `${card.word} - ${card.translation}`;
    document.getElementById('counter').innerText = `Card ${currentIndex + 1} of ${flashcards.length}`;
    document.getElementById('flashcard').classList.remove('flip');
    flipped = false;
    
    let markLearnedBtn = document.getElementById('markLearnedBtn');
    if (card.learned == 1) {
        markLearnedBtn.disabled = true;
        markLearnedBtn.innerText = "Already Learned";
    } else {
        markLearnedBtn.disabled = false;
        markLearnedBtn.innerText = "Mark as Learned";
    }
}

function flipCard() {
    flipped = !flipped;
    document.getElementById('flashcard').classList.toggle('flip');
}

function nextCard() {
    if (currentIndex < flashcards.length - 1) {
        currentIndex++;
        loadCard();
    }
}

function previousCard() {
    if (currentIndex > 0) {
        currentIndex--;
        loadCard();
    }
}

function shuffleCards() {
    flashcards.sort(() => Math.random() - 0.5);
    currentIndex = 0;
    loadCard();
}

function markLearned() {
    let cardId = flashcards[currentIndex].id;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "mark_learned.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("user_id=<?php echo $user_id; ?>&activity_id=<?php echo $activity_id; ?>&card_id=" + cardId);
    flashcards[currentIndex].learned = 1;
    loadCard();
}

function submitActivity() {
    window.location.href = "submit_activity.php?activity_id=<?php echo $activity_id; ?>";
}

loadCard();
</script>
</body>
</html>
