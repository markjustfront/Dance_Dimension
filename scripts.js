document.getElementById('imageUpload').addEventListener('change', function () {
    let file = this.files[0];
    let reader = new FileReader();
    
    reader.onload = function (event) {
    let base64String = event.target.result;
        document.getElementById('preview').src = base64String;
        document.getElementById('preview').style.display = 'block';
        console.log(base64String);
            };
    
        reader.readAsDataURL(file);
            });

// Song Display

document.addEventListener('DOMContentLoaded', function() {
    fetch('Text_Data/song_info.json')
    .then(response => response.json())
    .then(data => {
        const songList = document.getElementById('songList');
        data.forEach(song => {
            let songDiv = document.createElement('div');
            songDiv.className = 'song-item';
            
            // Image
            let img = document.createElement('img');
            img.src = song.image_path.replace(/\\/g, '/'); // Convert Windows backslashes to forward slashes for URL
            img.alt = song.title;
            img.className = 'song-image';
            
            // Song Info
            let infoDiv = document.createElement('div');
            infoDiv.innerHTML = `<h3>${song.title}</h3><p>By: ${song.artist}</p>`;
            
            // Append image and info to the song div
            songDiv.appendChild(img);
            songDiv.appendChild(infoDiv);

            // Optionally, add a click event or button to select the song for gameplay
            // songDiv.onclick = function() {
            //     // Here you would implement what happens when a song is selected
            //     console.log('Selected:', song.title);
            //     // Perhaps navigate to a play page or start the game directly
            });

            // Add the song div to the song list
            songList.appendChild(songDiv);
        });
    })
    .catch(error => console.error('Error loading the song list:', error));