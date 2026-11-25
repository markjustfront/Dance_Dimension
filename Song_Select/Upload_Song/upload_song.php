<?php
require dirname(__DIR__, 2) . '/common.php';
dd_head('Upload Song', [
    dd_url('Song_Select/Upload_Song/upload_song.js'),
    dd_url('Song_Select/song_ticktack.js'),
]);
?>
<body class="body" id="BlackBG">
<main class="page">
    <p class="nav-row">
        <a class="button-74 glow" href="<?php echo dd_h(dd_url('Menu/Menu.php')); ?>">Main Menu</a>
    </p>
    <div class="upload-content">
        <fieldset class="glow">
            <form action="upload_song_func.php" method="post" enctype="multipart/form-data">
                <label for="title" class="glow">Title</label>
                <input type="text" id="title" name="title" maxlength="80" required>

                <label for="author" class="glow">Author</label>
                <input type="text" id="author" name="author" maxlength="80" required>

                <label for="songfile" class="glow">Song file (OGG, WAV, or MP3, max 8 MB)</label>
                <input type="file" id="songfile" name="songfile" accept=".ogg,.oga,.wav,.mp3,audio/ogg,audio/wav,audio/mpeg" required>

                <label for="art" class="glow">Art (JPEG, PNG, or WebP, max 3 MB)</label>
                <input type="file" id="art" name="art" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>

                <fieldset class="inner">
                    <legend class="glow">Chart upload method</legend>
                    <div>
                        <input type="radio" id="fileUpload" name="uploadMethod" value="file" required>
                        <label for="fileUpload">File upload</label>
                        <input type="radio" id="textUpload" name="uploadMethod" value="text">
                        <label for="textUpload">Text entry</label>
                    </div>
                    <div id="fileField" hidden>
                        <label for="gamefile">Chart file (.txt)</label>
                        <input type="file" id="gamefile" name="gamefile" accept=".txt,text/plain">
                    </div>
                    <div id="textField" hidden>
                        <label for="gamefileArea" class="glow">Chart content</label>
                        <textarea id="gamefileArea" name="gamefileArea" rows="8" placeholder="85&#10;37 # 1.0 # 2.0"></textarea>
                    </div>
                </fieldset>

                <input type="hidden" name="songDuration" id="songDuration" value="0">
                <button type="submit" class="button-74 glow">Submit</button>
            </form>
        </fieldset>
    </div>
</main>
</body>
</html>
