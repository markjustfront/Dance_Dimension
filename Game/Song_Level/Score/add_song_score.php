<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Sanitize and validate inputs
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = isset($_POST["nickname"]) ? sanitizeInput($_POST["nickname"]) : '';
    $songID = isset($_POST["songID"]) ? filter_var($_POST["songID"], FILTER_SANITIZE_NUMBER_INT) : '';
    $score = isset($_POST["score"]) ? filter_var($_POST["score"], FILTER_SANITIZE_NUMBER_INT) : '';

    // Debug check
    echo "Received: Nickname: $nickname, Song ID: $songID, Score: $score<br>";

    if (!$nickname || $songID === '' || $score === '') {
        die("Missing required POST data.");
    }

    // Path to JSON file
    $filePath = "../../../1-Song_Data/scores.json";

    // Read existing scores or initialize if the file does not exist or is empty
    $fileContent = @file_get_contents($filePath);
    if ($fileContent === false) {
        // If file read fails, handle error
        die("Cannot read the scores file.");
    }

    $jsonData = json_decode($fileContent, true);
    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
        die("Failed to decode JSON: " . json_last_error_msg());
    }

    if (!isset($jsonData[$songID])) {
        $jsonData[$songID] = [];
    }

    // Add new score
    $jsonData[$songID][] = ["nickname" => $nickname, "score" => $score];

    // Optionally sort scores here or where needed

    // Write updated scores back to file
    $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
    if ($jsonString === false) {
        die("Failed to encode JSON.");
    }

    if (file_put_contents($filePath, $jsonString) === false) {
        die("Failed to write to scores file.");
    }

    header("Location: ../../Game.php");
    exit;
} else {
    die("Invalid request method.");
}
ob_end_flush();
?>