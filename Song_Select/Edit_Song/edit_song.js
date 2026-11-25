function loadEditValues(title, author, id) {
    const form = document.getElementById('EditForm');
    if (!form) {
        return;
    }
    if (form.elements.namedItem('title')) {
        form.elements.namedItem('title').value = title;
    }
    if (form.elements.namedItem('author')) {
        form.elements.namedItem('author').value = author;
    }
    if (form.elements.namedItem('ID')) {
        form.elements.namedItem('ID').value = id;
    }
}
