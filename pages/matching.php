<?php
session_start();
include '../db_connect.php';

if (!isset($_GET['activity_id'])) {
    die("Activity not found.");
}

$activity_id = intval($_GET['activity_id']);
$user_id = $_SESSION['user_id']; // Ensure user is logged in

// Fetch matching words
$query = "SELECT * FROM matching WHERE activity_id = $activity_id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$words = [];
while ($row = $result->fetch_assoc()) {
    $words[] = $row;
}

if (empty($words)) {
    die("No words found for this activity.");
}

// Shuffle the meanings
$shuffledWords = $words;
shuffle($shuffledWords);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matching Activity</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: linear-gradient(45deg, #FF6F61, #FFB44D);
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            color: #333;
        }
        h2 {
            font-size: 2.5rem;
            color: #333;
        }
        .column {
            width: 45%;
            display: inline-block;
            vertical-align: top;
            margin: 10px;
        }
        .word, .meaning {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 20px;
            background-color: white;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s, transform 0.2s;
        }
        .word:hover, .meaning:hover {
            transform: scale(1.05);
        }
        .selected {
            background-color: #ADD8E6;
        }
        .matched {
            background-color: #D3D3D3;
            cursor: default;
        }
        #score-display {
            font-size: 20px;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            margin: 20px 10px;
            background-color: #ff7f50;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    <h2>Matching Activity</h2>
    <div class="column" id="words-column">
        <?php foreach ($words as $word): ?>
            <div class="word" data-id="<?php echo $word['id']; ?>"><?php echo $word['word']; ?></div>
        <?php endforeach; ?>
    </div>
    <div class="column" id="meanings-column">
        <?php foreach ($shuffledWords as $word): ?>
            <div class="meaning" data-id="<?php echo $word['id']; ?>"><?php echo $word['correct_match']; ?></div>
        <?php endforeach; ?>
    </div>
    <div style="clear: both;"></div>
    <button id="submit" class="button">Submit</button>
    <button id="profile-btn" class="button">Go to Profile</button>
    <div id="score-display"></div>

    <script>
        $(document).ready(function() {
            let selectedPairs = []; // Store user-selected pairs

            $('.word, .meaning').click(function() {
                let id = $(this).data('id');
                let type = $(this).hasClass('word') ? 'word' : 'meaning';

                if ($(this).hasClass('matched')) return; // Prevent clicking already matched items

                if (type === 'word' && selectedPairs.some(pair => pair.wordId === id)) {
                    return; // Prevent selecting the same word twice
                }
                if (type === 'meaning' && selectedPairs.some(pair => pair.meaningId === id)) {
                    return; // Prevent selecting the same meaning twice
                }

                $(this).toggleClass('selected'); // Toggle selection

                // Check if we have a pair selected
                let selectedWord = $('.word.selected');
                let selectedMeaning = $('.meaning.selected');

                if (selectedWord.length && selectedMeaning.length) {
                    let wordId = selectedWord.data('id');
                    let meaningId = selectedMeaning.data('id');

                    // Store the pair
                    selectedPairs.push({ wordId, meaningId, correct: wordId === meaningId });

                    // Style matched pairs
                    selectedWord.removeClass('selected').addClass('matched');
                    selectedMeaning.removeClass('selected').addClass('matched');
                }
            });

            $('#submit').click(function() {
                let correctMatches = selectedPairs.filter(pair => pair.correct).length;
                
                $.post("update_matching_score.php", {
                    user_id: <?php echo $user_id; ?>,
                    activity_id: <?php echo $activity_id; ?>,
                    score: correctMatches
                }, function(response) {
                    $('#score-display').text("Your score is: " + correctMatches);
                });
            });

            $('#profile-btn').click(function() {
                window.location.href = '../profile.php';
            });
        });
    </script>
</body>
</html>
