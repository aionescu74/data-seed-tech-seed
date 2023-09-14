<?php
// 1. Pagina jobs.php este cea care executa dinamic joburile din BROWSER
// Ea indeplineste acelasi rol ca si cron.php dar are avantajul unui UI.
// Se va folosi pe instantele SEED locale, iar pe cele online daca nu exista CRON configurat sa ruleze cron.php.
// Nu e nicio problema daca avem si CRON configurat si rulam si jobs.php in BROWSER.

// 2. La intervalul setat cu $EXEC_INTERVAL se cheama jobsExecution.php care face acelasi lucru ca si cron.php, 
// adica extrage joburile care trebuie rulate si le executa.

require_once("./connection.inc");
require_once("./xml_functions.inc");
require_once("./messaging.inc");
require_once("./jobs_select.inc");

$EXEC_INTERVAL = 600000;    //60000 milisecunde --> 1 minut
?>

<html>
<head>
<script type="text/javascript">
var tim = 0;
var EXECUTION_INTERVAL = <?php print($EXEC_INTERVAL); ?>;    //60000 milisecunde --> 1 minut


function jobExecute() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState === 4 && this.status === 200) {
          document.getElementById("jobResponse").innerHTML = this.responseText;
          document.getElementById("runInterval").innerHTML = EXECUTION_INTERVAL/1000;
        }
    };

    xhttp.open("GET", "jobsExecution.php", false);
    xhttp.send();
}





function repeat(ms)  //ms = milisecunde
{
    tim = setInterval(jobExecute, ms);
}


function pause() {
  clearInterval(tim);
}


function load()
{

    jobExecute();
    
    
    currTime = document.getElementById("currTime").innerHTML;
    
    document.getElementById("runningTime").innerHTML = currTime;
    
    var countDownDate = new Date(currTime).getTime();
    var x = setInterval(
    function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = now - countDownDate;
        

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (EXECUTION_INTERVAL * 60 * 24));
        var hours = Math.floor((distance % (EXECUTION_INTERVAL * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (EXECUTION_INTERVAL * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (EXECUTION_INTERVAL)) / 1000);

    // Output the result in an element with id="demo"
    document.getElementById("runningTime").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";
    document.getElementById("timeLeft").innerHTML = EXECUTION_INTERVAL/1000 - seconds;

    // If the count down is over, write some text 
    if (distance < 0) {
      clearInterval(x);
      document.getElementById("runningTime").innerHTML = "EXPIRED";
    }
    }, 1000);
}
</script>
<title>Automatic jobs</title>
<link rel="stylesheet" type="text/css" href="./styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body onload="load(); repeat(EXECUTION_INTERVAL)">
<div class="header">
    <h1><a href="./" class="header">&#127968; Home</a>&nbsp;&nbsp;>&nbsp; Automatic jobs</h1>
</div>

<div class="right">
    <div id="jobResponse">...</div>
</div>
</body>
</html>