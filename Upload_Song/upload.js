document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('fileUpload');
    const textUpload = document.getElementById('textUpload');
    const textEntryFieldset = document.getElementById('textEntryFieldset');
    const textUploadButton = document.getElementById('textUploadButton');

    fileUpload.onchange = function() {
        textEntryFieldset.style.display = 'none';
    };

    textUpload.onchange = function() {
        textEntryFieldset.style.display = 'block';
    };

    textUploadButton.onclick = function() {
        document.getElementById('uploadMethodValue').value = 'text';
        document.querySelector('form').submit();
    };
});