<?php
$songInfoPath = '../1-Song_Data/Text_Data/song_info.json';
$songInfo = json_decode(file_get_contents($songInfoPath), true);

$index = isset($_GET['songindex']) && is_numeric($_GET['songindex']) ? intval($_GET['songindex']) : 0;
if ($index < 0 || $index >= count($songInfo)) {
    $index = 0;
}

header('Content-Type: application/json');
echo json_encode($songInfo[$index]);
?>