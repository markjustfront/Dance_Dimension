document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('fileUpload');
    const textUpload = document.getElementById('textUpload');

    fileUpload.onchange = function() {
        showField('file');
    };

    textUpload.onchange = function() {
        showField('text');
    };

    function showField(type) {
        document.getElementById('fileField').style.display = type === 'file' ? 'block' : 'none';
        document.getElementById('textField').style.display = type === 'text' ? 'block' : 'none';
    }
});