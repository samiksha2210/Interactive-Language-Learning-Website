<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("Activity not found.");
}

$activity_id = intval($_GET['id']);

// Fetch activity details
$query = "SELECT * FROM activities WHERE id = $activity_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Invalid activity.");
}

$activity = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $activity['title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #D16BA5, #86A8E7, #5FFBF1, #7FDD4C);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .activity-container {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 600px;
            animation: fadeIn 0.8s;
        }

        h2 {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #555;
        }

        a {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            font-size: 1rem;
            text-decoration: none;
            color: white;
            background-color: #6c63ff;
            border-radius: 10px;
            transition: all 0.3s;
        }

        a:hover {
            background-color: #4e4baf;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="activity-container animate__animated animate__fadeIn">
        <h2><?php echo $activity['title']; ?></h2>
        <p><?php echo $activity['description']; ?></p>

        <?php
        // Load activity content based on type
        switch ($activity['activity_type']) {
            case 'quiz':
                echo "<a href='pages/quiz.php?activity_id=$activity_id'>Start Quiz</a>";
                break;
            case 'flashcard':
                echo "<a href='pages/flashcards.php?activity_id=$activity_id'>View Flashcards</a>";
                break;
            case 'fill-in-the-blank':
                echo "<a href='pages/fill_in_the_blank.php?activity_id=$activity_id'>Start Fill in the Blank</a>";
                break;
            case 'matching':
                echo "<a href='pages/matching.php?activity_id=$activity_id'>Start Matching</a>";
                break;
            default:
                echo "<p>Activity type not recognized.</p>";
        }
        ?>
    </div>
</body>
</html>
