<?php
require dirname(__DIR__) . '/common.php';
dd_head('Menu');
?>
<body class="body" id="BlackBG">
<main class="page">
    <fieldset>
        <h1 class="glow">Game Menu</h1>
        <div class="menu-actions">
            <a class="button-74 glow" href="<?php echo dd_h(dd_url('Game/Game.php')); ?>">Begin</a>
            <a class="button-74 glow" href="<?php echo dd_h(dd_url('Song_Select/song_list.php')); ?>">Songs</a>
            <a class="button-74 glow" href="<?php echo dd_h(dd_url('Leaderboard/board.php')); ?>">Leaderboard</a>
        </div>
    </fieldset>
</main>
</body>
</html>
