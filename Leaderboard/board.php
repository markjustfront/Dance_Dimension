<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <script src="board.js"></script>

    </head>
    <body class="body" id="BlackBG">
        <button class="button-74 glow" onclick="window.location.href='../Menu/Menu.php'">Main Menu</button>
        <fieldset>
        <h1 class="glow">Songs Listed</h1>
            <div class="songselector glow" id="songselector">
                <?php
                    $json = file_get_contents("../1-Song_Data/song_info.json");
                    echo "<script>loadSongs($json)</script>";
                ?>
            </div>
            </fieldset>
            <fieldset>
            <h1 class="glow">Leaderboard</h1>

            <div class="scoreShow" id="scoreShow">
                <table class="scoreList glow" id="scoreList">
                </table>
                <?php
                    $json_ranks = file_get_contents("../1-Song_Data/scores.json");
                    $json_id = file_get_contents("../1-Song_Data/song_info.json");

                    echo "<script>loadScore($json_ranks, $json_id)</script>";
                ?>
            </div>
        </fieldset>
    </body>
</html>