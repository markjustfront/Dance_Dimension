<?php

include "../gameValidator.php";
if (!isset($_FILES["gamefile"])) {
} else {
    $file = file_get_contents($_FILES["gamefile"]["tmp_name"]);
}
if ($valid  == false) {
    header("Location: editsong.php?error=1&title=" . $_POST["title"] . "&author=" . $_POST["author"] . "&id=" . $_POST["ID"]);
    exit();
}

$songfileDir = "../SONGS/";
$gamefileDir = "../SONGS/GameFiles/";
$portraitfileDir = "../SONGS/Portraits/";
$bgvideoDir = "../SONGS/BgVideos/";

$json = file_get_contents("../../DB/songs.json");
$json = json_decode($json, true);
foreach ($json as $key => $value) {
    if ($value["ID"] == $_POST["ID"]) {
        if (isset($_FILES["gamefile"])) {
            $edited = [
                "title" => $_POST["title"],
                "duration" => $_POST["songDuration"],
                "Author" => $_POST["author"],
                "ID" => $_POST["ID"]
            ];
        } else {
            $edited = [
                "title" => $_POST["title"],
                "duration" => $_POST["songDuration"],
                "Author" => $_POST["author"],
                "ID" => $_POST["ID"]
            ];
            file_put_contents("../" . $value["GameFile"], $_POST["gamefileArea"]);
        }
        $gamefileDir = $value["GameFile"];
        $songfileDir = $value["SongFile"];
        $portraitfileDir = $value["Portrait"];
        $bgvideoDir = $value["BG"];
        $edited = [
            "title" => $edited["title"],
            "duration" => $edited["duration"],
            "Author" => $edited["Author"],
            "Portrait" => $portraitfileDir,
            "SongFile" => $songfileDir,
            "GameFile" => $gamefileDir,
            "ID" => $edited["ID"]
        ];
        $json[$key] = $edited;
        break;
    }
}

$json = json_encode($json);
print_r($json);
file_put_contents("../../DB/songs.json", $json);

if (isset($_FILES["gamefile"])) {
    move_uploaded_file($_FILES["gamefile"]["tmp_name"], "../" . $gamefileDir);
}
move_uploaded_file($_FILES["songfile"]["tmp_name"], "../" . $songfileDir);
move_uploaded_file($_FILES["bgvideo"]["tmp_name"], "../" . $bgvideoDir);
move_uploaded_file($_FILES["portrait"]["tmp_name"], "../" . $portraitfileDir);

header("Location: ../songs.php");
