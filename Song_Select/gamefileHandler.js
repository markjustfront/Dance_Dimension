document.getElementById("gamefile").addEventListener("change", function(e) {
    document.getElementById("gamefileArea").setAttribute("style", "display: none;");
    document.getElementById("gamefileArea").removeAttribute("required");
});
document.getElementById("gamefileArea").addEventListener("click", function() {
    document.getElementById("gamefile").setAttribute("type", "hidden");
    document.getElementById("gamefile").removeAttribute("required");
});