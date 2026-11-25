<?php
require dirname(__DIR__) . '/common.php';
dd_head('Song List', [
    dd_url('Song_Select/song_list.js'),
    dd_url('Song_Select/song_select.js'),
]);
$songs = dd_load_songs();
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Menu/Menu.php')); ?>">Main Menu</a>
    </p>
    <div class="songs-songselector" id="songselector"></div>
    <div class="modifyButtons">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Song_Select/Upload_Song/upload_song.php')); ?>">Upload Song</a>
        <button type="button" id="edit" class="button-74 glow" disabled>Edit</button>
        <button type="button" id="remove" class="button-74 glow" disabled>Delete</button>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        loadSongs(<?php echo dd_json($songs); ?>);
    });
    </script>
</main>
</body>
</html>
