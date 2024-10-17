<?php
session_start();

$error = "";
if (!isset($_GET["songID"]) || !isset($_GET["score"]) || !isset($_GET["grade"])) {
    $error = "Required parameters are missing. Please ensure all fields are filled out correctly.";
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="/2-Web_Assets/styles.css">
    <link rel="icon" type="image/png" href="/2-Web_Assets/The Dance Dimension.png">
    <meta charset="UTF-8">
</head>

<body>
    <div class="scoreForm">
        <?php if ($error): ?>
            <p style='color:red;'><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <form action="add_song_score.php" method="post">
                <label for="nickname">Nickname:</label>
                <?php if (isset($_SESSION["nickname"])): ?>
                    <input type="text" id="nickname" name="nickname" value="<?php echo htmlspecialchars($_SESSION["nickname"]); ?>" required>
                <?php else: ?>
                    <input type="text" id="nickname" name="nickname" required>
                <?php endif; ?>
                <p>Score:</p>
                <div class="score">
                    <?php echo htmlspecialchars($_GET["grade"]) . " " . htmlspecialchars($_GET["score"]); ?>
                </div>
                <input type="hidden" name="songID" value="<?php echo htmlspecialchars($_GET["songID"]); ?>">
                <input type="hidden" name="score" value="<?php echo htmlspecialchars($_GET["score"]); ?>">
                <input type="submit" value="Submit Score">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>