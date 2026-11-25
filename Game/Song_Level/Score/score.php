<?php
require dirname(__DIR__, 3) . '/common.php';
dd_session();

$error = '';
$songID = $_GET['songID'] ?? '';
$score = $_GET['score'] ?? '';
$grade = $_GET['gradeLetter'] ?? $_GET['grade'] ?? '';

if ($songID === '' || $score === '') {
    $error = "Required parameters are missing.";
} else {
    if ($grade === '') {
        $grade = 'No Grade';
    }
}

dd_head('Score');
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Game/Game.php')); ?>">Song Select</a>
    </p>
    <fieldset class="score-fieldset">
        <div class="glow">
            <?php if ($error): ?>
                <p class="error" role="alert"><?php echo dd_h($error); ?></p>
            <?php else: ?>
                <form action="add_song_score.php" method="post">
                    <label for="nickname">Nickname</label>
                    <input type="text" id="nickname" class="textarea" name="nickname" maxlength="24" required
                        value="<?php echo dd_h((string) ($_SESSION['nickname'] ?? '')); ?>">
                    <p>Score: <?php echo dd_h((string) $score); ?></p>
                    <p>Grade: <?php echo dd_h((string) $grade); ?></p>
                    <input type="hidden" name="songID" value="<?php echo dd_h((string) $songID); ?>">
                    <input type="hidden" name="score" value="<?php echo dd_h((string) $score); ?>">
                    <input type="hidden" name="grade" value="<?php echo dd_h((string) $grade); ?>">
                    <button type="submit" class="button-74 glow">Submit</button>
                </form>
            <?php endif; ?>
        </div>
    </fieldset>
</main>
</body>
</html>
