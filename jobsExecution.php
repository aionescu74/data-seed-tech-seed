<?php
// 1. jobsExecution.php se executa din pagina jobs.php prin AJAX, executa joburile mature si afiseaza log-ul
// 2. Toate modificarile trebuie sincronizate cu cron.php, care se executa de catre CRON, dar e aceeasi logica
// TBD: la un moment dat trebuie facuta o functie
require_once("./connection.inc");
require_once("./xml_functions.inc");
require_once("./json_functions.inc");
require_once("./messaging.inc");
require_once("./rest_pipes.inc");
require_once("./jobs_select.inc");
?>



<h2>&#128337; Automatic jobs</h2>
<?php
$query = $jobs_select; // vine din jobs_select.inc
$result = $conn -> query($query);

$xml = GetXMLfromQuery($conn, $query, "jobs", "job");
//print_r ($xml);
$proc = new XSLTProcessor();
$xslTabel = GetXSLTable("jobs", "job", "entityEdit.php", "jobId", "&amp;table=seed_jobs", "_blank", "jobExecution.php");
$proc->importStyleSheet($xslTabel);
//$xmlTabel = $proc->transformToXML($xmlPre);
$xmlTabel = $proc->transformToXML($xml);
print ($xmlTabel);
?>


<br/><br/>




<?php
$queryX = $jobs_select; // vine din jobs_select.inc
//print($queryX);
$resultX = $conn -> query($queryX);
//print_r($resultX);
while($row = $resultX -> fetch_object())
//while($row = $result -> fetch_object())
{
    $jobId           = $row->jobId;
    $jobType         = $row->jobType;
    $jobAction       = $row->jobAction;
    $repetitionType  = $row->repetitionType;
    $repetitionDay   = $row->repetitionDay;
    $repetitionValue = $row->repetitionValue;
    $lastRun         = $row->lastRun;
    $nextRun         = $row->nextRun;
    $secondsToNextRun= $row->secondsToNextRun;
    /*
    if(empty($lastRun))
    {
        print('aaa');
    }
     * 
     */
    
    if($secondsToNextRun <= 0 && $repetitionType != 'once')
    {
        //print('daily');
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      REST PIPES
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "rest_pipe")
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-rest-daily');";
            //$conn -> query($query);
            
            $responseContentType = "";
            $token = "";
            $resp = execRestPipe($conn, $jobId, $token, $responseContentType);
            //print("HTTP_RESPONSE: " . $resp);
            if($resp == 200)
                $okNok = 'OK';
            else
                $okNok = 'NOK';
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      PHP
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "script") // for php scripts, which are, in fact, included in the current run
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-script-daily');";
            //$conn -> query($query);
            
            $resp = '';
            require_once($jobAction);   //with require_once variable valuess from main page are replaced with the ones from called script
            $okNok = 'OK';
        }
        
        
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      CMD: PYTHON, SHELL
        //                                      useful only local, not online
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "cmd")
        {
            $command = escapeshellcmd($jobAction);
            $resp = shell_exec($command);
            $okNok = 'OK';
        }
        
        
        
        

        $query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobId."', now(), '" . $okNok . "', '" . $resp . "');";
        $result = $conn -> query($query);
        //print($conn->errno);
        //$msg = $query;

        //if($conn->errno === 0)
            print("<div class='descriere'>Job: " .$jobId . " ran at: " .$lastRun . "</div>");
        //else
        //    print("<div class='descriere'>&#129310; <font color='red'>ERROR [" . $conn->errno . ": " . $conn->error ."]</font></div>");
    }
    elseif($secondsToNextRun <= 0 && $repetitionType === 'once' && ($lastRun === "" || empty($lastRun)))                //  --> ONCE (one time jobs)
    {
        //print('once');
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      REST PIPES
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "rest_pipe")
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-rest-once');";
            //$conn -> query($query);
            
            $responseContentType = "";
            $token = "";
        
            $resp = execRestPipe($conn, $jobId, $token, $responseContentType);
            //print("HTTP_RESPONSE: " . $resp);
            if($resp == 200)
                $okNok = 'OK';
            else
                $okNok = 'NOK';
        }
            
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      PHP
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "script") // for php scripts, which are, in fact, included in the current run
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-script-once');";
            //$conn -> query($query);
            
            $resp = '';
            require_once($jobAction);   //with require_once variable valuess from main page are replaced with the ones from called script
            $okNok = 'OK';
        }

        $query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobId."', now(), '" . $okNok . "', '" . $resp . "');";
        $result = $conn -> query($query);
        
        print("<div class='descriere'>Job: " .$jobId . " ran at: " .$lastRun . " [ONE TIME JOB]</div>");
    }
    elseif($secondsToNextRun <= 3600 && $repetitionType != 'once')                                                      // jobs to run in the next 60 minutes
    {
        print("<div class='descriere'>Job to run in the next 60 minutes: " .$jobId . " at: " .$nextRun . "</div>");
    }
    elseif($secondsToNextRun <= 3600 && $repetitionType === 'once' && ($lastRun === "" || empty($lastRun)))             // jobs to run in the next 60 minutes --> ONCE
    {
        print("<div class='descriere'>Job to run in the next 60 minutes: " .$jobId . " at: " .$nextRun . " [ONE TIME JOB]</div>");
    }
    //else
    //{
    //    print("<div class='descriere'>Next run: " .$jobId . " at: " .$nextRun . "</div>");
    //}
}
?>
<br/><br/>

<?php
date_default_timezone_set('Europe/Bucharest');
$currTime = date('Y-m-d H:i:s');
?>
<div class='descriere'>
    Last run: <span id='currTime'><?php print($currTime); ?></span> || 
    Interval: <span id='runInterval'></span> seconds || 
    Running time: <span id='runningTime'></span> || 
    Time left to next run: <span id='timeLeft'>0</span> seconds
</div>

<h2>&#128220; Job execution history (last 10 records)</h2>
<?php
//sleep(5);

$query = "SELECT * 
            FROM seed_jobs_log
            ORDER BY logDateTime DESC
            LIMIT 10";
$result = $conn -> query($query);

$xml = GetXMLfromQuery($conn, $query, "jobs", "job");
//print_r ($xml);
$proc = new XSLTProcessor();
$xslTabel = GetXSLTable("jobs", "job", "entityEdit.php", "jobId", "&amp;table=seed_jobs");
$proc->importStyleSheet($xslTabel);
//$xmlTabel = $proc->transformToXML($xmlPre);
$xmlTabel = $proc->transformToXML($xml);
print ($xmlTabel);
?>
