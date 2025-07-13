<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate username (at least 5 characters)
    if (strlen($username) < 5) {
        echo "Username must be at least 5 characters long!";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='notification'>Invalid email format!</div>";
        exit();
    }

    // Validate password (at least one special character)
    if (!preg_match('/[\W]/', $password)) {
        echo "<div class='notification'>Password must contain at least one special character!</div>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<div class='notification'>Username or Email already taken!</div>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='notification'>Registration successful! Redirecting to login page...</div>";
            header("Refresh: 1; url=login.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | LangLearn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .notification {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #d87a16;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            z-index: 10;
            font-size: 1.1em;
        }
        body {
            background: linear-gradient(135deg, rgb(230, 139, 227), rgb(252, 248, 37));
            height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .register-container {
            width: 400px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #d87a16;
        }
        .form-container input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        .form-container button {
            padding: 10px 20px;
            border: none;
            background-color: #6c63ff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Floating Image Gallery */
        .image-gallery img {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            position: absolute;
            animation: float 8s infinite;
            transition: transform 0.3s;
        }
        .image-gallery img:hover {
            transform: scale(1.1);
        }
        @keyframes float {
            0% { transform: translate(0, 0); }
            25% { transform: translate(20px, -20px); }
            50% { transform: translate(-20px, 20px); }
            75% { transform: translate(-20px, -20px); }
            100% { transform: translate(0, 0); }
        }
    </style>
</head>
<body>
    <div class="image-gallery">
        <img src="/language_learning/assets/images/english.jpg"  style="top: 10%; left: 20%; animation-duration: 9s;">
        <img src="/language_learning/assets/images/spanish.jpg"  style="top: 55%; left: 85%; animation-duration: 7s;">
        <img src="/language_learning/assets/images/french.jpg" style="top: 60%; left: 40%; animation-duration: 10s;">
        <img src="/language_learning/assets/images/japanese.jpg"  style="top: 50%; left: 10%; animation-duration: 8s;">
        <img src="/language_learning/assets/images/italian.jpg" style="top: 50%; left: 70%; animation-duration: 6s;">
        <img src="/language_learning/assets/images/german.jpg" style="top: 5%; left: 80%; animation-duration: 10s;">
    </div>
    <div class="register-container">
        <div class="form-container">
            <h2>Create Your Account</h2>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username (Min 5 chars)" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password (1 special char)" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
