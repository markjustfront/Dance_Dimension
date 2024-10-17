document.getElementById('songselector').addEventListener('click', function(e) {
  if (e.target.classList.contains('songTitle') || e.target.classList.contains('songDuration')) {
      deSelect();
      select(e.target.closest('.songEntry').querySelector('.songTitle'));
      buttonStatus();
  }
});

function getSelected() {
  var selected = document.querySelector('.songTitle.selected');
  return selected ? selected.textContent : '';
}

function removeSong() {
  var songToRemove = getSelected();
  if(songToRemove) {
      // Here you would typically have logic to remove the song from your list or server
  }
}

function deSelect() {
  var selected = document.querySelector('.songTitle.selected');
  if (selected) selected.classList.remove("selected");
}

function select(e) {
  e.classList.add("selected");
  // seffect.play(); // Uncomment if seffect is defined somewhere
}

function buttonStatus() {
  var selected = getSelected();
  var editButton = document.getElementById("edit");
  var removeButton = document.getElementById("remove");
  
  if (selected) {
      console.log("Enable");
      editButton.className = "button-74 glow editButtonEnabled";
      editButton.onclick = function() {
          location.href = "./Edit_Song/edit_song.php?id=" + encodeURIComponent(selected);
      };
      removeButton.className = "button-74 glow removeButtonEnabled";
      removeButton.onclick = function() {
          location.href = "delete_song_func.php?id=" + encodeURIComponent(selected);
      };
  } else {
      console.log("Disabled");
      editButton.className = "button-74 glow";
      editButton.onclick = null;
      removeButton.className = "button-74 glow";
      removeButton.onclick = null;
  }
}