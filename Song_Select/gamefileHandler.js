document.addEventListener('DOMContentLoaded', function () {
    const gamefile = document.getElementById('gamefile');
    const area = document.getElementById('gamefileArea');
    if (!gamefile || !area) {
        return;
    }
    gamefile.addEventListener('change', function () {
        if (gamefile.files && gamefile.files.length) {
            area.removeAttribute('required');
        }
    });
});
