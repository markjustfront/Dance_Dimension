function addScore(name, score) {
    const scoreList = document.getElementById('scoreList');
    const li = document.createElement('li');
    li.textContent = `${name}: ${score}`;
    scoreList.appendChild(li);
    
    let items = Array.from(scoreList.children);
    items.sort((a, b) => {
        let scoreA = parseInt(a.textContent.split(':')[1]);
        let scoreB = parseInt(b.textContent.split(':')[1]);
        return scoreB - scoreA; 
    }).forEach(item => scoreList.appendChild(item));
}

// Default text:
addScore('Elon', 420);
addScore('Buffet', 300);
addScore('Mike', 700);
addScore('John', 930);