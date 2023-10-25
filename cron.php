<?php
// 1. Pagina cron.php se executa de catre CROM la fiecare 5 minue (sau cat se seteaza jobul)
// -> in timp ce jobsExecution.php se executa din pagina jobs.php prin AJAX, executa joburile mature si afiseaza log-ul
// 2. Toate modificarile trebuie sincronizate cu jobsExecution.php, care se executa din jobs.php (AJAX), dar e aceeasi logica
// TBD: la un moment dat trebuie facuta o functie
// 3. Setare CRON:
// */5	*	*	*	*	/usr/local/bin/php /home/intheory/public_html/seed/cron.php


//set_include_path('/home/intheory/public_html'); NU A MERS, PUTEM INCERCA O VARIABILA GLOBALA
require_once("/home/intheory/public_html/connection.inc");

//DEBUG STUFF:
//$query = "INSERT INTO seed_jobs_log VALUES (default, 'start', now(), 'OK', 'cron.php');";
//$conn -> query($query);

require_once("/home/intheory/public_html/seed/xml_functions.inc");
require_once("/home/intheory/public_html/seed/json_functions.inc");
require_once("/home/intheory/public_html/seed/messaging.inc");
require_once("/home/intheory/public_html/seed/rest_pipes.inc");
require_once("/home/intheory/public_html/seed/jobs_select.inc");
$queryX = $jobs_select; // vine din jobs_select.inc si este un SQL mare care extrage joburile care trebuie rulate
$resultX = $conn -> query($queryX);


while($row = $resultX -> fetch_object())
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
    
    //if($repetitionType != 'once')
    if($secondsToNextRun <= 0 && $repetitionType != 'once')
    {
        //print('daily');
        $responseContentType = "";
        $token = "";
        
        
        //$query = "INSERT INTO seed_jobs_log VALUES (default, 'daily', now(), 'OK', '123');";
        //$result = $conn -> query($query);
        //print('aa1');
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      REST PIPE JOB
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "rest_pipe")
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-rest-daily');";
            //$conn -> query($query);
            
            
            $resp = execRestPipe($conn, $jobAction, $token, $responseContentType);
        
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
            $query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-script-daily');";
            $conn -> query($query);
            
            $resp = '';
            require_once($jobAction);   //with require_once variable valuess from main page are replaced with the ones from called script
            $okNok = 'OK';
        }

        $query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobId."', now(), '" . $okNok . "', '" . $resp . "');";
        $result = $conn -> query($query);
        
    }
    elseif($secondsToNextRun <= 0 && $repetitionType === 'once' && ($lastRun === "" || empty($lastRun)))                //  --> ONCE (one time jobs)
    {
        //print('once');
        $responseContentType = "";
        $token = "";
        
        
        //$query = "INSERT INTO seed_jobs_log VALUES (default, 'once', now(), 'OK', '123');";
        //$result = $conn -> query($query);
        
        /////////////////////////////////////////////////////////////////////////////////////////////
        //                                      REST PIPE JOB
        /////////////////////////////////////////////////////////////////////////////////////////////
        if($jobType === "rest_pipe")
        {
            // DEBUG STUFF:
            //$query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobAction."', now(), 'OK', 'cron-rest-once');";
            //$conn -> query($query);
            
            $resp = execRestPipe($conn, $jobAction, $token, $responseContentType);
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
    }
}

//testare
//$query = "INSERT INTO seed_jobs_log VALUES (default, 'end', now(), 'OK', '1234');";
//$conn -> query($query);

//print('aaa');
?>
