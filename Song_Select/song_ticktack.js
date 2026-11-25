document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('songfile');
    if (!input) {
        return;
    }
    input.addEventListener('change', function (event) {
        const file = event.target.files && event.target.files[0];
        const durationInput = document.getElementById('songDuration');
        if (!file || !durationInput) {
            return;
        }
        const Context = window.AudioContext || window.webkitAudioContext;
        if (!Context) {
            return;
        }
        const audioContext = new Context();
        const reader = new FileReader();
        reader.onload = function (loadEvent) {
            audioContext.decodeAudioData(loadEvent.target.result, function (buffer) {
                durationInput.value = buffer.duration.toFixed(2);
            });
        };
        reader.readAsArrayBuffer(file);
    });
});
