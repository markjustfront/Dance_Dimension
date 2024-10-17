<?php
if (isset($_GET["id"])) {
    // Retrieve song ID from URL parameters
    $songID = $_GET["id"];
    
    // Load song data from JSON file
    $json = file_get_contents("../../1-Song_Data/song_info.json");
    if ($json === false) {
        die("Failed to load song data.");
    }
    
    $jsonData = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("JSON decoding error: " . json_last_error_msg());
    }

    $songFound = false;
    foreach ($jsonData as $song) {
        // Search for the song with matching ID
        if ($song["ID"] == $songID) {
            $songTitle = htmlspecialchars($song["title"]); // Sanitize for XSS prevention
            $songAuthor = htmlspecialchars($song["Author"]);
            $songPortrait = "../" . htmlspecialchars($song["Art"]);
            $songSongFile = "../" . htmlspecialchars($song["SongFile"]);
            $songGameFile = "../" . htmlspecialchars($song["GameFile"]);
            $songFound = true;
            break;
        }
    }

    if (!$songFound) {
        die("Song not found.");
    }
} else {
    die("No song ID provided.");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $songTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <script src="mediaPlayer.js"></script>
</head>

<body id="BlackBG">
    <?php
    // Start the music player with the selected song
    echo "<script>startMusic('../../$songSongFile', $songID);</script>";
    ?>
    <div class="level">
        <!-- Button to return to the main menu -->
        <div class="back-to-menu">
            <button class="button-74 glow" onclick="location.href='../Game.php'">Main Menu</button>
        </div>

        <!-- Game interface for arrow keys -->
        <div class="playMat" id="playMat">
            <div class="playMat-row">
                <div id="playMat-12"></div>
            </div>
            <div class="playMat-row">
                <div id="playMat-11"></div>
                <div id="playMat-14"></div>
            </div>
            <div class="playMat-row">
                <div id="playMat-13"></div>
            </div>
        </div>

        <!-- Display for score and grade -->
        <div class="score ">
            <fieldset>
                <div class="gradeLetter glow" id="gradeLetter">F</div>
                <div class=" glow" id="score">0</div>
            </fieldset>
        </div>

        <!-- Progress bar for the game -->
        <div class="gametime" id="progressBar"></div>

        <!-- Song information display -->
    <fieldset>
        <div class="">
            <div class="song-info">
                <div class="level-songTitle glow"><?php echo $songTitle; ?></div>
                <div class="songAuthor glow"><?php echo $songAuthor; ?></div>
                <img class="songPic-image" alt="Song Art" src="<?php echo $songPortrait ?>">
            </div>
        </div>
</fieldset>

        <!-- Include JavaScript for level gameplay -->
        <script src="level.js"></script>
        <?php
        // Load and parse the game file for the current song
        $file = file_get_contents($songGameFile);
        $file = explode(PHP_EOL, $file);
        $nArrows = $file[0]; // First line contains the number of arrows
        unset($file[0]);
        $lines = [];
        foreach ($file as $content) {
            $line = explode(" # ", $content);
            $lines[] = $line;
        }
        $lines = json_encode($lines);
        echo "<script>getLevel($nArrows, $lines)</script>";
        ?>
    </div>
</body>

</html>