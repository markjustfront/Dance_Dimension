function assetUrl(path) {
    const prefix = typeof DD_PREFIX === 'string' ? DD_PREFIX : '';
    return prefix + String(path || '').replace(/^\/+/, '');
}

function loadSongs(songs) {
    if (!Array.isArray(songs)) {
        console.error('Songs is not an array:', songs);
        return;
    }
    jsonGlob = songs;

    const list = document.getElementById('songselector');
    if (!list) {
        return;
    }
    list.innerHTML = '';
    songs.forEach(addSongToList);
}

function addSongToList(song) {
    const songElement = document.createElement('div');
    songElement.className = 'song-item-container';

    const img = document.createElement('img');
    img.src = assetUrl(song.Art);
    img.alt = (song.title || 'Song') + ' art';
    img.className = 'song-list-art';
    img.width = 50;
    img.height = 50;

    const label = document.createElement('p');
    label.className = 'song-item';
    label.dataset.id = String(song.ID);
    label.textContent = (song.title || 'Untitled') + ' by ' + (song.Author || 'Unknown');

    const playButton = document.createElement('button');
    playButton.type = 'button';
    playButton.className = 'play-button button-74 glow';
    playButton.textContent = 'Play';
    playButton.addEventListener('click', function (event) {
        event.stopPropagation();
        playSong(song.ID);
    });

    songElement.appendChild(img);
    songElement.appendChild(label);
    songElement.appendChild(playButton);
    document.getElementById('songselector').appendChild(songElement);

    if (!document.querySelector('.song-item.selected')) {
        label.classList.add('selected');
        updateSongInfo(song.title);
    }
}

function updateSongInfo(title) {
    const song = (jsonGlob || []).find(function (item) {
        return item.title === title;
    });
    if (!song) {
        return;
    }
    const titleEl = document.querySelector('.songTitle-child');
    const authorEl = document.querySelector('.songAuthor-child');
    if (titleEl) {
        titleEl.textContent = song.title;
    }
    if (authorEl) {
        authorEl.textContent = 'by ' + song.Author;
    }
}

function playSong(id) {
    const prefix = typeof DD_PREFIX === 'string' ? DD_PREFIX : '../';
    window.location.href = prefix + 'Game/Song_Level/level.php?id=' + encodeURIComponent(id);
}
