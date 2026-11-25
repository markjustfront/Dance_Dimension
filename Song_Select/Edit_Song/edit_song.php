<?php
require dirname(__DIR__, 2) . '/common.php';
dd_head('Edit Song', [
    dd_url('Song_Select/Edit_Song/edit_song.js'),
    dd_url('Song_Select/gamefileHandler.js'),
    dd_url('Song_Select/song_ticktack.js'),
]);

$idParam = $_GET['id'] ?? $_GET['ID'] ?? '';
$songs = dd_load_songs();
$current = null;
foreach ($songs as $song) {
    if ((string) ($song['ID'] ?? '') === (string) $idParam || (string) ($song['title'] ?? '') === (string) $idParam) {
        $current = $song;
        break;
    }
}
$error = isset($_GET['error']) ? 'The chart file was not valid.' : '';
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Song_Select/song_list.php')); ?>">Song List</a>
    </p>
    <?php if ($error): ?>
        <p class="error glow" role="alert"><?php echo dd_h($error); ?></p>
    <?php endif; ?>
    <?php if (!$current): ?>
        <p class="error glow" role="alert">Song not found.</p>
    <?php else: ?>
        <fieldset class="glow">
            <form action="edit_song_func.php" method="post" id="EditForm" enctype="multipart/form-data">
                <input type="hidden" id="ID" name="ID" value="<?php echo dd_h((string) $current['ID']); ?>">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" maxlength="80" required value="<?php echo dd_h((string) $current['title']); ?>">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" maxlength="80" required value="<?php echo dd_h((string) $current['Author']); ?>">

                <label for="songfile">Replace song file (optional)</label>
                <input type="file" id="songfile" name="songfile" accept=".ogg,.oga,.wav,.mp3,audio/ogg,audio/wav,audio/mpeg">
                <label for="portrait">Replace art (optional)</label>
                <input type="file" id="portrait" name="portrait" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <label for="gamefile">Replace chart file (optional)</label>
                <input type="file" id="gamefile" name="gamefile" accept=".txt,text/plain">
                <label for="gamefileArea">Or paste new chart content (optional)</label>
                <textarea id="gamefileArea" name="gamefileArea" rows="8"></textarea>

                <input type="hidden" name="songDuration" id="songDuration" value="<?php echo dd_h((string) ($current['duration'] ?? '0')); ?>">
                <button type="submit" class="button-74 glow">Submit</button>
            </form>
        </fieldset>
    <?php endif; ?>
</main>
</body>
</html>
