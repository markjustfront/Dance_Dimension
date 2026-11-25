document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('songselector');
    if (list) {
        list.addEventListener('click', function (event) {
            const entry = event.target.closest('.songEntry');
            if (!entry) {
                return;
            }
            deSelect();
            select(entry);
            buttonStatus();
        });
    }
    const editButton = document.getElementById('edit');
    const removeButton = document.getElementById('remove');
    if (editButton) {
        editButton.addEventListener('click', function () {
            const id = getSelectedId();
            if (!id) {
                return;
            }
            const prefix = typeof DD_PREFIX === 'string' ? DD_PREFIX : '../';
            window.location.href = prefix + 'Song_Select/Edit_Song/edit_song.php?id=' + encodeURIComponent(id);
        });
    }
    if (removeButton) {
        removeButton.addEventListener('click', deleteSong);
    }
    buttonStatus();
});

function deSelect() {
    document.querySelectorAll('.songEntry.selected').forEach(function (el) {
        el.classList.remove('selected');
    });
}

function select(entry) {
    entry.classList.add('selected');
}

function getSelectedId() {
    const selected = document.querySelector('.songEntry.selected');
    return selected ? selected.dataset.id : '';
}

function getSelectedTitle() {
    const selected = document.querySelector('.songEntry.selected .songTitle');
    return selected ? selected.textContent : '';
}

function buttonStatus() {
    const selected = getSelectedId();
    const editButton = document.getElementById('edit');
    const removeButton = document.getElementById('remove');
    const enabled = Boolean(selected);
    if (editButton) {
        editButton.disabled = !enabled;
        editButton.classList.toggle('editButtonEnabled', enabled);
    }
    if (removeButton) {
        removeButton.disabled = !enabled;
        removeButton.classList.toggle('removeButtonEnabled', enabled);
    }
}

function deleteSong() {
    const id = getSelectedId();
    const title = getSelectedTitle() || 'this song';
    if (!id) {
        window.alert('No song selected');
        return;
    }
    if (!window.confirm('Delete "' + title + '"? This cannot be undone.')) {
        return;
    }
    const prefix = typeof DD_PREFIX === 'string' ? DD_PREFIX : '../';
    window.location.href = prefix + 'Song_Select/delete_song_func.php?id=' + encodeURIComponent(id);
}
