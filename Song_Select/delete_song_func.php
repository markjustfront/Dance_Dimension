<?php
    $songTitle = $_GET["id"];
    $json = file_get_contents("../DB/songs.json");
    $json = json_decode($json, true);
    $ranking = file_get_contents("../DB/ranks.json");
    $ranking = json_decode($ranking, true);
    foreach ($json as $key => $value) {
        if ($value["title"] == $songTitle) {
            foreach ($ranking as $k => $v) {
                if ($k == $value["ID"]) {
                    unset($ranking[$k]);
                }
            }
            unlink($value["Portrait"]);
            unlink($value["SongFile"]);
            unlink($value["BG"]);
            unlink($value["GameFile"]);
            unset($json[$key]);
            break;
        }
    }
    $json = array_values($json);
    $ranking = array_values($ranking);
    $json = json_encode($json);
    $ranking = json_encode($ranking);
    file_put_contents("../DB/ranks.json", $ranking);
    file_put_contents("../DB/songs.json", $json);
    header("Location: songs.php");