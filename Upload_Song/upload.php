<?php
// Refernce of the directories
$baseDir = '../1-Song_Data' . DIRECTORY_SEPARATOR;
$dirs = [
    'Songs' => $baseDir . 'Songs' . DIRECTORY_SEPARATOR,
    'Art' => $baseDir . 'Art' . DIRECTORY_SEPARATOR,
    'GameFiles' => $baseDir . 'GameFiles' . DIRECTORY_SEPARATOR,
    'Text_Data' => $baseDir . 'Text_Data' . DIRECTORY_SEPARATOR,
];

// In case of the directories where elimnated, they will reapear uppon new song upload
foreach ($dirs as $path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

$errors = [];

function validateGameFile($content) {
    $lines = explode("\n", trim((string)$content)); 
    if (count($lines) < 2) return false;
    $elementCount = intval(array_shift($lines));
    if ($elementCount != count($lines)) return false;

    foreach ($lines as $line) {
        if (!preg_match('/^(\d+) # (\p{L}|[\x21-\x7E]) # (\d+(?:\.\d+)?) # (\d+(?:\.\d+)?)(?: # (.*))?$/u', trim($line), $matches)) {
            return false;
        }
    }
    return true;
}

function safeMoveUploadedFile($tmp_name, $target_path) {
    if (!is_dir(dirname($target_path))) {
        mkdir(dirname($target_path), 0777, true);
    }
    if (move_uploaded_file($tmp_name, $target_path)) {
        return true;
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $artist = $_POST['artist'] ?? '';
    $description = $_POST['description'] ?? '';
    $songFile = $_FILES['song']['name'] ?? '';
    $imageFile = $_FILES['image']['name'] ?? '';
    $songFeel = $_POST['songFeel'] ?? '';

    $uploadMethod = $_POST['uploadMethod'] ?? '';
    $gameFileContent = $_POST['textgameFile'] ?? '';
    $gameFileName = '';  // Initialize game file name to use later

    // Determine which upload method to process
    if ($uploadMethod == 'file') {
        if (isset($_FILES['gameFile']) && $_FILES['gameFile']['error'] == UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['gameFile']['name']);
            $targetPath = $dirs['GameFiles'] . $fileName;
            
            if (safeMoveUploadedFile($_FILES['gameFile']['tmp_name'], $targetPath)) {
                echo "The file $fileName has been uploaded to GameFiles.<br>";
                $gameFileName = $fileName;  // Set the game file name for metadata
            } else {
                $errors[] = "There was an error uploading the game file, please try again.";
            }
        } else {
            $errors[] = "No game file was selected for upload.";
        }
    } elseif ($uploadMethod == 'text') {
        if (trim($gameFileContent) !== '') {
            if (validateGameFile($gameFileContent)) {
                $fileName = uniqid('text_') . '.txt';
                $targetPath = $dirs['GameFiles'] . $fileName;
                if (file_put_contents($targetPath, $gameFileContent) !== false) {
                    echo "Text content has been saved as $fileName in GameFiles.<br>";
                    $gameFileName = $fileName;  // Set the game file name for metadata
                } else {
                    $errors[] = "There was an error saving the text game file.";
                }
            } else {
                $errors[] = "Invalid game file format.";
            }
        } else {
            $errors[] = "Text entry cannot be empty.";
        }
    } else {
        $errors[] = "Please choose an upload method.";
    }

    // Handle other uploads
    if ($_FILES['song']['error'] !== UPLOAD_ERR_OK || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading song or image files.";
    } else {
        move_uploaded_file($_FILES['song']['tmp_name'], $dirs['Songs'] . $songFile);
        move_uploaded_file($_FILES['image']['tmp_name'], $dirs['Art'] . $imageFile);
    }

    if (empty($errors)) {
        $metaData = [
            'title' => $title,
            'artist' => $artist,
            'description' => $description,
            'image_path' => $dirs['Art'] . $imageFile,
            'song_path' => $dirs['Songs'] . $songFile,
            'game_path' => $dirs['GameFiles'] . (isset($gameFileName) ? $gameFileName : $fileName)
        ];
    
        $metaFileName = uniqid('meta_', true) . '.txt';
        file_put_contents($dirs['Text_Data'] . $metaFileName, json_encode($metaData));
    
        // Update playlist JSON
        $playlist = json_decode(file_get_contents($dirs['Text_Data'] . 'song_info.json'), true) ?? [];
        $playlist[] = $metaData;
        file_put_contents($dirs['Text_Data'] . 'song_info.json', json_encode($playlist, JSON_PRETTY_PRINT));
        ?>
        <script>
            setTimeout(function(){
                window.location.href = 'Congrats.html';
            }, 3000); // Redirect after 3 seconds
        </script>
        <?php
    } else {
        // If there are errors, just output them
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>