function loadEditValues(title, author, id) {
    document.getElementById("EditForm").elements.namedItem("title").value = title;
    document.getElementById("EditForm").elements.namedItem("author").value = author;
    document.getElementById("EditForm").elements.namedItem("ID").value = id;
}