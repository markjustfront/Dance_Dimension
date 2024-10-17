var levelmusic;
function startMusic(songFile, songID) {
    levelmusic = new Audio(songFile);
    levelmusic.play();
    levelmusic.addEventListener("ended", function() {
        var gradeLetter = document.getElementById("gradeLetter").textContent;
        window.location.assign("Score/score.php?score=" + score + "&songID=" + songID + "&gradeLetter=" + gradeLetter);
    });
}
