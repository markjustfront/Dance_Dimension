<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$songIndex = isset($_GET['songindex']) ? intval($_GET['songindex']) : 0;
$songs = json_decode(file_get_contents('../1-Song_Data/Text_Data/song_info.json'), true);

if(json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg();
    exit;
}

if($songIndex < 0 || $songIndex >= count($songs)) {
    $songIndex = 0;
}

$songInfo = $songs[$songIndex];

$gameFilePath = $songInfo['game_path'];
$gameFileContent = file_get_contents($gameFilePath);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">    <title>Dance Dimension</title>
</head>
<body id="BlackBG">
<div class="backbutton">
    <button class="button-74 glow" onclick="window.location.href='/Menu/Menu.html'">Main Menu</button>
</div>
<div id="startGameContainer">
    <button id="startGameButton" class="button-74 glow">Start Game</button>
</div>
    <div id="gameArea" class="glow">
        <div id="songInfo">
            <h2 id="songTitle"></h2>
            <p id="artist"></p>
            <div id="playbackBar"><div id="playbackProgress"></div></div>
        </div>
        <div class="key-area" id="keyArea">
        </div>
        <div id="score">Score: <span id="currentScore">0</span></div>
        <div id="rank">Rank: <span id="currentRank">E</span></div>
    </div>

<script src="game.js"></script>
<script>
    const gameEventData = `<?php echo addslashes($gameFileContent); ?>`;
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const songInfo = <?= json_encode($songInfo, JSON_HEX_TAG) ?>;
            
            if(songInfo.title) {
                document.getElementById('songTitle').textContent = songInfo.title;
                document.getElementById('artist').textContent = songInfo.artist;
                
                // Here we'll add the event listener for the start button
                document.getElementById('startButton').addEventListener('click', function() {
                    const audio = new Audio(songInfo.song_path);
                    audio.play().then(() => {
                        audio.onended = endGame; // Assuming endGame is defined in game.js or here
                        setupGameEvents(getGameDataFromMap(songInfo.game_path), audio);
                    }).catch(error => {
                        console.log("Autoplay was prevented:", error);
                        // alert("Please click start again to play the song due to browser autoplay restrictions.");
                    });
                });
            } else {
                console.error('No song selected or data not available.');
                document.getElementById('songTitle').textContent = "Error: Song Data Not Available";
            }
        });
    </script>
    <div class="glow"> 
    <?php
    // Display information loaded on gameplay.
        echo "<pre>";
        var_dump($songInfo);
        echo "</pre>";
    ?>
</div>
</body>
</html>
