<?php
require __DIR__ . '/common.php';
dd_session();

$token = (string) (dd_config()['write_token'] ?? '');
$next = (string) ($_GET['next'] ?? $_POST['next'] ?? 'Song_Select/song_list.php');
$next = ltrim(str_replace('\\', '/', $next), '/');
if ($next === '' || str_contains($next, '..')) {
    $next = 'Song_Select/song_list.php';
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $given = (string) ($_POST['write_token'] ?? '');
    if ($token !== '' && hash_equals($token, $given)) {
        $_SESSION['dd_write_ok'] = true;
        dd_redirect($next);
    }
    $error = 'That token did not match.';
}

dd_head('Unlock library edits');
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Menu/Menu.php')); ?>">Main Menu</a>
    </p>
    <fieldset>
        <h1 class="glow">Unlock edits</h1>
        <?php if ($token === ''): ?>
            <p>No write token is configured, so library edits are open on this copy.</p>
        <?php else: ?>
            <?php if ($error): ?><p class="error" role="alert"><?php echo dd_h($error); ?></p><?php endif; ?>
            <form method="post">
                <label for="write_token">Write token</label>
                <input type="password" id="write_token" name="write_token" required>
                <input type="hidden" name="next" value="<?php echo dd_h($next); ?>">
                <button type="submit" class="button-74 glow">Unlock</button>
            </form>
        <?php endif; ?>
    </fieldset>
</main>
</body>
</html>
