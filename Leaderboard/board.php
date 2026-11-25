<?php
require dirname(__DIR__) . '/common.php';
dd_head('Leaderboard', [dd_url('Leaderboard/board.js')]);
$songs = dd_load_songs();
$scores = dd_load_scores();
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Menu/Menu.php')); ?>">Main Menu</a>
    </p>
    <div class="board-layout">
        <fieldset>
            <h1 class="glow">Songs Listed</h1>
            <div class="songselector glow" id="songselector"></div>
        </fieldset>
        <fieldset>
            <h1 class="glow">Leaderboard</h1>
            <div class="scoreShow" id="scoreShow">
                <table class="scoreList glow" id="scoreList"></table>
            </div>
        </fieldset>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        loadSongs(<?php echo dd_json($songs); ?>);
        loadScore(<?php echo dd_json($scores); ?>, <?php echo dd_json($songs); ?>);
    });
    </script>
</main>
</body>
</html>
