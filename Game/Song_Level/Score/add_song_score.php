<?php
require dirname(__DIR__, 3) . '/common.php';
dd_session();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'Invalid request method.';
    exit;
}

$nickname = trim((string) ($_POST['nickname'] ?? ''));
$nickname = preg_replace('/\s+/', ' ', $nickname) ?? '';
$songID = (string) ($_POST['songID'] ?? '');
$scoreRaw = (string) ($_POST['score'] ?? '');

if ($nickname === '' || $songID === '' || $scoreRaw === '' || !preg_match('/^-?\d+$/', $scoreRaw)) {
    http_response_code(400);
    echo 'Missing or invalid form data.';
    exit;
}
if (strlen($nickname) > 24) {
    $nickname = substr($nickname, 0, 24);
}

$songs = dd_load_songs();
$known = false;
foreach ($songs as $song) {
    if ((string) ($song['ID'] ?? '') === $songID) {
        $known = true;
        break;
    }
}
if (!$known) {
    http_response_code(400);
    echo 'Unknown song.';
    exit;
}

$score = (int) $scoreRaw;
if ($score < 0) {
    $score = 0;
}

$scores = dd_load_scores();
$key = $songID;
if (!isset($scores[$key]) || !is_array($scores[$key])) {
    $scores[$key] = [];
}
$scores[$key][] = ['nickname' => $nickname, 'score' => (string) $score];
$_SESSION['nickname'] = $nickname;

if (!dd_save_scores($scores)) {
    http_response_code(500);
    echo 'Could not save the score. The server may not have write access.';
    exit;
}

dd_redirect('Game/Game.php');
