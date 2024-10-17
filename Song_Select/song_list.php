<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Song List</title>
    <script src="../Song_Select/song_select.js"></script>
</head>
<body class="body" id="BlackBG">
<button class="button-74 glow" onclick="location.href = '../Menu/Menu.php';">Main Menu</button>
        <div class="songs-songselector" id="songselector">
            <?php
            $json = file_get_contents("../1-Song_Data/song_info.json");
            $songData = json_decode($json, true);
            ?>
            <script>
                var songData = <?php echo json_encode($songData, JSON_HEX_TAG); ?>;
                document.addEventListener("DOMContentLoaded", function() {
                    loadSongs(songData);
                });
            </script>
        </div>
        <div class="modifyButtons">
            <div>
            <button class="button-74 glow" onclick="location.href = './Upload_Song/upload_song.php'">Upload Song</button>
            </div>
            <button id="edit" class="button-74 glow" onclick="location.href = './Edit_Song/edit_song.php'">Edit</button>
            <button id="remove" class="button-74 glow" onclick="location.href = './delete_song_func.php'">Delete</button>
        </div>
    </div>
    <script src="song_select.js"></script>
</body>
</html>