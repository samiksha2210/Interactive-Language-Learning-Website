<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Learning - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Lora:ital@1&family=Poppins:wght@600&family=Quicksand:wght@400;600&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar-brand {
            font-size: 30px;
            font-weight: bold;
            color: #ff9a8b;
            font-family: 'Quicksand', sans-serif;
        }

        .navbar-nav .nav-link {
            color: #ff9a8b;
            font-size: 18px;
            font-weight: 600;
            margin-left: 15px;
            transition: all 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #ff9a8b;
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero {
            background-image: url('/final_lang/language_learning/assets/images/lang1.png');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: black;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: bold;
            font-family: 'Quicksand', sans-serif;
            margin-bottom: 10px;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 400;
            margin-bottom: 20px;
        }

        .hero-btn {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 30px;
            transition: all 0.3s;
            background-color: #ff9a8b;
            color: white;
            border: none;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-btn:hover {
            background-color: #f39c12;
            transform: scale(1.05);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
        }

        /* Heading "Why Choose LangLearn?" */
        .features h2 {
            font-family: 'Dancing Script', cursive;
            font-size: 3rem;
            color: #ff6f61;
            font-weight: bold;
        }

        .feature-card {
            padding: 30px;
            border-radius: 20px;
            transition: all 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Feature Titles */
        .feature-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        /* Feature Descriptions */
        .feature-text {
            font-family: 'Lora', serif;
            font-size: 1.1rem;
            font-style: italic;
            color: #555;
        }

        /* Pastel Colors for Features */
        .feature1 { background-color: #ffe4e1; } /* Light Pink */
        .feature2 { background-color: #e0f7fa; } /* Soft Blue */
        .feature3 { background-color: #f3e5f5; } /* Light Lavender */

        /* Footer */
        .footer {
            background-color: #222;
            color: white;
            padding: 30px 0;
            font-size: 16px;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">LangLearn</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-black px-4 ms-3" href="login.php">Sign In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1 class="hero-title">A Fun Way to Learn Languages</h1>
            <p class="hero-subtitle">Interactive activities, challenges, and rewards await you.</p>
            <a href="register.php" class="btn hero-btn">Create Account</a>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features text-center">
        <div class="container">
            <h2 class="mb-5">Why Choose LangLearn?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card feature1">
                        <i class="bi bi-book-half feature-icon"></i>
                        <h4 class="feature-title">Interactive Activities</h4>
                        <p class="feature-text">Play games, take quizzes, and enhance your vocabulary.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card feature2">
                        <i class="bi bi-bar-chart feature-icon"></i>
                        <h4 class="feature-title">Track Your Progress</h4>
                        <p class="feature-text">Stay motivated by tracking your scores and progress.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card feature3">
                        <i class="bi bi-award feature-icon"></i>
                        <h4 class="feature-title">Earn Rewards</h4>
                        <p class="feature-text">Unlock badges and achieve new ranks as you learn.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer text-center">
        <p>&copy; 2025 LangLearn. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
