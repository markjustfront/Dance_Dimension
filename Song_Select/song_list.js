let vGlobJson = [];

function loadSongs(songs) {
    if (!Array.isArray(songs)) {
        console.error('Songs data is not an array:', songs);
        return;
    }
    vGlobJson = songs;
    const list = document.getElementById('songselector');
    if (!list) {
        return;
    }
    list.innerHTML = '';
    songs.forEach(function (song, index) {
        addSongtoList(song, index === 0);
    });
    if (typeof buttonStatus === 'function') {
        buttonStatus();
    }
}

function addSongtoList(song, firstOne) {
    const duration = song.duration ? parseInt(song.duration, 10) : 0;
    const minutes = Math.floor(duration / 60);
    const seconds = duration % 60;
    const formattedDuration = minutes + ':' + String(seconds).padStart(2, '0');

    const container = document.createElement('button');
    container.type = 'button';
    container.className = 'songEntry';
    container.dataset.id = String(song.ID);

    const songNameElement = document.createElement('span');
    songNameElement.className = 'songTitle';
    songNameElement.textContent = song.title || 'Untitled';

    const songDurationElement = document.createElement('span');
    songDurationElement.className = 'songDuration';
    songDurationElement.textContent = formattedDuration;

    container.appendChild(songNameElement);
    container.appendChild(songDurationElement);
    document.getElementById('songselector').appendChild(container);

    if (firstOne) {
        container.classList.add('selected');
    }
}
