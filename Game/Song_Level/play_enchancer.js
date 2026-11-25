document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('songselector');
    if (!list) {
        return;
    }
    list.addEventListener('click', function (event) {
        let target = event.target;
        while (target && target !== this) {
            if (target.classList && target.classList.contains('song-item')) {
                deSelect();
                select(target.parentNode);
                return;
            }
            target = target.parentNode;
        }
    });
});

function deSelect() {
    const selected = document.querySelector('.song-item.selected');
    if (selected) {
        selected.classList.remove('selected');
    }
}

function select(container) {
    const item = container.querySelector('.song-item');
    if (!item) {
        return;
    }
    item.classList.add('selected');
    const title = item.textContent.split(' by ')[0];
    if (typeof updateSongInfo === 'function') {
        updateSongInfo(title);
    }
    const info = document.querySelector('.songInfo');
    if (info) {
        info.style.display = 'block';
    }
}
