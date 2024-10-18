<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start(); 
include "../gameValidator.php";

// Substitueix el teu codi existent aquí amb el següent bloc:
if (!isset($_FILES["songfile"])) {
    $valid = gameValidation($_POST["gamefileArea"], $_POST["songDuration"]);
} else {
    if ($_POST['uploadMethod'] == 'file' && isset($_FILES["gamefile"]) && $_FILES["gamefile"]["error"] == UPLOAD_ERR_OK) {
        $gameFileContent = file_get_contents($_FILES["gamefile"]["tmp_name"]);
        if ($gameFileContent === false) {
            die('Could not read the game file.');
        }
        $valid = gameValidation($gameFileContent, $_POST["songDuration"]);
    } elseif ($_POST['uploadMethod'] == 'text') {
        $valid = gameValidation($_POST["gamefileArea"], $_POST["songDuration"]);
    } else {
        // Pot ser necessari manejar el cas on no s'ha seleccionat ni fitxer ni text
        die('No game file method selected or file upload failed.');
    }
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