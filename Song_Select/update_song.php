<?php
$jsonFilePath = '../1-Song_Data/Text_Data/song_info.json';
$songs = json_decode(file_get_contents($jsonFilePath), true);

$id = $_POST['id'];

if (isset($songs[$id])) {
    // Update existing fields
    $songs[$id]['title'] = $_POST['title'];
    $songs[$id]['artist'] = $_POST['artist'];
    $songs[$id]['description'] = $_POST['description'] ?? $songs[$id]['description']; // Keep existing if not updated

    // Define upload directories
    $uploadDir = '../1-Song_Data/';
    $songDir = $uploadDir . 'Songs/';
    $imageDir = $uploadDir . 'Art/';
    $gameDir = $uploadDir . 'GameFiles/';

    // Ensure directories exist
    foreach ([$uploadDir, $songDir, $imageDir, $gameDir] as $dir) {
        if (!is_dir($dir)) mkdir($dir, 0755, true);
    }

    function uploadFile($fileInputName, $targetDir, $currentPath) {
        if (!empty($_FILES[$fileInputName]['name'])) {
            $fileName = $_FILES[$fileInputName]['name'];
            $fileTmpName = $_FILES[$fileInputName]['tmp_name'];
            $newFileName = uniqid() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $fileDestination = $targetDir . $newFileName;
            
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                return $fileDestination;
            } else {
                echo "Failed to upload the file.";
                return null;
            }
        }
        return $currentPath; // Return the current path if no new file was uploaded
    }

    // Handle updates for image and game files only if new files are uploaded
    $songs[$id]['image_path'] = uploadFile('image', $imageDir, $songs[$id]['image_path'] ?? '');
    $songs[$id]['game_path'] = uploadFile('gameFile', $gameDir, $songs[$id]['game_path'] ?? '');

    // Handle game content if method is 'text'
    if ($_POST['uploadMethod'] == 'text') {
        $gameContent = $_POST['textgameFile'];
        $gameContentPath = $gameDir . 'text_' . uniqid() . '.txt';
        file_put_contents($gameContentPath, $gameContent);
        $songs[$id]['game_path'] = $gameContentPath; // Update or keep the game_path to point to the new or existing text file
    }

    // Write back to JSON file
    file_put_contents($jsonFilePath, json_encode($songs, JSON_PRETTY_PRINT));
    
    header("Location: song_list.php");
    exit();
} else {
    echo "Song not found.";
    exit();
}
?>