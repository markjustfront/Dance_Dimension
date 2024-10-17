var score = 0;
var arrowCounter = 0;
function game() {
    document.addEventListener("keydown", function (e) {
        keyCheck(e);
    });
    document.addEventListener("DOMContentLoaded", function () {
        progressBarUpdate();
    });
}
function getLevel(nArrows, levelJson) {
    var level = JSON.parse(JSON.stringify(levelJson));
    for (let i = 0; i < nArrows; i++) {
        setTimeout(() => { spawnArrow(level[i][0]) }, level[i][1] * 1000);
        setTimeout(() => { despawnArrow(level[i][0]) }, level[i][2] * 1000);
    }
    game();
}

function updateScore(modifier) {
    score += modifier;
    if (score < 0) {
        score = 0;
    }
    let grade;
    if (score >= arrowCounter * 90) {
        grade = "Z";
    } else if (score >= arrowCounter * 70) {
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

function keyCheck(e) {
    if (e.key == "ArrowUp") {
        if (document.getElementById("playMat-12").childElementCount != 0) {
            document.getElementById("playMat-12").removeChild(document.getElementById("playMat-12").firstChild);
            updateScore(100);
        } else {
            updateScore(-50);
        }
    }
    if (e.key == "ArrowDown") {
        if (document.getElementById("playMat-13").childElementCount != 0) {
            document.getElementById("playMat-13").removeChild(document.getElementById("playMat-13").firstChild);
            updateScore(100);
            successEffect.play();
        } else {
            updateScore(-50);
            failEffect.play();
        }
    }
    if (e.key == "ArrowLeft") {
        if (document.getElementById("playMat-11").childElementCount != 0) {
            document.getElementById("playMat-11").removeChild(document.getElementById("playMat-11").firstChild);
            updateScore(100);
            successEffect.play();
        } else {
            updateScore(-50);
            failEffect.play();
        }
    }
    if (e.key == "ArrowRight") {
        if (document.getElementById("playMat-14").childElementCount != 0) {
            document.getElementById("playMat-14").removeChild(document.getElementById("playMat-14").firstChild);
            updateScore(100);
            successEffect.play();
        } else {
            updateScore(-50);
            failEffect.play();
        }
    }
}

function spawnArrow(arrowDirection) {
    var arrow = document.createElement("img");
    arrow.className = "arrow";
    arrowCounter++;
    var direction;
    switch (arrowDirection) {
        case "37":
            arrow.src = "../../2-Web_Assets/Arrows/LEFT.png";
            arrow.id = "arrowLEFT";
            direction = "11";
            break;
        case "38":
            arrow.src = "../../2-Web_Assets/Arrows/UP.png";
            arrow.id = "arrowUP";
            direction = "12";
            break;
        case "39":
            arrow.src = "../../2-Web_Assets/Arrows/RIGHT.png";
            arrow.id = "arrowRIGHT";
            direction = "14";
            break;
        case "40":
            arrow.src = "../../2-Web_Assets/Arrows/DAWn.png";
            arrow.id = "arrowDOWN";
            direction = "13";
            break;
    }
    document.getElementById("playMat-" + direction).append(arrow);
}

function despawnArrow(arrowDirection) {
    var direction;
    switch (arrowDirection) {
        case "37":
            direction = "11";
            break;
        case "38":
            direction = "12";
            break;
        case "39":
            direction = "14";
            break;
        case "40":
            direction = "13";
            break;
        default:
            direction = "11";
    }
    document.getElementById("playMat-" + direction).removeChild(document.getElementById("playMat-" + direction).firstChild);
    updateScore(-10);
}

function progressBarUpdate() {
    const progressBar = document.getElementById('progressBar');
    const percentage = (levelmusic.currentTime / levelmusic.duration) * 100;
    progressBar.style.width = percentage + '%';
    setTimeout(() => { progressBarUpdate() }, 100);

}