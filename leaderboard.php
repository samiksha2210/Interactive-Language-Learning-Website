<?php 
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ✅ Fetch leaderboard data
$query = "SELECT users.id, users.username, COALESCE(SUM(user_progress.score), 0) AS total_score 
          FROM users 
          LEFT JOIN user_progress ON users.id = user_progress.user_id
          GROUP BY users.id, users.username
          ORDER BY total_score DESC";

$result = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgb(230, 139, 227), rgb(31, 178, 176), rgb(252, 248, 37));
            min-height: 100vh;
        }
        nav {
            background-color: black;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            margin: 20px 0;
            color: white;
            font-weight: 700;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-size: 18px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        th {
            background-color: #003366;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:first-child {
            background-color: #ffd700;
            font-weight: bold;
        }
        img.avatar {
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }
        .gold { color: #ffd700; }
        .silver { color: #c0c0c0; }
        .bronze { color: #cd7f32; }
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            color: white;
            font-weight: 600;
        }
        .pro { background-color: #ff5722; }
        .top { background-color: #4caf50; }
        .active { background-color: #03a9f4; }
        .beginner { background-color: #9e9e9e; }
    </style>
</head>
<body>

<nav>
    <a href="profile.php">Back to Profile</a>
</nav>

<h2>🏆 Leaderboard</h2>

<table>
    <tr>
        <th>Rank</th>
        <th>Avatar</th>
        <th>Username</th>
        <th>Total Score</th>
        <th>Badge</th>
    </tr>

    <?php 
    $rank = 1;
    while ($row = $result->fetch_assoc()) { 
        $avatar = "https://i.pravatar.cc/50?u=" . $row['id']; 
        $username = htmlspecialchars($row['username']);
        $score = $row['total_score'];

        if ($rank == 1) {
            $medal = "<span class='gold'>🥇</span>";
        } elseif ($rank == 2) {
            $medal = "<span class='silver'>🥈</span>";
        } elseif ($rank == 3) {
            $medal = "<span class='bronze'>🥉</span>";
        } else {
            $medal = $rank;
        }

        if ($score >= 120) {
            $badge = "<span class='badge pro'>🏆 Pro Champion</span>";
        } elseif ($score >= 80) {
            $badge = "<span class='badge top'>💎 Top Scorer</span>";
        } elseif ($score >= 40) {
            $badge = "<span class='badge active'>⭐ Active Learner</span>";
        } else {
            $badge = "<span class='badge beginner'>🎓 Beginner</span>";
        }
    ?>

    <tr>
        <td><?php echo $medal; ?></td>
        <td><img src="<?php echo $avatar; ?>" class="avatar" alt="Avatar"></td>
        <td><?php echo $username; ?></td>
        <td><?php echo $score; ?></td>
        <td><?php echo $badge; ?></td>
    </tr>

    <?php $rank++; } ?>
</table>

</body>
</html>
