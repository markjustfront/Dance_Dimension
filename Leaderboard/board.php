<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/Short-The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <script src="board.js"></script>

    </head>
    <body id="BlackBG">
        <button class="button-74 glow" onclick="window.location.href='../Menu/Menu.php'">Main Menu</button>
        <fieldset>
        <div class="glow">Leaderboard</div>
            <div class="songselector" id="songselector">
                <?php
                    $json = file_get_contents("../1-Song_Data/song_info.json");
                    echo "<script>loadSongs($json)</script>";
                ?>
            </div>
            <div class="rankingShow" id="rankingShow">
                <table class="rankingList" id="rankingList">
                    <tr>
                        <th id="tableHeader" class="glow">Nickname</th>
                        <th id="tableHeader" class="glow">Score</th>
                    </tr>
                </table>
                <?php
                    $json_ranks = file_get_contents("../1-Song_Data/scores.json");
                    $json_id = file_get_contents("../1-Song_Data/song_info.json");

                    echo "<script>loadRanking($json_ranks, $json_id)</script>";
                ?>
            </div>
        </fieldset>
        <script src="ranking_enhancer.js"></script>
    </body>
</html>