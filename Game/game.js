document.addEventListener('DOMContentLoaded', function() {
    const arrowsContainer = document.getElementById('arrowsContainer');
    const currentArrowDisplay = document.getElementById('currentArrow');
    const scoreDisplay = document.getElementById('score');
    let score = 0;
    let currentStepIndex = 0;
    let gameStartTime;
    
    const referenceData = 
`4
↓ # 0.5 # 1.5 # blue # large
← # 2.0 # 2.2 # red # small
↑ # 2.5 # 3.0 # green # medium
→ # 3.5 # 4.0 # yellow # large`;

    const steps = referenceData.split('\n').slice(1).map((line, index) => {
        const parts = line.split('#').map(part => part.trim());
        return {
            arrow: parts[0],
            appear: parseFloat(parts[1]) * 1000,
            disappear: parseFloat(parts[2]) * 1000,
            index: index
        };
    });

    function gameLoop(timeStamp) {
        if (!gameStartTime) gameStartTime = timeStamp;
        const progress = timeStamp - gameStartTime;

        steps.forEach(step => {
            if (step.appear <= progress && !step.shown) {
                currentArrowDisplay.textContent = step.arrow;
                step.shown = true;
                setTimeout(() => {
                    if (currentArrowDisplay.textContent === step.arrow) {
                        currentArrowDisplay.textContent = "-";
                    }
                }, step.disappear - step.appear);
            }
        });

        if(steps.every(step => step.shown && progress > step.disappear)) {
            currentArrowDisplay.textContent = "Game Over!";
            return;
        }

        requestAnimationFrame(gameLoop);
    }

    document.addEventListener('keydown', function(e) {
        if(['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            const keyPressed = {'ArrowUp': '↑', 'ArrowDown': '↓', 'ArrowLeft': '←', 'ArrowRight': '→'}[e.key];
            if(keyPressed === currentArrowDisplay.textContent) {
                score += 10;
                scoreDisplay.textContent = `Score: ${score}`;
                currentArrowDisplay.textContent = "-";
            }
        }
    });

    gameLoop();
});