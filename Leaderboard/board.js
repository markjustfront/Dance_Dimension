var vGlobRanks = {};
var vGlobSongs = [];

function loadSongs(songs) {
    const list = document.getElementById('songselector');
    if (!list || !Array.isArray(songs)) {
        return;
    }
    list.innerHTML = '';
    songs.forEach(function (song, index) {
        addSongtoList(song, index === 0);
    });
    list.addEventListener('click', function (event) {
        const item = event.target.closest('.song-pick');
        if (!item) {
            return;
        }
        list.querySelectorAll('.song-pick.selected').forEach(function (el) {
            el.classList.remove('selected');
        });
        item.classList.add('selected');
        updateScore();
    });
}

function addSongtoList(song, firstOne) {
    const el = document.createElement('button');
    el.type = 'button';
    el.className = 'song-pick' + (firstOne ? ' selected' : '');
    el.dataset.id = String(song.ID);
    el.textContent = song.title || 'Untitled';
    document.getElementById('songselector').appendChild(el);
}

function loadScore(scores, songs, firstTime) {
    if (firstTime !== false) {
        vGlobRanks = JSON.parse(JSON.stringify(scores || {}));
        vGlobSongs = JSON.parse(JSON.stringify(songs || []));
    }
    const selected = document.querySelector('.song-pick.selected');
    if (!selected) {
        return;
    }
    const id = selected.dataset.id;
    const rows = (vGlobRanks && vGlobRanks[id]) ? vGlobRanks[id].slice() : [];
    rows.sort(function (a, b) {
        return Number(b.score) - Number(a.score);
    });
    addScoreToList(rows);
}

function updateScore() {
    const table = document.getElementById('scoreList');
    if (table) {
        table.innerHTML = '';
    }
    loadScore(vGlobRanks, vGlobSongs, false);
}

function addScoreToList(entries) {
    const table = document.getElementById('scoreList');
    if (!table) {
        return;
    }
    const header = table.createTHead();
    const headRow = header.insertRow();
    headRow.insertCell().textContent = 'Player';
    headRow.insertCell().textContent = 'Score';
    const body = table.createTBody();
    if (!entries.length) {
        const empty = body.insertRow();
        const cell = empty.insertCell();
        cell.colSpan = 2;
        cell.textContent = 'No scores yet';
        return;
    }
    entries.forEach(function (entry) {
        const row = body.insertRow();
        row.insertCell().textContent = entry.nickname || '';
        row.insertCell().textContent = entry.score || '0';
    });
}
