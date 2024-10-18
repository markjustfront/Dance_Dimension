<?php
session_start();

$error = "";
if (!isset($_GET["songID"]) || !isset($_GET["score"])) {
    $error = "Required parameters 'songID' and 'score' are missing. Please ensure all fields are filled out correctly.";
} else {
    // If grade is not set whe set a delfault value
    $grade = isset($_GET["grade"]) ? htmlspecialchars($_GET["grade"]) : "No Grade";
}


?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
</head>

<body class="body" id="BlackBG">
    <fieldset calss="score-fieldst">
    <div class="glow ">
        <?php if ($error): ?>
            <p style='color:red;'><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <form action="add_song_score.php" method="post">
                <label for="nickname">Nickname:</label>
                <?php if (isset($_SESSION["nickname"])): ?>
                    <input type="text" id="" class="textarea" name="nickname" value="<?php echo htmlspecialchars($_SESSION["nickname"]); ?>" required>
                <?php else: ?>
                    <input type="text" id="" class="textarea" name="nickname" required>
                <?php endif; ?>
                <p>Score:</p>
                <div class="score">
                    <?php echo htmlspecialchars($_GET["score"]) ?>
                </div>
                <input type="hidden" name="songID" value="<?php echo htmlspecialchars($_GET["songID"]); ?>">
                <input type="hidden" name="score" value="<?php echo htmlspecialchars($_GET["score"]); ?>">
                <button type="submit" value="Submit" class="button-74 glow">Submit</button>
            </form>
        <?php endif; ?>
    </div>
    </fieldset>
</body>

</html>
