<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, rgb(230, 139, 227), rgb(114, 169, 220), rgb(252, 248, 37));
            transition: background 3s ease;
            height: 100vh;
            min-height: 100vh;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            font-family: 'Pacifico', cursive;
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }
        .languages {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }
        .language-card {
            font-family: 'Pacifico', cursive;
            font-size: 20px;
            padding: 15px 30px;
            background-color: #ffffff;
            border-radius: 50px; /* Cloud shape */
            border: 2px solid #ddd;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .language-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        #activities {
            margin: 30px auto;
            width: 80%;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <h2>Select a Language</h2>
    <div class="languages">
        <div class="language-card" onclick="showActivities(1, 'linear-gradient(135deg,rgb(238, 80, 80),rgb(255, 226, 153))')">French</div>
        <div class="language-card" onclick="showActivities(2, 'linear-gradient(135deg, #76b852,rgb(34, 95, 89))')">English</div>
        <div class="language-card" onclick="showActivities(3, 'linear-gradient(135deg,rgb(234, 129, 218),rgb(65, 53, 111))')">Spanish</div>
        <div class="language-card" onclick="showActivities(4, 'linear-gradient(135deg,rgb(189, 247, 121),rgb(132, 24, 66))')">German</div>
        <div class="language-card" onclick="showActivities(5, 'linear-gradient(135deg, #654ea3, #eaafc8)')">Italian</div>
    </div>

    <div id="activities"></div>

    <script>
        let currentLanguageId = null; // Track the last loaded language ID

        function showActivities(languageId, bgColor) {
            if (currentLanguageId === languageId) return; // Prevent duplicate loading

            currentLanguageId = languageId; // Set current language ID
            document.body.style.background = bgColor; // Change background dynamically

            const activitiesDiv = document.getElementById('activities');
            activitiesDiv.innerHTML = '<div>Loading activities...</div>'; // Clear previous content

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "includes/fetch_activities.php?language_id=" + languageId, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    activitiesDiv.innerHTML = xhr.responseText;
                } else {
                    activitiesDiv.innerHTML = '<div>Failed to load activities. Please try again.</div>';
                }
            };
            xhr.onerror = function () {
                activitiesDiv.innerHTML = '<div>Error occurred while fetching activities. Please check your connection.</div>';
            };
            xhr.send();
        }
    </script>
</body>
</html>
