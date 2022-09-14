function setScores(scores, wedstrijdId) {
    var winnaar = 0;
    var gelijkspel = 0;

    var score1 = scores[0];
    var score2 = scores[4];
  
    if(scores.length>18){
      
      gelijkspel = scores[18]
    }
    if(score1>score2){
      winnaar = 1;
    }
    else{
      if(gelijkspel==0){
        winnaar = 2;
      }
      else{
          winnaar = gelijkspel;
      }
    }
    updateValues(score1,score2,winnaar,wedstrijdId);
}

function updateValues(score1, score2, winnaar, wedstrijdId){
    var xhttp = new XMLHttpRequest();

     xhttp.open("POST", "updater.php?w="+winnaar+"&&s1="+score1+"&&s2="+score2+"&&wID="+wedstrijdId);

      xhttp.send();
    
}
