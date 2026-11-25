<?php
require dirname(__DIR__, 2) . '/common.php';

$songID = $_GET['id'] ?? '';
if ($songID === '') {
    http_response_code(400);
    dd_head('Game');
    echo "<body id=\"BlackBG\"><main class=\"page\"><p class=\"error glow\">No song ID provided.</p></main></body></html>";
    exit;
}

$current = null;
foreach (dd_load_songs() as $song) {
    if ((string) ($song['ID'] ?? '') === (string) $songID) {
        $current = $song;
        break;
    }
}
if (!$current) {
    http_response_code(404);
    dd_head('Game');
    echo "<body id=\"BlackBG\"><main class=\"page\"><p class=\"error glow\">Song not found.</p></main></body></html>";
    exit;
}

$songTitle = (string) $current['title'];
$songAuthor = (string) ($current['Author'] ?? '');
$songPortrait = dd_url((string) $current['Art']);
$songSongFile = dd_url((string) $current['SongFile']);
$chartPath = dd_fs((string) $current['GameFile']);

$nArrows = 0;
$lines = [];
if (is_file($chartPath)) {
    $raw = file_get_contents($chartPath);
    if ($raw !== false) {
        $file = preg_split("/\r\n|\n|\r/", $raw) ?: [];
        $nArrows = (int) array_shift($file);
        foreach ($file as $content) {
            $content = trim($content);
            if ($content === '') {
                continue;
            }
            $parts = array_map('trim', explode('#', $content));
            if (count($parts) >= 3) {
                $lines[] = [$parts[0], $parts[1], $parts[2]];
            }
        }
        if ($nArrows <= 0) {
            $nArrows = count($lines);
        }
        $nArrows = min($nArrows, count($lines));
    }
}

dd_head($songTitle, [
    dd_url('Game/Song_Level/mediaPlayer.js'),
]);
?>
<body id="BlackBG">
<main class="page level">
    <p class="nav-row back-to-menu">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Game/Game.php')); ?>">Song Select</a>
    </p>

    <div class="playMat" id="playMat">
        <div class="playMat-row">
            <button type="button" class="pad" id="playMat-12" data-dir="12" aria-label="Up"></button>
        </div>
        <div class="playMat-row">
            <button type="button" class="pad" id="playMat-11" data-dir="11" aria-label="Left"></button>
            <button type="button" class="pad" id="playMat-14" data-dir="14" aria-label="Right"></button>
        </div>
        <div class="playMat-row">
            <button type="button" class="pad" id="playMat-13" data-dir="13" aria-label="Down"></button>
        </div>
    </div>

    <div class="score">
        <fieldset>
            <div class="gradeLetter glow" id="gradeLetter">F</div>
            <div class="glow" id="score">0</div>
        </fieldset>
    </div>

    <div class="gametime" id="progressBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>

    <fieldset>
        <div class="song-info">
            <div class="level-songTitle glow"><?php echo dd_h($songTitle); ?></div>
            <div class="songAuthor glow"><?php echo dd_h($songAuthor); ?></div>
            <img class="songPic-image" alt="<?php echo dd_h($songTitle . ' art'); ?>" src="<?php echo dd_h($songPortrait); ?>">
        </div>
    </fieldset>

    <script src="<?php echo dd_h(dd_url('Game/Song_Level/level.js')); ?>"></script>
    <script>
    startMusic(<?php echo dd_json($songSongFile); ?>, <?php echo dd_json($songID); ?>);
    getLevel(<?php echo (int) $nArrows; ?>, <?php echo dd_json($lines); ?>);
    </script>
</main>
</body>
</html>
