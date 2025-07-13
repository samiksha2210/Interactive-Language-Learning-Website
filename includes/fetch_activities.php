<?php
include '../db_connect.php';

if (isset($_GET['language_id'])) {
    $language_id = intval($_GET['language_id']);
    
    $query = "SELECT * FROM activities WHERE language_id = $language_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<style>
            h3 {
                text-align: center;
                font-family: 'Pacifico', cursive;
                font-size: 24px;
                font-weight: 700;
                color: #333;
            }
            .activities-container {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 15px;
                padding: 20px;
            }
            .activity-btn {
                text-decoration: none;
                background: #ffffff;
                color: black;
                padding: 12px 25px;
                border-radius: 50px;
                border: 2px solid #ddd;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                cursor: pointer;
                transition: transform 0.3s, box-shadow 0.3s;
                font-family: 'Pacifico', cursive;
                font-size: 16px;
                font-weight: bold;
                display: inline-block;
            }
            .activity-btn:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
               
            }
        </style>";

        echo "<h3 style='text-align: center; margin-bottom: 20px;'>Available Activities:</h3>";
        echo "<div class='activities-container'>";
        while ($activity = $result->fetch_assoc()) {
            echo "<a href='activity_page.php?id=" . $activity['id'] . "' class='activity-btn'>" . htmlspecialchars($activity['title']) . "</a>";
        }
        echo "</div>";
    } else {
        echo "<p style='text-align: center;'>No activities found for this language.</p>";
    }
}
?>
