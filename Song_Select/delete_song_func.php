<?php
    $songTitle = $_GET["ID"];
    $json = file_get_contents("../1-Song_Data/song_info.json");
    $json = json_decode($json, true);
    $ranking = file_get_contents("../1-Song_Data/scores.json");
    $ranking = json_decode($ranking, true);
    foreach ($json as $key => $value) {
        if ($value["title"] == $songTitle) {
            foreach ($ranking as $k => $v) {
                if ($k == $value["ID"]) {
                    unset($ranking[$k]);
                }
            }
            unlink($value["Art"]);
            unlink($value["SongFile"]);
            unlink($value["GameFile"]);
            unset($json[$key]);
            break;
        }
    }
    $json = array_values($json);
    $ranking = array_values($ranking);
    $json = json_encode($json);
    $ranking = json_encode($ranking);
    file_put_contents("../1-Song_Data/scores.json", $ranking);
    file_put_contents("../1-Song_Data/song_info.json", $json);
    header("Location: song_list.php");
    ?>

<script>
function deleteSong() {
    var selectedSongTitle = getSelected();
    if (selectedSongTitle) {
        location.href = './delete_song_func.php?ID=' + encodeURIComponent(selectedSongTitle);
    } else {
        alert('No song selected');
    }
}
</script>