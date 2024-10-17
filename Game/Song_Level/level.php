<?php
if (isset($_GET["id"])) {
    $songID = $_GET["id"];
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
        if ($song["ID"] == $songID) {
            $songTitle = htmlspecialchars($song["title"]);  // Basic XSS prevention
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
    <script src="musicHandler.js"></script>
</head>

<body id="BlackBG">
    <?php
    echo "<script>startMusic('../../$songSongFile', $songID);</script>";
    ?>
    <div class="level">
        <!-- Main Menu Button -->
        <div class="back-to-menu">
            <button class="button-74 glow" onclick="location.href='../Game.php'">Main Menu</button>
        </div>

        <!-- Keys at the top -->
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

        <!-- Score in the middle -->
        <div class="score ">
            <fieldset>
                <div class="gradeLetter glow" id="gradeLetter">F</div>
                <div class=" glow" id="score">0</div>
            </fieldset>
        </div>

        <!-- Progress Bar -->
        <div class="gametime" id="progressBar"></div>

        <!-- Song Information at the bottom -->
        <div class="song_level">
            <div class="song-info">
                <div class="level-songTitle glow"><?php echo $songTitle; ?></div>
                <div class="songAuthor glow"><?php echo $songAuthor; ?></div>
                <img class="songPic-image" alt="Song Art" src="<?php echo $songPortrait ?>">
            </div>
        </div>

        <script src="level.js"></script>
        <?php
        $file = file_get_contents($songGameFile);
        $file = explode(PHP_EOL, $file);
        $nArrows = $file[0];
        unset($file[0]);
        $lines = [];
        foreach ($file as $content) {
            $line = explode(" # ", $content);
            $lines[] = $line;
        }
        $lines = json_encode($lines);
        echo "<script>getLevel($nArrows, $lines)</script>"
        ?>
    </div>
</body>

</html>