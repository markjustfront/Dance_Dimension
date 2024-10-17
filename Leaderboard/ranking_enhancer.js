document.querySelector('div').addEventListener('click', function (e) {
    var selected;
    if (e.target.tagName === 'P') {
      if (e.target.id == "song") {
        if (e.target.textContent != getSelected()) {
            deSelect();
            select(e);
            updateRanking(); 
        }
        
      }
      
    }
  
  });
  function getSelected() {
    var selected = document.querySelector('p.selected');
    return selected.textContent;
  }
  function removeSong() {
    getSelected();
  }
  function deSelect() {
    var selected = document.querySelector('p.selected');
    selected.className = "";
  }
  function select(e) {
    var objective = e.target;
    objective.className = "selected";
    seffect.play();
  }