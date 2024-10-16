// This is a simulation. Actual file writing would need server-side handling.

// Simulated data store - would be replaced with actual file read/write in a real server environment
let commentsData = { comments: [] };

function addComment() {
    const commentText = document.getElementById('newComment').value;
    if (commentText.trim()) {
        // Simulate saving to file
        commentsData.comments.push(commentText);
        displayComment(commentText);
        document.getElementById('newComment').value = '';
        console.log('Comment added to:', commentsData); // This would normally trigger a file save
    }
}

function displayComment(comment) {
    const commentContainer = document.getElementById('commentContainer');
    const commentElement = document.createElement('div');
    commentElement.className = 'comment';
    commentElement.textContent = comment;
    commentContainer.appendChild(commentElement);
}

function loadComments() {
    // In a real scenario, this would involve reading from comments.json
    // Here, we'll just simulate loading pre-existing comments
    commentsData.comments.forEach(comment => displayComment(comment));
}

// Simulate loading comments from file on page load
window.onload = () => {
    // This would normally be where you'd fetch or read the JSON file
    // For now, we'll just ensure commentsData is initialized
    if (!localStorage.getItem('hasRunBefore')) {
        localStorage.setItem('hasRunBefore', 'true');
        // Here you might initialize from a default file if it's the first load
    }
    loadComments();
};