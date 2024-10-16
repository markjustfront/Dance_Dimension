document.addEventListener('DOMContentLoaded', (event) => {
    let currentEvent = 0;
    let score = 0;
    let events = [];
    let maxScore;

    function initializeGame() {
        document.getElementById('startGameContainer').style.display = 'none';
        document.getElementById('gameArea').style.display = 'block';

        const audio = new Audio(songInfo.song_path);
        audio.muted = true; // Start muted due to autoplay policies

        audio.play().then(() => {
            audio.muted = false; // Unmute the audio
            // Here you should directly use the game data from PHP or fetch if necessary
            setupGameEvents(gameEventData, audio); // Ensure gameEventData is defined or fetched
        }).catch((error) => {
            console.error("Audio playback was prevented:", error);
            alert("Please interact with the page to start the game. Autoplay is restricted.");
        });
    }

    // Remove other startGameButton listeners and keep only this one:
    document.getElementById('startGameButton').addEventListener('click', initializeGame);

    function checkKeyPress(keyCode) {
        if (currentEvent < events.length && keyCode === events[currentEvent].keyCode) {
            events[currentEvent].hit = true;
            score += 100;
        } else {
            score -= 50;
        }
        document.getElementById('currentScore').textContent = score;
        updateRank(score, maxScore);
    }

    function setupGameEvents(gameData, audio) {
        console.log('Setting up game events with:', gameData);
        const lines = gameData.split('\n');
        const numberOfEvents = parseInt(lines[0], 10);
        events = lines.slice(1).map(line => {
            const [keyCode, start, end] = line.split('#').map(Number);
            return {keyCode, start: start * 1000, end: end * 1000};
        });

        maxScore = numberOfEvents * 100;

        function gameLoop() {
            const currentTime = audio.currentTime * 1000;
            const duration = audio.duration * 1000;
            document.getElementById('playbackProgress').style.width = `${(currentTime / duration) * 100}%`;

            if (currentEvent < events.length) {
                if (currentTime >= events[currentEvent].start && currentTime <= events[currentEvent].end) {
                    showKey(events[currentEvent].keyCode);
                } else if (currentTime > events[currentEvent].end) {
                    if (!events[currentEvent].hit) {
                        score -= 50;
                        document.getElementById('currentScore').textContent = score;
                        updateRank(score, maxScore);
                    }
                    hideKey();
                    currentEvent++;
                }
            }

            if (currentEvent < events.length && !audio.paused && !audio.ended) {
                requestAnimationFrame(gameLoop);
            } else if (audio.ended) {
                endGame();
            }
        }

        audio.onplay = gameLoop;
        audio.onended = endGame;

        // Define functions here or ensure they're defined earlier if not inside this scope
        function updateRank(score, maxScore) {
            const percentage = (score / maxScore) * 100;
            let rank = 'E';
            if (percentage >= 90) rank = 'A';
            else if (percentage >= 70) rank = 'B';
            else if (percentage >= 50) rank = 'C';
            else if (percentage >= 25) rank = 'D';
            document.getElementById('currentRank').textContent = rank;
        }

        function showKey(keyCode) {
            const keyDiv = document.createElement('div');
            keyDiv.className = 'key';
            keyDiv.textContent = ['←', '↑', '→', '↓'][keyCode - 37] || String.fromCharCode(keyCode);
            document.getElementById('keyArea').appendChild(keyDiv);
        }

        function hideKey() {
            document.querySelector('.key-area .key')?.remove();
        }
    }

    function endGame() {
        const score = parseInt(document.getElementById('currentScore').textContent);
        const playerName = prompt("Enter your name to save your score:");
        if(playerName) {
            saveStats(playerName, score);
        }
        alert(score === 0 ? "The song finished with a score of 0. Better luck next time!" : "Congratulations on finishing the song!");
        window.location.href = '/Menu/Menu.html';
    }

    function saveStats(name, score) {
        let stats = JSON.parse(localStorage.getItem('gameStats') || '[]');
        stats.push({name, score, date: new Date().toISOString()});
        localStorage.setItem('gameStats', JSON.stringify(stats));
    }

    document.addEventListener('keydown', function(event) {
        const keyMap = {
            "ArrowLeft": 37,
            "ArrowUp": 38,
            "ArrowRight": 39,
            "ArrowDown": 40
        };

        if(keyMap[event.key] !== undefined) {
            checkKeyPress(keyMap[event.key]);
            event.preventDefault();
        }
    });

    function getGameDataFromMap(gamePath) {
        console.log('Fetching game data from:', gamePath);
        return fetch(gamePath)
            .then(response => response.text())
            .then(data => data)
            .catch(error => {
                console.error('Failed to load game data:', error);
                return "4\n37#0.5#1.0\n38#1.1#1.6\n39#2.0#2.5\n40#2.6#3.1"; // Fallback data
            });
    }

    // This should be called when the user selects a song to play
    document.getElementById('startGameButton').addEventListener('click', function() {
        const audio = new Audio(songInfo.song_path);
        audio.muted = true; // Start muted to comply with autoplay policies
        
        audio.play().then(() => {
            audio.muted = false; // Unmute after play promise resolves
            return getGameDataFromMap(songInfo.game_path);
        }).then(gameData => {
            setupGameEvents(gameData, audio);
        }).catch(error => {
            console.error("Failed to start game:", error);
            alert("Failed to start the game. Please ensure the page has been interacted with for audio playback.");
        });
    });
});

document.getElementById('startGameButton').addEventListener('click', function() {
    const startGameContainer = document.getElementById('startGameContainer');
    const gameArea = document.getElementById('gameArea');
    
    // Hide the start button
    startGameContainer.style.display = 'none';
    // Show the game area
    gameArea.style.display = 'block';

    const audio = new Audio(songInfo.song_path);
    audio.muted = true; // Still starting muted to comply with autoplay policies
    
    audio.play().then(() => {
        audio.muted = false; // Unmute after play promise resolves
        return getGameDataFromMap(songInfo.game_path);
    }).then(gameData => {
        setupGameEvents(gameData, audio);
    }).catch(error => {
        console.error("Failed to start game:", error);
        alert("Failed to start the game. Please ensure the page has been interacted with for audio playback.");
    });
});

console.log("Attempting to play audio from path:", songInfo.song_path);

document.getElementById('startGameButton').addEventListener('click', function() {
    document.getElementById('startGameContainer').style.display = 'none';
    document.getElementById('gameArea').style.display = 'block';
    // Already handled within the DOMContentLoaded event listener in DDR.php
});



// Other Functions for end of party
function endGame() {
    const finalScore = parseInt(document.getElementById('currentScore').textContent);
    const playerName = prompt("Game Over! Enter your name for the leaderboard:");
    if(playerName) {
        saveStats(playerName, finalScore);
    }
    alert("Game Over! Your final score is: " + finalScore);
    window.location.href = '/Menu/Menu.html'; // Redirect back to the main menu or leaderboard
}

function saveStats(name, score) {
    let stats = JSON.parse(localStorage.getItem('gameStats') || '[]');
    stats.push({
        name: name,
        score: score,
        date: new Date().toISOString()
    });
    stats.sort((a, b) => b.score - a.score); // Sort by score, high to low
    if(stats.length > 10) stats = stats.slice(0, 10); // Keep only top 10 scores
    localStorage.setItem('gameStats', JSON.stringify(stats));
}

// If you want to display the leaderboard somewhere in your game:
function displayLeaderboard() {
    const stats = JSON.parse(localStorage.getItem('gameStats') || '[]');
    let leaderboardHtml = '<ol>';
    stats.forEach((stat, index) => {
        leaderboardHtml += `<li>${stat.name}: ${stat.score} - ${new Date(stat.date).toLocaleDateString()}</li>`;
    });
    leaderboardHtml += '</ol>';
    document.getElementById('leaderboard').innerHTML = leaderboardHtml; // Assuming there's an element with id 'leaderboard'
}

// Assuming you want to call displayLeaderboard when a certain condition is met or a button is clicked:

// Add this if you have a leaderboard button in your game area:
document.getElementById('showLeaderboardButton')?.addEventListener('click', displayLeaderboard);
document.getElementById('startGameButton').addEventListener('click', initializeGame);

// Enhance the key press event to include visual feedback or additional game mechanics
document.addEventListener('keydown', function(event) {
    const keyMap = {
        "ArrowLeft": 37,
        "ArrowUp": 38,
        "ArrowRight": 39,
        "ArrowDown": 40
    };

    if(keyMap[event.key] !== undefined) {
        checkKeyPress(keyMap[event.key]);
        event.preventDefault();
        // Visual feedback for key press (could be a CSS animation or class toggle)
        const keyElement = document.querySelector(`[data-keycode="${keyMap[event.key]}"]`);
        if(keyElement) {
            keyElement.classList.add('pressed');
            setTimeout(() => keyElement.classList.remove('pressed'), 100);
        }
    }
});

// If you have elements representing keys in your HTML, you might want to add this:
document.querySelectorAll('.key').forEach(key => {
    key.addEventListener('click', function() {
        checkKeyPress(parseInt(this.dataset.keycode));
    });
});