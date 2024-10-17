function loadSongs(songs) {
    if (!Array.isArray(songs)) {
        console.error('Songs is not an array:', songs);
        return;
    }
    jsonGlob = songs;

    // Clear existing content
    document.getElementById("songselector").innerHTML = '';

    songs.forEach(addSongToList);
}

function addSongToList(song) {
    let songElement = document.createElement("div");
    songElement.className = "song-item-container";
    songElement.innerHTML = `
        <img src="${song.Art}" alt="${song.title} Art" class="song-list-art" style="width: 50px; height: 50px;">
        <p class="song-item" data-id="${song.ID}">${song.title} by ${song.Author}</p>
        <button class="play-button button-74 glow">Play</button>
    `;
    document.getElementById("songselector").appendChild(songElement);

    // Set the first song as selected by default but don't call updateSongInfo here
    if (!document.querySelector('.song-item.selected')) {
        songElement.querySelector('.song-item').classList.add("selected");
    }

    // Add click event for the play button
    let playButton = songElement.querySelector('.play-button');
    playButton.onclick = (event) => {
        event.stopPropagation();
        playSong(song.ID);
    };
}

function updateSongInfo(title) {
    const song = jsonGlob.find(s => s.title === title);
    if(song) {
        document.querySelector('.songTitle-child').textContent = song.title;
        document.querySelector('.songAuthor-child').textContent = `by ${song.Author}`;
        // If you want to show the selected song's image here, uncomment or adapt the following line:
        // document.querySelector('.song-art').src = song.Art; // Assuming you have an element with class 'song-art'
    }
}

function playSong(id) {
    window.location.href = `Song_Level/level.php?id=${id}`;
}

// Initially, we won't display any song information until one is selected
document.querySelector('.songInfo').style.display = 'none';