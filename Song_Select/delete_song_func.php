<?php
require dirname(__DIR__) . '/common.php';
dd_require_write('Song_Select/song_list.php');

$idParam = (string) ($_GET['id'] ?? $_GET['ID'] ?? '');
if ($idParam === '') {
    dd_redirect('Song_Select/song_list.php');
}

$songs = dd_load_songs();
$found = null;
$remain = [];
foreach ($songs as $song) {
    $idMatch = (string) ($song['ID'] ?? '') === $idParam;
    $titleMatch = (string) ($song['title'] ?? '') === $idParam;
    if ($found === null && ($idMatch || $titleMatch)) {
        $found = $song;
        continue;
    }
    $remain[] = $song;
}

if ($found === null) {
    dd_redirect('Song_Select/song_list.php');
}

foreach (['Art', 'SongFile', 'GameFile'] as $key) {
    $rel = (string) ($found[$key] ?? '');
    if ($rel !== '' && strpos($rel, '1-Song_Data/') === 0 && is_file(dd_fs($rel))) {
        @unlink(dd_fs($rel));
    }
}

$scores = dd_load_scores();
$scoreKey = (string) ($found['ID'] ?? '');
if ($scoreKey !== '' && isset($scores[$scoreKey])) {
    unset($scores[$scoreKey]);
}

if (!dd_save_json('1-Song_Data/song_info.json', $remain) || !dd_save_scores($scores)) {
    http_response_code(500);
    echo 'Could not update the library. The server may not have write access.';
    exit;
}

dd_redirect('Song_Select/song_list.php');
