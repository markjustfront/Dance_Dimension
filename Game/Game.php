<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Game</title>
    <script src="Game.js"></script>
</head>
<body class="laforme" id="BlackBG">
<button class="button-74 glow" onclick="window.location.href='/Menu/Menu.php'">Main Menu</button>

    <fieldset>
        <div class="glow" id="songselector"></div>
        <div class="songInfo">
            <div class="songInfoText">
                <p class="songTitle-child glow"></p>
                <p class="songAuthor-child glow"></p>
            </div>
            <div class="playContainer"></div>
        </div>
    </fieldset>
    <script>
    var songData = <?php echo json_encode(file_get_contents("../1-Song_Data/song_info.json"), JSON_HEX_TAG); ?>;
    document.addEventListener("DOMContentLoaded", function() {
        if (songData && typeof songData.then === 'undefined') {
            loadSongs(JSON.parse(songData));
        } else {
            console.error('Failed to load song data');
        }
    });
    </script>
    <script src="play_enhancer.js"></script>
</body>
</html>