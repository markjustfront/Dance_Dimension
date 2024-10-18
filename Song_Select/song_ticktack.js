document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('songfile').onchange = function(event) {
        let file = event.target.files[0];
        let audioContext = new (window.AudioContext || window.webkitAudioContext)();
        let reader = new FileReader();
        
        reader.onload = function(letar) {
            audioContext.decodeAudioData(e.target.result, function(buffer) {
                document.getElementById('songDuration').value = buffer.duration.toFixed(2); // Set duration in seconds
            });
        };

        reader.readAsArrayBuffer(file);
    };
});