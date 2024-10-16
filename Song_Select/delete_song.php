<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'path/to/your/error_log');

$jsonFilePath = '../1-Song_Data/Text_Data/song_info.json';
$songs = json_decode(file_get_contents($jsonFilePath), true);

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID provided.']);
    exit;
}

if (isset($songs[$id])) {
    $songToDelete = $songs[$id];
    $baseDir = '../1-Song_Data' . DIRECTORY_SEPARATOR;
    
    // Paths for the song, image, and game file
    $songPath = $baseDir . 'Songs' . DIRECTORY_SEPARATOR . basename($songToDelete['song_path']);
    $imagePath = $baseDir . 'Art' . DIRECTORY_SEPARATOR . basename($songToDelete['image_path']);
    $gamePath = $baseDir . 'GameFiles' . DIRECTORY_SEPARATOR . basename($songToDelete['game_path']); // Assuming 'game_path' contains the game file path

    // Attempt to delete the files
    $songDeleted = @unlink($songPath);
    $imageDeleted = @unlink($imagePath);
    $gameDeleted = @unlink($gamePath); // Delete the game file

    // Remove the song from the array
    unset($songs[$id]);

    // Reset array keys
    $songs = array_values($songs);

    if ($songDeleted && $imageDeleted && $gameDeleted && file_put_contents($jsonFilePath, json_encode($songs, JSON_PRETTY_PRINT)) !== false) {
        header('Location: CongratsonDelete.html'); // All operations successful
    } else {
        $errorMessage = 'Failed to delete files or update song list.';
        if (!$songDeleted) $errorMessage .= ' Song file could not be deleted.';
        if (!$imageDeleted) $errorMessage .= ' Image file could not be deleted.';
        if (!$gameDeleted) $errorMessage .= ' Game file could not be deleted.';
        header("Location: song_list.php?error=" . urlencode($errorMessage));
    }
} else {
    $errorMessage = 'Song ID does not exist or deletion failed.';
    header("Location: song_list.php?error=" . urlencode($errorMessage));
}

exit;
?>