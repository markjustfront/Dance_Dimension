<?php
require dirname(__DIR__) . '/common.php';
dd_head('Game', [
    dd_url('Game/game.js'),
    dd_url('Game/Song_Level/play_enchancer.js'),
]);
$songs = dd_load_songs();
?>
<body class="laforme" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Menu/Menu.php')); ?>">Main Menu</a>
    </p>
    <fieldset>
        <div class="glow" id="songselector"></div>
        <div class="songInfo">
            <div class="songInfoText">
                <p class="songTitle-child glow"></p>
                <p class="songAuthor-child glow"></p>
            </div>
            <div class="playContainer"></div>
        </div>
    </fieldset>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        loadSongs(<?php echo dd_json($songs); ?>);
    });
    </script>
</main>
</body>
</html>
