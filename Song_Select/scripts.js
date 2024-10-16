let isSoundEnabled = localStorage.getItem('soundEnabled') === 'true' || false;
let audioPlayer = null;
let currentPlaying = null;

function toggleSound() {
    isSoundEnabled = !isSoundEnabled;
    localStorage.setItem('soundEnabled', isSoundEnabled);
    document.getElementById('sound-toggle').innerText = isSoundEnabled ? 'Disable Sound' : 'Enable Sound';
    if (!isSoundEnabled && audioPlayer) {
        audioPlayer.pause();
        currentPlaying = null;
    }
}

// Initialize button text based on stored state
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('sound-toggle').innerText = isSoundEnabled ? 'Disable Sound' : 'Enable Sound';
});

    document.querySelectorAll('.song-item').forEach(song => {
        song.addEventListener('mouseover', function() {
            if (isSoundEnabled && currentPlaying !== this) {
            }
        });
        // song.addEventListener('mouseout', function() {
        //     // Stop audio if it's not controlled by the play/pause button
        //     if (currentPlaying === this && audioPlayer && !audioPlayer.paused) {
        //         audioPlayer.pause();
        //         currentPlaying = null;
        //     }
        // });
    });

    document.querySelectorAll('.play-button').forEach(button => {
        button.addEventListener('click', function() {
            if (!isSoundEnabled) return;
            const src = this.getAttribute('data-src');
            if (currentPlaying !== this.closest('.song-item')) {
                if (audioPlayer) audioPlayer.pause();
                audioPlayer = new Audio(src);
                audioPlayer.play();
                currentPlaying = this.closest('.song-item');
            } else {
                if (audioPlayer.paused) {
                    audioPlayer.play();
                } else {
                    audioPlayer.pause();
                }
            }
        });
    });

    document.querySelectorAll('.go-play').forEach(button => {
        button.addEventListener('click', function() {
            const songIndex = this.getAttribute('data-index');
            // Here, instead of setting localStorage, we directly append to URL
            window.location.href = '../Game/DDR.php?songindex=' + songIndex;
        });
    });
    