<?php
$baseDir = 'Game_Data\\';
$dirs = [
    'Songs' => $baseDir . 'Songs\\',
    'Art' => $baseDir . 'Art\\',
    'GameFiles' => $baseDir . 'GameFiles\\',
    'Text_Data' => $baseDir . 'Text_Data\\',
];

foreach ($dirs as $path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $uploadedFiles = ['image', 'song', 'gameFile'];
    $jsonData = [
        'title' => htmlspecialchars($_POST['title'] ?? ''),
        'artist' => htmlspecialchars($_POST['artist'] ?? ''),
        'description' => htmlspecialchars($_POST['description'] ?? ''),
    ];

    foreach ($uploadedFiles as $fileType) {
        if (isset($_FILES[$fileType])) {
            $file = $_FILES[$fileType];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileError = $file['error'];

            if ($fileError === 0) {
                if (is_uploaded_file($fileTmpName)) {
                    $newFileName = uniqid('', true) . '_' . $fileName;
                    $dir = $fileType === 'gameFile' ? $dirs['GameFiles'] : ($fileType === 'image' ? $dirs['Art'] : $dirs['Songs']);

                    if (move_uploaded_file($fileTmpName, $dir . $newFileName)) {
                        $jsonData[$fileType . '_path'] = $dir . $newFileName; // Store the full path for JSON
                    } else {
                        $errors[] = "Failed to move the uploaded file $fileName.";
                    }
                } else {
                    $errors[] = "File $fileName was not uploaded via HTTP POST.";
                }
            } else {
                $errors[] = "Error uploading $fileName: Error code $fileError";
            }
        }
    }

    if (empty($errors)) {
        $jsonFilePath = $dirs['Text_Data'] . 'song_info.json';
        
        $existingData = file_exists($jsonFilePath) ? json_decode(file_get_contents($jsonFilePath), true) : [];
        $existingData[] = $jsonData;
        
        if (file_put_contents($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT)) !== false) {
            echo "Data saved to JSON successfully.<br>";
            echo "<script type='text/javascript'>";
            echo "window.location.href = 'Congrats.html';";
            echo "</script>";
            exit;
        } else {
            $errors[] = "Failed to write to JSON file.<br>";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    echo "No POST data was sent or there was an issue with the form submission.";
}
?>
