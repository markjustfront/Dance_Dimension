<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Edit Song</title>
        <script src="editsong.js"></script>
    </head>
    <body>
        <?php
            if (isset($_GET["error"])) {
                echo "<div class='error'>";
                if ($_GET["error"] == 1) {
                    echo "Invalid game file.";
                }
                echo "</div>";
            }
        ?>
        <div class="addForm">
            <form action="songEditFunction.php" method="post" id="EditForm" enctype="multipart/form-data">
                <label for="songfile">Song file</label>
                <input type="file" id="songfile" name="songfile" accept="audio/mp3" required>
                <label for="gamefile">Game file</label>
                <input type="file" id="gamefile" name="gamefile" accept=".txt" required>
                <textarea id="gamefileArea" name="gamefileArea" required></textarea>
                <script src="../gamefileHandler.js"></script>
                <label for="portrait">Portrait</label>
                <input type="file" id="portrait" name="portrait" accept="image/png" required>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
                <input type="text" id="ID" name="ID" style="display: none;">
                <?php
                    $json = file_get_contents("../../1-Song_Data/song_info.json");
                    $song = $_GET["id"];
                    $json = json_decode($json, true);
                    foreach ($json as $key => $value) {
                        if ($value["ID"] == $song || $value["title"] == $song) {
                            $t = $value["title"];
                            $d = $value["duration"];
                            $a = $value["Author"];
                            $id = $value["ID"];
                            echo "<script>loadEditValues('$t', '$a', '$id')</script>";
                        }
                    }
                    
                ?>
                <label for="bgvideo">BG Video</label>
                <input type="file" id="bgvideoInput" name="bgvideo" accept="video/mp4" required>
                <input type="hidden" name="songDuration" id="songDuration" value="<?php echo $d?>">
                <script src="../SongDurationHandler.js"></script>
                <input type="submit" value="">
            </form>
            <div class="backArrow" onclick="location.href='../songs.php'">
                <img class="backArrow-image" alt="Back Arrow" src="../../RESOURCES/BackArrowBlue.png">

            </div>
        </div>
    </body>
</html>