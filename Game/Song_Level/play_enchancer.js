document.getElementById('songselector').addEventListener('click', function(e) {
    let target = e.target;
    while (target && target !== this) {
        if(target.classList.contains('song-item')) {
            deSelect();
            select(target.parentNode); // Assuming the container has a class or will be the parent of .song-item
            return;
        }
        target = target.parentNode;
    }
  });
  
  function deSelect() {
    var selected = document.querySelector('.song-item.selected');
    if(selected) selected.classList.remove('selected');
  }
  
  function select(e) {
    e.querySelector('.song-item').classList.add('selected');
    let title = e.querySelector('.song-item').textContent.split(' by ')[0];
    updateSongInfo(title);
    document.querySelector('.songInfo').style.display = 'block'; // Show the song info when selected
  }