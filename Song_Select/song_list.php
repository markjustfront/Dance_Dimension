<?php
$jsonFilePath = '../1-Song_Data/Text_Data/song_info.json';
$songsPerPage = 5;

if (!file_exists($jsonFilePath)) {
    die("No song data available.");
}

$songs = json_decode(file_get_contents($jsonFilePath), true);
$totalSongs = count($songs);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $songsPerPage;
$endIndex = min($startIndex + $songsPerPage, $totalSongs);
$songsToDisplay = array_slice($songs, $startIndex, $songsPerPage);

$baseDir = '../1-Song_Data' . DIRECTORY_SEPARATOR;
$dirs = [
    'Songs' => $baseDir . 'Songs' . DIRECTORY_SEPARATOR,
    'Art' => $baseDir . 'Art' . DIRECTORY_SEPARATOR,
    'GameFiles' => $baseDir . 'GameFiles' . DIRECTORY_SEPARATOR,
    'Text_Data' => $baseDir . 'Text_Data' . DIRECTORY_SEPARATOR,
];

function estimateSongDuration($filePath) {
    $fileSize = filesize($filePath);
    $durationMinutes = $fileSize / (1024 * 1024);
    return number_format($durationMinutes, 2) . " min";
}

foreach($songsToDisplay as &$song) {
    $songPath = $dirs['Songs'] . basename($song['song_path']);
    if (!isset($song['duration'])) {
        $song['duration'] = estimateSongDuration($songPath);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song List</title>
</head>
<body id="BlackBG">
    <div class="backbutton">
        <button class="button-74 glow" onclick="window.location.href='/Menu/Menu.html'">Main Menu</button>
        <button class="button-74 glow" id="sound-toggle" onclick="toggleSound()">Enable Sound</button>
    </div>
    
    <div class="song-container">
    <?php foreach($songsToDisplay as $index => $song): ?>
        <div class="song-item" id="computerBG" data-index="<?php echo $startIndex + $index; ?>">
            <img src="<?php echo htmlspecialchars($song['image_path']); ?>" alt="Song Art" class="song-art">
            <div>
                <h3 class="glow"><?php echo htmlspecialchars($song['title']); ?></h3>
                <p class="glow">By: <?php echo htmlspecialchars($song['artist']); ?></p>
                <p class="song-duration glow"><?php echo $song['duration']; ?></p>
                <button class="button-74 glow play-button" data-src="<?php echo $song['song_path']; ?>">Play/Pause</button>
                <button class="button-74 glow go-play" data-index="<?php echo $startIndex + $index; ?>" onclick="selectSong(<?php echo $startIndex + $index; ?>)">Go Play</button>                <a href="edit_song.php?id=<?php echo $index; ?>"><button class="button-74 glow">Edit</button></a>
                <form method="POST" action="delete_song.php" onsubmit="return confirm('Are you sure you want to delete this song?');">
                    <input type="hidden" name="id" value="<?php echo $index; ?>">
                    <button class ="button-74 glow" type="submit">Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="pagination glow fakefieldset">
        <?php 
        $totalPages = ceil($totalSongs / $songsPerPage);
        for ($i = 1; $i <= $totalPages; $i++):
        ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i == $currentPage ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <script>
        function selectSong(index) {
        localStorage.setItem('selectedSongIndex', index);
        localStorage.setItem('songInfoForGame', JSON.stringify(<?php echo json_encode($songs); ?>));
        localStorage.setItem('selectedSongInfo', JSON.stringify(<?php echo json_encode($songs[$index]); ?>));
        window.location.href = '../Game/DDR.php';
    }
    
    function toggleSound() {
        isSoundEnabled = !isSoundEnabled;
        localStorage.setItem('soundEnabled', isSoundEnabled);
        document.getElementById('sound-toggle').innerText = isSoundEnabled ? 'Disable Sound' : 'Enable Sound';
        if (!isSoundEnabled && audioPlayer) {
            audioPlayer.pause();
            currentPlaying = null;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('sound-toggle').innerText = isSoundEnabled ? 'Disable Sound' : 'Enable Sound';
    });

    function selectSong(index) {
        localStorage.setItem('selectedSongIndex', index);
        localStorage.setItem('songInfoForGame', JSON.stringify(<?php echo json_encode($songs); ?>));
        window.location.href = '../Game/DDR.php';
    }

    </script>

    <script src="scripts.js"></script>

    <script>
    let isSoundEnabled = false;
    let audioPlayer = null;
    let currentPlaying = null;

    if (confirm('Are you sure you want to delete this song?')) {
            fetch('delete_song.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to delete song.');
                }
            });
        }
    
    </script>
</body>
</html>