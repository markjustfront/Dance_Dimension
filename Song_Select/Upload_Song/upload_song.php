<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Upload Song</title>
</head>
<body>
    <div class="backbutton">
        <button class="button-74 glow" onclick="window.location.href='/Menu/Menu.php'">Main Menu</button>
    </div>
    <div class="upload-content">
        <fieldset class="altfield">
        <form action="upload_song_func.php" method="post" enctype="multipart/form-data">                <label for="title" class="glow laforme">Title *</label>
                <input type="text" id="title" name="title" placeholder="Computer World" required/>

                <label for="author" class="glow laforme">Author</label>
                <input type="text" id="author" name="author" placeholder="Kraftwerk" required/>

                <label for="songfile" class="glow laforme">Song File (MP3)</label>
                <input type="file" id="songfile" name="songfile" accept=".mp3" required>

                <label for="Art" class="glow laforme">Art</label>
                <input type="file" id="art" name="art" accept="jpg/png" required/>

                <!-- Game File Upload Method Selection -->
                <label class="glow laforme">Game File Upload Method</label>
                <div>
                    <input type="radio" id="fileUpload" name="uploadMethod" value="file" onclick="showField('file')" required>
                    <label for="fileUpload" class="glow">File Upload</label>
                    <input type="radio" id="textUpload" name="uploadMethod" value="text" onclick="showField('text')">
                    <label for="textUpload" class="glow">Text Entry</label>
                </div>

                <!-- File Upload Field -->
                <div id="fileField" style="display:none;">
                    <input type="file" id="gamefile" name="gamefile" accept=".txt">
                </div>

                <!-- Text Entry Field -->
                <div id="textField" style="display:none;">
                    <label for="gamefileArea" class="glow laforme">Game File Content</label>
                    <textarea id="gamefileArea" name="gamefileArea" placeholder="Enter game file content here..."></textarea>
                </div>

                <input type="hidden" name="songDuration" id="songDuration" value="0"> <!-- or a dynamic value -->

                <button type="submit" class="button-74 glow">Submit</button>
            </form>
        </fieldset>
    </div>
    <script src="upload_song.js"></script>
    <script>
        function showField(type) {
            document.getElementById('fileField').style.display = type === 'file' ? 'block' : 'none';
            document.getElementById('textField').style.display = type === 'text' ? 'block' : 'none';
        }
    </script>
    <script src="../song_ticktack.js"></script>
</body>
</html>