<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            ob_start();
            header("Location: profile.php");
            ob_end_flush();
            exit;
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | LangLearn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg,rgb(230, 139, 227),rgb(121, 235, 239),rgb(252, 248, 37));
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-container {
            width: 400px;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: slideIn 1s ease-out;
        }

        @keyframes slideIn {
            0% { 
                transform: translateY(50px); 
                opacity: 0; 
            }
            100% { 
                transform: translateY(0); 
                opacity: 1; 
            }
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #d87a16;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .login-container input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
            transition: transform 0.2s, border-color 0.2s;
        }

        .login-container input:focus {
            transform: scale(1.02);
            border-color: #6c63ff;
            outline: none;
        }

        .login-container button {
            padding: 10px 20px;
            border: none;
            background-color: #6c63ff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .login-container button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(108, 99, 255, 0.7);
        }

        .login-container a {
            display: block;
            margin-top: 15px;
            color: #6c63ff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-container a:hover {
            text-decoration: underline;
            color: #4a43b5;
        }
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

        <img src="/final_lang/language_learning/assets/images/english.jpg"  style="top: 10%; left: 20%; animation-duration: 4s;">
        <img src="/final_lang/language_learning/assets/images/spanish.jpg"  style="top: 70%; left: 85%; animation-duration: 5s;">
        <img src="/final_lang/language_learning/assets/images/french.jpg" style="top: 71%; left: 23%; animation-duration: 10s;">
        <img src="/final_lang/language_learning/assets/images/japanese.jpg"  style="top: 50%; left: 2%; animation-duration: 8s;">
        <img src="/final_lang/language_learning/assets/images/italian.jpg" style="top: 50%; left: 70%; animation-duration: 6s;">
        <img src="/final_lang/language_learning/assets/images/german.jpg" style="top: 5%; left: 80%; animation-duration: 10s;">
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Don't have an account? Sign up</a>
    </div>
</body>
</html>
