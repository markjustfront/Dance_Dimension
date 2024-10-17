// Initialize global variables for score tracking and arrow count
var score = 0;
var arrowCounter = 0;

// Main game function, setting up event listeners
function game() {
    // Listen for keydown events to check for arrow key presses
    document.addEventListener("keydown", function (e) {
        keyCheck(e);
    });

    // Update the progress bar once the DOM is loaded
    document.addEventListener("DOMContentLoaded", function () {
        progressBarUpdate();
    });
}

// Function to set up the level with arrows based on the provided JSON data
function getLevel(nArrows, levelJson) {
    var level = JSON.parse(JSON.stringify(levelJson)); // Deep copy the level data
    for (let i = 0; i < nArrows; i++) {
        // Spawn arrows at specified times based on level data
        setTimeout(() => { spawnArrow(level[i][0]) }, level[i][1] * 1000);
        // Despawn arrows if not hit, at the time they should disappear
        setTimeout(() => { despawnArrow(level[i][0]) }, level[i][2] * 1000);
    }
    game(); // Start the game after setting up the level
}

// Function to update the score and grade
function updateScore(modifier) {
    score += modifier;
    if (score < 0) {
        score = 0; // Ensure score doesn't go negative
    }
    let grade;
    // Determine grade based on score relative to total arrows
    if (score >= arrowCounter * 70) {
        grade = "A";
    } else if (score >= arrowCounter * 50) {
        grade = "B";
    } else if (score >= arrowCounter * 30) {
        grade = "C";
    } else if (score >= arrowCounter * 10) {
        grade = "D";
    } else {
        grade = "F";
    }
    document.getElementsByClassName("gradeLetter")[0].textContent = grade;
    document.getElementById("score").textContent = score;
}

// Function to check which key is pressed and handle accordingly
function keyCheck(e) {
    // Check for arrow key presses and handle arrow removal and score update
    if (e.key == "ArrowUp") handleArrow("12", 100, -50);
    if (e.key == "ArrowDown") handleArrow("13", 100, -50);
    if (e.key == "ArrowLeft") handleArrow("11", 100, -50);
    if (e.key == "ArrowRight") handleArrow("14", 100, -50);
}

// Helper function for checking arrows
function handleArrow(id, hitScore, missScore) {
    var mat = document.getElementById("playMat-" + id);
    if (mat.childElementCount != 0) {
        mat.removeChild(mat.firstChild);
        updateScore(hitScore);
        // Assuming `successEffect` is an audio or effect object
        successEffect.play();
    } else {
        updateScore(missScore);
        // Assuming `failEffect` is an audio or effect object
        failEffect.play();
    }
}

// Function to spawn arrows based on direction code
function spawnArrow(arrowDirection) {
    var arrow = document.createElement("img");
    arrow.className = "arrow";
    arrowCounter++; // Increment for each arrow spawned
    var direction;
    // Determine arrow image and placement based on direction code
    switch (arrowDirection) {
        case "37": // Left arrow key code
            arrow.src = "../../2-Web_Assets/Arrows/LEFT.png";
            direction = "11";
            break;
        case "38": // Up arrow key code
            arrow.src = "../../2-Web_Assets/Arrows/UP.png";
            direction = "12";
            break;
        case "39": // Right arrow key code
            arrow.src = "../../2-Web_Assets/Arrows/RIGHT.png";
            direction = "14";
            break;
        case "40": // Down arrow key code
            arrow.src = "../../2-Web_Assets/Arrows/DAWn.png";
            direction = "13";
            break;
    }
    document.getElementById("playMat-" + direction).append(arrow);
}

// Function to remove arrows if they haven't been hit
function despawnArrow(arrowDirection) {
    var direction = getDirectionFromCode(arrowDirection);
    var mat = document.getElementById("playMat-" + direction);
    if (mat.firstChild) {
        mat.removeChild(mat.firstChild);
        updateScore(-10); // Penalize for not hitting the arrow
    }
}

// Helper to convert arrow code to playMat direction
function getDirectionFromCode(code) {
    switch (code) {
        case "37": return "11";
        case "38": return "12";
        case "39": return "14";
        case "40": return "13";
        default: return "11";
    }
}

// Function to update the progress bar of the game
function progressBarUpdate() {
    const progressBar = document.getElementById('progressBar');
    const levelmusic = document.getElementById('levelmusic'); // Assuming this is how you reference your audio element
    if (levelmusic && !levelmusic.paused && levelmusic.duration > 0) {
        const percentage = (levelmusic.currentTime / levelmusic.duration) * 100;
        progressBar.style.width = percentage + '%';
    }
    setTimeout(progressBarUpdate, 100);
}