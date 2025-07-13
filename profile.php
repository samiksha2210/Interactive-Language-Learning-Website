<?php 
include 'db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user details (username, email)
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// ✅ Calculate total score from user_progress
$stmt = $conn->prepare("SELECT COALESCE(SUM(score), 0) FROM user_progress WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_score);
$stmt->fetch();
$stmt->close();

// ✅ Fetch completed activities count
$stmt = $conn->prepare("SELECT COUNT(*) FROM user_progress WHERE user_id = ? AND completed = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completed_activities);
$stmt->fetch();
$stmt->close();

$conn->close();

$total_activities = 20; // Set total activities to 20
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Raleway:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(135deg, rgb(230, 139, 227), rgb(31, 178, 102), rgb(252, 248, 37));
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: rgb(0, 0, 0);
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #f39c12;
        }
        #profile-container {
            background: white;
            padding: 4%;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 500px;
            margin: 50px auto;
            animation: slideIn 1s ease;
        }
        h2 {
            font-family: 'Playfair Display', serif;
            color: #2c3e50;
        }
        p {
            font-size: 16px;
            font-weight: 600;
            color: #555;
        }
        #activity-tracker {
            text-align: center;
            background: linear-gradient(45deg, rgb(0, 255, 251), #f39c12);
            color: white;
            padding: 10px;
            border-radius: 10px;
            width: 120px;
            font-weight: bold;
            animation: pulse 2s infinite;
            margin: 10px auto;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes slideIn {
            0% { transform: translateX(-100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- ✅ Navbar Section -->
    <nav>
        <a href="activities.php">Activities</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div id="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Score: <?php echo $total_score; ?></p>
        <p style="font-family: 'Playfair Display', serif; font-size: 18px; color: #34495e; text-align: center;">
            🌟 Welcome Back to <b>LangLearn</b>! 🌟<br><br>
            We're excited to see you again! Every step you take brings you closer to mastering your new language. 💪✨<br><br>
            Remember, progress isn't just about perfection—it's about showing up and growing, one word at a time.<br><br> 
            Dive back in, and let’s make learning a joyful journey! 🚀📚<br><br>
            Ready to unlock more knowledge? Let's go! 🔥
        </p>

        <!-- ✅ Activity Tracker Section -->
        <div id="activity-tracker">
            <p>Completed</p>
            <p><?php echo $completed_activities; ?> / <?php echo $total_activities; ?></p>
        </div>
    </div>

</body>
</html>
