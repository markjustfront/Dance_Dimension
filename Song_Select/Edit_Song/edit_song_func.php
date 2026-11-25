<?php
require dirname(__DIR__, 2) . '/common.php';
dd_require_write('Song_Select/song_list.php');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'Invalid request method.';
    exit;
}

$id = (string) ($_POST['ID'] ?? '');
$title = trim((string) ($_POST['title'] ?? ''));
$author = trim((string) ($_POST['author'] ?? ''));
$duration = (string) ($_POST['songDuration'] ?? '');

if ($id === '' || $title === '' || $author === '') {
    http_response_code(400);
    echo 'Title, author, and song ID are required.';
    exit;
}

$songs = dd_load_songs();
$index = null;
foreach ($songs as $i => $song) {
    if ((string) ($song['ID'] ?? '') === $id) {
        $index = $i;
        break;
    }
}
if ($index === null) {
    http_response_code(404);
    echo 'Song not found.';
    exit;
}

$song = $songs[$index];
if (is_numeric($duration) && (float) $duration > 0) {
    $song['duration'] = $duration;
}
$song['title'] = $title;
$song['Author'] = $author;

$audio = $_FILES['songfile'] ?? [];
if (($audio['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (!dd_is_allowed_audio($audio)) {
        http_response_code(400);
        echo 'The audio file is not an allowed type/size.';
        exit;
    }
    $audioRel = '1-Song_Data/Songs/' . bin2hex(random_bytes(8)) . '_' . dd_sanitize_filename($audio['name']);
    if (!move_uploaded_file($audio['tmp_name'], dd_fs($audioRel))) {
        http_response_code(500);
        echo 'Could not save the audio file.';
        exit;
    }
    $old = (string) ($song['SongFile'] ?? '');
    $song['SongFile'] = $audioRel;
    if ($old !== '' && is_file(dd_fs($old)) && strpos($old, '1-Song_Data/Songs/') === 0) {
        @unlink(dd_fs($old));
    }
}

$art = $_FILES['portrait'] ?? [];
if (($art['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (!dd_is_allowed_image($art)) {
        http_response_code(400);
        echo 'The art file is not an allowed type/size.';
        exit;
    }
    $artRel = '1-Song_Data/Art/' . bin2hex(random_bytes(8)) . '_' . dd_sanitize_filename($art['name']);
    if (!move_uploaded_file($art['tmp_name'], dd_fs($artRel))) {
        http_response_code(500);
        echo 'Could not save the art file.';
        exit;
    }
    $old = (string) ($song['Art'] ?? '');
    $song['Art'] = $artRel;
    if ($old !== '' && is_file(dd_fs($old)) && strpos($old, '1-Song_Data/Art/') === 0) {
        @unlink(dd_fs($old));
    }
}

$chartFile = $_FILES['gamefile'] ?? [];
$chartText = (string) ($_POST['gamefileArea'] ?? '');
if (($chartFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (!dd_is_allowed_chart_upload($chartFile)) {
        header('Location: edit_song.php?id=' . rawurlencode($id) . '&error=1', true, 302);
        exit;
    }
    $dest = dd_fs((string) $song['GameFile']);
    if (!move_uploaded_file($chartFile['tmp_name'], $dest)) {
        http_response_code(500);
        echo 'Could not save the chart file.';
        exit;
    }
} elseif (trim($chartText) !== '') {
    if (strlen($chartText) > DD_MAX_CHART_BYTES) {
        header('Location: edit_song.php?id=' . rawurlencode($id) . '&error=1', true, 302);
        exit;
    }
    if (file_put_contents(dd_fs((string) $song['GameFile']), $chartText) === false) {
        http_response_code(500);
        echo 'Could not save the chart file.';
        exit;
    }
}

$songs[$index] = $song;
if (!dd_save_json('1-Song_Data/song_info.json', array_values($songs))) {
    http_response_code(500);
    echo 'Could not update the song list. The server may not have write access.';
    exit;
}

dd_redirect('Song_Select/song_list.php');
