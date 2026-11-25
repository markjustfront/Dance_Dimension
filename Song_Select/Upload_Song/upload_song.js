document.addEventListener('DOMContentLoaded', function () {
    const fileUpload = document.getElementById('fileUpload');
    const textUpload = document.getElementById('textUpload');
    if (!fileUpload || !textUpload) {
        return;
    }
    function showField(type) {
        const fileField = document.getElementById('fileField');
        const textField = document.getElementById('textField');
        const gamefile = document.getElementById('gamefile');
        const area = document.getElementById('gamefileArea');
        if (fileField) {
            fileField.hidden = type !== 'file';
        }
        if (textField) {
            textField.hidden = type !== 'text';
        }
        if (gamefile) {
            gamefile.required = type === 'file';
        }
        if (area) {
            area.required = type === 'text';
        }
    }
    fileUpload.addEventListener('change', function () {
        showField('file');
    });
    textUpload.addEventListener('change', function () {
        showField('text');
    });
});
