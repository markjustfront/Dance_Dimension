// Global variables to hold rankings and songs data
var vGlobRanks = "";
var vGlobSongs = "";

// Function to load all songs into a list
function loadSongs(songs) {
    for (let i = 0; i < songs.length; i++) {
        // The first song added will be marked as selected by default
        if (i == 0) {
            addSongtoList(songs[i], true);
        } else {
            addSongtoList(songs[i]);
        }
    }
}

// Function to add a song to the list on Leaderboard
function addSongtoList(song, firstOne = false) {
    var songName = song.title;
    var newElement = document.createElement("p");
    newElement.id = "song"; // Each song gets an ID of 'song'
    var songNameNode = document.createTextNode(songName);
    newElement.appendChild(songNameNode);
    document.getElementById("songselector").appendChild(newElement);
    if (firstOne) {
        newElement.className = "selected"; // Mark as selected if it's the first or specified
    }
}

// Function to load rankings on Leaderboard
function loadRanking(ranks, songs, firstTime = true) {
    var selected = document.querySelector("p.selected"); // Get the currently selected song
    if (firstTime) {
        // Clone the ranks and songs to globals for later use without modifying original data
        vGlobRanks = JSON.parse(JSON.stringify(ranks));
        vGlobSongs = JSON.parse(JSON.stringify(songs));
    } else {
        // If not the first time, we still clone to prevent modifying global data directly
        ranks = JSON.parse(JSON.stringify(ranks));
        songs = JSON.parse(JSON.stringify(songs));
    }
    
    var id;
    // Find the ID of the selected song
    for (let i = 0; i < songs.length; i++) {
        if (selected.textContent == songs[i].title) {
            id = songs[i].ID;
            break;
        }
    }
    
    // Display rankings for the selected song ID
    Object.entries(ranks).forEach(key => {
        if (key[0] == id) {
            console.log("FOUND");
            addRankToList(key[1]);
        }
    });
}

// Function to refresh the ranking list based on current selection
function updateRanking() {
    var table = document.getElementById("rankingList");
    clearRankingTable(table);
    loadRanking(vGlobRanks, vGlobSongs, false); // false indicates this is not the first load
}

// Function to add rankings to the ranking list table
function addRankToList(rank) {
    for (let i = 0; i < rank.length; i++) {
        var table = document.getElementById("rankingList");
        var row = table.insertRow(-1); // Add new row to the end
        var nicknameCell = row.insertCell(0);
        var scoreCell = row.insertCell(1);
        
        if (i >= 0) { // Note: There's a typo in the original condition. It should probably be `i >= 0` to affect all rows
            nicknameCell.setAttribute("id", "nickname");
            scoreCell.setAttribute("id", "score");
            nicknameCell.innerHTML = rank[i].nickname;
            scoreCell.innerHTML = rank[i].score;
        }
    }
}

// Function to clear the existing rankings from the table
function clearRankingTable(table) {
    var rows = table.rows;
    for (let i = rows.length - 1; i > 0; i--) {
        table.deleteRow(i); // Leave the header row (if any) intact by starting from the last child
    }
}