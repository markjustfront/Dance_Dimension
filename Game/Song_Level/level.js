var score = 0;
var arrowCounter = 0;

function playTone(frequency, duration, type) {
    try {
        const ctx = playTone.ctx || (playTone.ctx = new (window.AudioContext || window.webkitAudioContext)());
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = type || 'square';
        osc.frequency.value = frequency;
        gain.gain.value = 0.08;
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start();
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + duration);
        osc.stop(ctx.currentTime + duration);
    } catch (err) {
        // Audio cues are optional.
    }
}

function game() {
    document.addEventListener('keydown', function (event) {
        keyCheck(event);
    });
    document.querySelectorAll('#playMat .pad').forEach(function (pad) {
        pad.addEventListener('pointerdown', function (event) {
            event.preventDefault();
            handleArrow(pad.getAttribute('data-dir'), 100, -50);
        });
    });
    requestAnimationFrame(progressBarUpdate);
}

function getLevel(nArrows, levelJson) {
    const level = Array.isArray(levelJson) ? levelJson : [];
    const count = Math.min(Number(nArrows) || 0, level.length);
    for (let i = 0; i < count; i++) {
        const row = level[i] || [];
        const dir = String(row[0] || '');
        const appear = Number(row[1]) * 1000;
        const vanish = Number(row[2]) * 1000;
        if (!dir || Number.isNaN(appear)) {
            continue;
        }
        window.setTimeout(function () {
            spawnArrow(dir);
        }, Math.max(0, appear));
        window.setTimeout(function () {
            despawnArrow(dir);
        }, Math.max(0, Number.isNaN(vanish) ? appear + 1000 : vanish));
    }
    game();
}

function updateScore(modifier) {
    score += modifier;
    if (score < 0) {
        score = 0;
    }
    let grade = 'F';
    if (arrowCounter > 0) {
        if (score >= arrowCounter * 70) {
            grade = 'A';
        } else if (score >= arrowCounter * 50) {
            grade = 'B';
        } else if (score >= arrowCounter * 30) {
            grade = 'C';
        } else if (score >= arrowCounter * 10) {
            grade = 'D';
        }
    }
    const gradeEl = document.getElementsByClassName('gradeLetter')[0];
    const scoreEl = document.getElementById('score');
    if (gradeEl) {
        gradeEl.textContent = grade;
    }
    if (scoreEl) {
        scoreEl.textContent = String(score);
    }
}

function keyCheck(event) {
    if (event.key === 'ArrowUp') {
        handleArrow('12', 100, -50);
    } else if (event.key === 'ArrowDown') {
        handleArrow('13', 100, -50);
    } else if (event.key === 'ArrowLeft') {
        handleArrow('11', 100, -50);
    } else if (event.key === 'ArrowRight') {
        handleArrow('14', 100, -50);
    }
}

function handleArrow(id, hitScore, missScore) {
    const mat = document.getElementById('playMat-' + id);
    if (!mat) {
        return;
    }
    const arrow = mat.querySelector('img.arrow');
    if (arrow) {
        mat.removeChild(arrow);
        updateScore(hitScore);
        playTone(880, 0.08, 'square');
    } else {
        updateScore(missScore);
        playTone(180, 0.12, 'sawtooth');
    }
}

function spawnArrow(arrowDirection) {
    const arrow = document.createElement('img');
    arrow.className = 'arrow';
    arrow.alt = '';
    arrowCounter += 1;
    let direction = '11';
    const prefix = typeof DD_PREFIX === 'string' ? DD_PREFIX : '../../';
    switch (String(arrowDirection)) {
        case '37':
            arrow.src = prefix + '2-Web_Assets/Arrows/LEFT.png';
            arrow.alt = 'Left arrow';
            direction = '11';
            break;
        case '38':
            arrow.src = prefix + '2-Web_Assets/Arrows/UP.png';
            arrow.alt = 'Up arrow';
            direction = '12';
            break;
        case '39':
            arrow.src = prefix + '2-Web_Assets/Arrows/RIGHT.png';
            arrow.alt = 'Right arrow';
            direction = '14';
            break;
        case '40':
            arrow.src = prefix + '2-Web_Assets/Arrows/DAWn.png';
            arrow.alt = 'Down arrow';
            direction = '13';
            break;
        default:
            return;
    }
    const mat = document.getElementById('playMat-' + direction);
    if (mat) {
        mat.append(arrow);
    }
}

function despawnArrow(arrowDirection) {
    const direction = getDirectionFromCode(arrowDirection);
    const mat = document.getElementById('playMat-' + direction);
    if (mat && mat.querySelector('img.arrow')) {
        mat.removeChild(mat.querySelector('img.arrow'));
        updateScore(-10);
    }
}

function getDirectionFromCode(code) {
    switch (String(code)) {
        case '37': return '11';
        case '38': return '12';
        case '39': return '14';
        case '40': return '13';
        default: return '11';
    }
}

function progressBarUpdate() {
    const progressBar = document.getElementById('progressBar');
    const music = (typeof levelmusic !== 'undefined') ? levelmusic : null;
    if (progressBar && music && music.duration > 0) {
        const percentage = (music.currentTime / music.duration) * 100;
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', String(Math.round(percentage)));
    }
    window.requestAnimationFrame(progressBarUpdate);
}
