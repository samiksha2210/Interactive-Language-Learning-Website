
<?php
$host = "localhost";
$user = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "language_learning";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Database Connected Successfully!";
?>