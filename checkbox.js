function toggleCheckBoxes() {
    checkboxes = document.getElementsByClassName('checkBox');
    
    for(var i=0; i<checkboxes.length; i++) {
      if(!checkboxes[i].checked){
        checkboxes[i].checked = true;
      } else {
        checkboxes[i].checked = false;
      }
      
    }
      
  }
  
function redirect(link){

    window.location.replace(link);

  }
  
function submitValues(){

    selectboxes = document.getElementsByClassName('text-input2');
    
    for(var i=0; i<selectboxes.length; i++) {

        setScores(selectboxes[i].value, selectboxes[i].id);
        
      }
  
  }