document.addEventListener('DOMContentLoaded', (event) => {
    const fileInput = document.querySelector('input[name="gameFile"]');
    const textAreaFieldset = document.getElementById('textEntryFieldset');
    const textUploadButton = document.getElementById('textUploadButton');
    const fileUploadButton = document.querySelector('input[type="submit"]');
    const uploadMethodValue = document.getElementById('uploadMethodValue');

    document.querySelectorAll('input[name="uploadMethod"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            var item = event.target.value;
            uploadMethodValue.value = this.value;
            if (item === 'file') {
                fileInput.style.display = 'block';
                textAreaFieldset.style.display = 'none';
                fileUploadButton.style.display = 'block';
                textUploadButton.style.display = 'none';
            } else if (item === 'text') {
                fileInput.style.display = 'none';
                textAreaFieldset.style.display = 'block';
                fileUploadButton.style.display = 'none';
                textUploadButton.style.display = 'block';
            }
        });
    });

    // Assuming textUploadButton should submit the form; if not, remove or adjust this
    textUploadButton.addEventListener('click', function(e) {
        e.preventDefault();
        // Here you might want to add some validation or directly submit the form
        document.querySelector('form').submit();
    });
});