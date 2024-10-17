let vGlobJson;

function loadSongs(songs) {
    if (!Array.isArray(songs)) {
        console.error('Songs data is not an array:', songs);
        return;
    }
    vGlobJson = songs;
    songs.forEach(addSongtoList);
}

function addSongtoList(song, firstOne = false) {
    let songName = song.title || 'Song Title Not Available';
    let duration = song.duration ? parseInt(song.duration, 10) : 0;
    let minutes = Math.floor(duration / 60);
    let seconds = duration % 60;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    let formattedDuration = `${minutes}:${seconds.toString().padStart(2, '0')}`;

    let container = document.createElement("div");
    container.className = "songEntry";

    let songNameElement = document.createElement("p");
    songNameElement.id = "song";
    songNameElement.className = "songTitle";
    songNameElement.textContent = songName;
    
    let songDurationElement = document.createElement("p");
    songDurationElement.id = "duration";
    songDurationElement.className = "songDuration";
    songDurationElement.textContent = formattedDuration;

    container.appendChild(songNameElement);
    container.appendChild(songDurationElement);

    document.getElementById("songselector").appendChild(container);

    if (firstOne) {
        songNameElement.classList.add("selected");
    }
}