var vGlobRanks = "";
var vGlobSongs = "";

function loadSongs(songs) {
    for (let i = 0; i < songs.length; i++) {
        if (i == 0) {
            addSongtoList(songs[i], true);
        } else {
            addSongtoList(songs[i]);
        }
    }
}

function addSongtoList(song, firstOne = false) {
    var songName = song.title;
    var newElement = document.createElement("p");
    newElement.id = "song";
    var songNameNode = document.createTextNode(songName);
    newElement.appendChild(songNameNode);
    document.getElementById("songselector").appendChild(newElement);
    if (firstOne) {
        newElement.className = "selected";
    }
}

function loadRanking(ranks, songs, firstTime = true) {
    var selected = document.querySelector("p.selected");
    if (firstTime) {
        vGlobRanks = JSON.parse(JSON.stringify(ranks));
        vGlobSongs = JSON.parse(JSON.stringify(songs));
        ranks = JSON.parse(JSON.stringify(ranks));
        songs = JSON.parse(JSON.stringify(songs));
    } else {
        ranks = JSON.parse(JSON.stringify(ranks));
        songs = JSON.parse(JSON.stringify(songs));
    }
    var id;

    for (let i = 0; i < songs.length; i++) {
        if (selected.textContent == songs[i].title) {
            id = songs[i].ID;
            break;
        }
    }
    Object.entries(ranks).forEach(key => {
        if (key[0] == id) {
            console.log("FOUND");
            addRankToList(key[1]);
        }
    });
}

function updateRanking() {
    var table = document.getElementById("rankingList");
    clearRankingTable(table);
    loadRanking(vGlobRanks, vGlobSongs, false);
}

function addRankToList(rank) {
    for (let i = 0; i < rank.length; i++) {
        var table = document.getElementById("rankingList");
        var row = table.insertRow(-1);
        var nicknameCell = row.insertCell(0);
        var scoreCell = row.insertCell(1);
        
        if (i => 0) {
            nicknameCell.setAttribute("id", "nickname");
            scoreCell.setAttribute("id", "score");
            nicknameCell.innerHTML = rank[i].nickname;
            scoreCell.innerHTML = rank[i].score;
        }
    }
}

function clearRankingTable(table) {
    var rows = table.rows;
    for (let i = rows.length - 1; i > 0; i--) {
        table.deleteRow(i);
    }
}