var levelmusic;

function startMusic(songFile, songID) {
    if (!songFile) {
        console.error('No song file provided');
        return;
    }
    levelmusic = new Audio(songFile);
    levelmusic.addEventListener('ended', function () {
        const gradeEl = document.getElementById('gradeLetter');
        const gradeLetter = gradeEl ? gradeEl.textContent : 'F';
        const safeScore = typeof score === 'number' ? score : 0;
        window.location.assign(
            'Score/score.php?score=' + encodeURIComponent(safeScore) +
            '&songID=' + encodeURIComponent(songID) +
            '&gradeLetter=' + encodeURIComponent(gradeLetter)
        );
    });
    const play = levelmusic.play();
    if (play && typeof play.catch === 'function') {
        play.catch(function () {
            const hint = document.createElement('button');
            hint.type = 'button';
            hint.className = 'button-74 glow';
            hint.textContent = 'Tap to start audio';
            hint.addEventListener('click', function () {
                levelmusic.play();
                hint.remove();
            });
            const nav = document.querySelector('.nav-row');
            if (nav) {
                nav.appendChild(hint);
            }
        });
    }
}
