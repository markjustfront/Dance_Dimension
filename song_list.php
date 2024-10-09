<?php
$jsonFilePath = 'Game_Data/Text_Data/song_info.json';
$songsPerPage = 5;

// Check if the JSON file exists
if (!file_exists($jsonFilePath)) {
    die("No song data available.");
}

// Read the JSON data
$songs = json_decode(file_get_contents($jsonFilePath), true);
$totalSongs = count($songs);

// Determine the current page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $songsPerPage;
$endIndex = $startIndex + $songsPerPage;

// Ensure we don't go out of bounds
$endIndex = min($endIndex, $totalSongs);

// Slice the array for pagination
$songsToDisplay = array_slice($songs, $startIndex, $songsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/png" href="Media/The Dance Dimension.png"
    <meta charset="UTf-8">
        <title>Song List</title>
</head>
<body id="computerBG">
        <div class="backbutton">
        <button class="button-74 glow" onclick="window.location.href='Menu.html'">Main Menu</button>
<?php foreach($songsToDisplay as $song): ?>
<div class="song-item">
    <img src="<?php echo htmlspecialchars($song['image_path']); ?>" alt="Song Art" class="song-art">
    <div>
        <h3 class="glow"><?php echo htmlspecialchars($song['title']); ?></h3>
        <p class="glow">By: <?php echo htmlspecialchars($song['artist']); ?></p>
        <audio controls preload="metadata">
            <source src="<?php echo htmlspecialchars($song['song_path']); ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>
</div>
<?php endforeach; ?>

<div class="pagination glow">
    <?php 
    $totalPages = ceil($totalSongs / $songsPerPage);
    for ($i = 1; $i <= $totalPages; $i++):
    ?>
        <a href="?page=<?php echo $i; ?>" <?php echo $i == $currentPage ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
    <?php endfor; ?>
</div>

</body>
</html>