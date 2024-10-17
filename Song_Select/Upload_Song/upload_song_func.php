<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start(); 
include "../gameValidator.php";

if (!isset($_FILES["songfile"])) {
    $valid = gameValidation($_POST["gamefileArea"], $_POST["songDuration"]);
} else {
    $valid = gameValidation(file_get_contents($_FILES["gamefile"]["tmp_name"]), $_POST["songDuration"]);
}

if ($valid == false) {
    header("Location: upload_song.php?error=1&title=" . $_POST["title"] . "&author=" . $_POST["author"]);
    exit();
}

$uniq = uniqid();

$songfileDir = "../1-Song_Data/Songs/";
$gamefileDir = "../1-Song_Data/GameFiles/";
$artfileDir = "../1-Song_Data/Art/";

$json = file_get_contents("../../1-Song_Data/song_info.json");
$json = json_decode($json, true);

$newSong = [
    "title" => $_POST["title"],
    "duration" => $_POST["songDuration"],
    "Author" => $_POST["author"],
    "Art" => $artfileDir . $uniq . basename($_FILES["art"]["name"]),
    "SongFile" => $songfileDir . $uniq . basename($_FILES["songfile"]["name"]),
    "ID" => end($json)["ID"] + 1,
];

if ($_POST['uploadMethod'] == 'text') {
    $newSong["GameFile"] = $gamefileDir . $uniq . ".txt";
} else {
    $newSong["GameFile"] = $gamefileDir . $uniq . basename($_FILES["gamefile"]["name"]);
}

array_push($json, $newSong);
$json = json_encode($json);

file_put_contents("../../1-Song_Data/song_info.json", $json);

move_uploaded_file($_FILES["songfile"]["tmp_name"], "../" . $newSong["SongFile"]);
move_uploaded_file($_FILES["art"]["tmp_name"], "../" . $newSong["Art"]);

if ($_POST['uploadMethod'] == 'text') {
    file_put_contents("../" . $newSong["GameFile"], $_POST["gamefileArea"]);
} else {
    move_uploaded_file($_FILES["gamefile"]["tmp_name"], "../" . $newSong["GameFile"]);
}

header("Location: ../song_list.php");
ob_end_flush(); // Flush the output buffer
exit();
?>