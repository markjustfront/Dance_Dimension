<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
    <title>Edit Song</title>
    <script src="editsong.js"></script>
</head>

<body class="body" id="BlackBG">
    <?php
    // Display error message if an error parameter is set in the URL
    if (isset($_GET["error"])) {
        echo "<div class='error'>";
        if ($_GET["error"] == 1) {
            echo "Invalid game file.";
        }
        echo "</div>";
        
    }
    ?>
    <div class="">
        <fieldset class=" glow">
        <form action="songEditFunction.php" method="post" id="EditForm" enctype="multipart/form-data">
            <label for="songfile">Song file</label>
            <input type="file" id="songfile" name="songfile" accept="audio/mp3" required>
            <label for="gamefile">Game file</label>
            <input type="file" id="gamefile" name="gamefile" accept=".txt" required>
            <!-- Textarea for game file content -->
            <textarea id="gamefileArea" name="gamefileArea" required></textarea>
            <script src="../gamefileHandler.js"></script>
            <label for="portrait">Portrait</label>
            <input type="file" id="portrait" name="portrait" accept="image/png" required>
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
            <!-- Hidden input for song ID -->
            <input type="text" id="ID" name="ID" style="display: none;">
            
            <?php
            // Load song data to populate the form with current values
            $json = file_get_contents("../../1-Song_Data/song_info.json");
            $song = $_GET["ID"];
            $json = json_decode($json, true);
            $t = $d = $a = $id = ''; // Initialize variables to avoid undefined index errors
            foreach ($json as $key => $value) {
                if ($value["ID"] == $song || $value["title"] == $song) {
                    $t = $value["title"];
                    $d = $value["duration"];
                    $a = $value["Author"];
                    $id = $value["ID"];
                    echo "<script>loadEditValues('$t', '$a', '$id')</script>";
                    break; 
                }
            }
            ?>
            <input type="hidden" name="songDuration" id="songDuration" value="<?php echo $d; ?>">
            <script src="../song_ticktack.js"></script>
            <button type="submit" value="Submit" class="glow button-74"> Submit</button>
        </form>
        </fieldset>
        <button class="button-74 glow" onclick="location.href = '../song_list.php'">Song List</button>

    </div>
</body>

</html>