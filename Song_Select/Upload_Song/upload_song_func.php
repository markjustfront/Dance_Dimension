<?php
require dirname(__DIR__, 2) . '/common.php';
dd_require_write('Song_Select/Upload_Song/upload_song.php');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'Invalid request method.';
    exit;
}

$title = trim((string) ($_POST['title'] ?? ''));
$author = trim((string) ($_POST['author'] ?? ''));
$duration = (string) ($_POST['songDuration'] ?? '0');
$method = (string) ($_POST['uploadMethod'] ?? '');

if ($title === '' || $author === '') {
    http_response_code(400);
    echo 'Title and author are required.';
    exit;
}
if (!is_numeric($duration)) {
    $duration = '0';
}
if (!dd_is_allowed_audio($_FILES['songfile'] ?? [])) {
    http_response_code(400);
    echo 'The audio file is missing or not an allowed type/size.';
    exit;
}
if (!dd_is_allowed_image($_FILES['art'] ?? [])) {
    http_response_code(400);
    echo 'The art file is missing or not an allowed type/size.';
    exit;
}

$chartText = '';
if ($method === 'text') {
    $chartText = (string) ($_POST['gamefileArea'] ?? '');
    if (trim($chartText) === '' || strlen($chartText) > DD_MAX_CHART_BYTES) {
        http_response_code(400);
        echo 'Chart text is missing or too large.';
        exit;
    }
} elseif ($method === 'file') {
    if (!dd_is_allowed_chart_upload($_FILES['gamefile'] ?? [])) {
        http_response_code(400);
        echo 'The chart file is missing or not an allowed type/size.';
        exit;
    }
} else {
    http_response_code(400);
    echo 'Choose a chart upload method.';
    exit;
}

$songs = dd_load_songs();
$maxId = -1;
foreach ($songs as $song) {
    $maxId = max($maxId, (int) ($song['ID'] ?? -1));
}
$newId = $maxId + 1;
$uniq = bin2hex(random_bytes(8));

$audioName = $uniq . '_' . dd_sanitize_filename($_FILES['songfile']['name']);
$artName = $uniq . '_' . dd_sanitize_filename($_FILES['art']['name']);
$chartName = $method === 'text'
    ? $uniq . '.txt'
    : $uniq . '_' . dd_sanitize_filename($_FILES['gamefile']['name']);

$audioRel = '1-Song_Data/Songs/' . $audioName;
$artRel = '1-Song_Data/Art/' . $artName;
$chartRel = '1-Song_Data/GameFiles/' . $chartName;

if (!move_uploaded_file($_FILES['songfile']['tmp_name'], dd_fs($audioRel))) {
    http_response_code(500);
    echo 'Could not save the audio file.';
    exit;
}
if (!move_uploaded_file($_FILES['art']['tmp_name'], dd_fs($artRel))) {
    @unlink(dd_fs($audioRel));
    http_response_code(500);
    echo 'Could not save the art file.';
    exit;
}
if ($method === 'text') {
    if (file_put_contents(dd_fs($chartRel), $chartText) === false) {
        @unlink(dd_fs($audioRel));
        @unlink(dd_fs($artRel));
        http_response_code(500);
        echo 'Could not save the chart file.';
        exit;
    }
} elseif (!move_uploaded_file($_FILES['gamefile']['tmp_name'], dd_fs($chartRel))) {
    @unlink(dd_fs($audioRel));
    @unlink(dd_fs($artRel));
    http_response_code(500);
    echo 'Could not save the chart file.';
    exit;
}

$songs[] = [
    'title' => $title,
    'duration' => $duration,
    'Author' => $author,
    'Art' => $artRel,
    'SongFile' => $audioRel,
    'GameFile' => $chartRel,
    'ID' => $newId,
];

if (!dd_save_json('1-Song_Data/song_info.json', array_values($songs))) {
    http_response_code(500);
    echo 'Could not update the song list. The server may not have write access.';
    exit;
}

dd_redirect('Song_Select/song_list.php');
