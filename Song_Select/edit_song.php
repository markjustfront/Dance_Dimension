<?php
$jsonFilePath = '../1-Song_Data/Text_Data/song_info.json';
$songs = json_decode(file_get_contents($jsonFilePath), true);
$id = $_GET['id'] ?? null;

if ($id === null || !isset($songs[$id])) {
    header("Location: song_list.php");
    exit();
}

$song = $songs[$id];
$gameFilePath = $song['game_path'] ?? '';
$gameFileContent = ($gameFilePath && file_exists($gameFilePath)) ? file_get_contents($gameFilePath) : '';
$imagePath = htmlspecialchars($song['image_path'] ?? 'path/to/default/image.jpg');
$altText = htmlspecialchars($song['title'] ?? 'Default Song Title');
$description = $song['description'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Edit</title>
</head>
<body id="BlackBG">
    <div class="backbutton">
        <button class="button-74 glow" onclick="window.location.href='song_list.php'">Back to Song List</button>
    </div>
    <!-- Same structure of the upload page but the fields are prefilled and a short explanation of each is inserted -->
    <div class="upload-content">
        <fieldset class="altfield">
            <form action="update_song.php" method="post" enctype="multipart/form-data" class="glow">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="title" class="glow laforme">Title *</label>
                <input type="text" class="title" name="title" value="<?php echo htmlspecialchars($song['title'] ?? ''); ?>" required/>
                
                <label for="artist" class="glow laforme">Artist *</label>
                <input type="text" name="artist" value="<?php echo htmlspecialchars($song['artist'] ?? ''); ?>" required/>
                
                <label for="description" class="glow laforme">Description</label>
                <textarea class="textarea" name="description"><?php echo htmlspecialchars($description); ?></textarea>

                <label for="song" class="glow laforme">Change Song File (leave blank to keep current)</label>
                <input type="file" name="song" accept=".mp3,.ogg">

                <label for="image" class="glow laforme">Change Image (leave blank to keep current)</label>
                <input type="file" name="image" id="imageUpload" accept=".jpeg,.png,.jpg"/>
                <img id="preview" src="<?php echo $imagePath; ?>" alt="<?php echo $altText; ?>" class="DummyBlock""/>
                
                <label class="glow laforme">Game File Upload Method</label>
                <div>
                    <input type="radio" id="fileUpload" name="uploadMethod" value="file" onclick="showField('file')" <?php echo empty($gameFileContent) ? 'checked' : ''; ?> required>
                    <label for="fileUpload" class="glow">Upload New File</label>
                    <input type="radio" id="textUpload" name="uploadMethod" value="text" onclick="showField('text')" <?php echo !empty($gameFileContent) ? 'checked' : ''; ?>>
                    <label for="textUpload" class="glow">Edit Text</label>
                </div>

                <div id="fileField" style="<?php echo empty($gameFileContent) ? 'display:block;' : 'display:none;'; ?>">
                    <input type="file" name="gameFile" id="gameFile" accept=".txt">
                </div>

                <div id="textField" style="<?php echo !empty($gameFileContent) ? 'display:block;' : 'display:none;'; ?>">
                    <label for="textgameFile" class="glow laforme">Current Game File Content:</label>
                    <textarea id="textgameFile" class="glow laforme comment-container" name="textgameFile" rows="10" cols="50"><?php echo htmlspecialchars($gameFileContent); ?></textarea>
                </div>

                <button type="submit" class="glow button-74">Update Song</button>
            </form>
        </fieldset>
    </div>

    <script>
    function showField(type) {
        document.getElementById('fileField').style.display = type === 'file' ? 'block' : 'none';
        document.getElementById('textField').style.display = type === 'text' ? 'block' : 'none';
    }
    </script>
</body>
</html>