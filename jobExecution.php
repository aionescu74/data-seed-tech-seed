<?php
//1. Pagina jobExecution se foloseste la rularea manuala a unui job.
//2. Ea se apeleaza din jobs.php (AJAX) care inlocuieste CRON in BROWSER.
require_once("./connection.inc");
require_once("./xml_functions.inc");
require_once("./json_functions.inc");
require_once("./messaging.inc");
require_once("./rest_pipes.inc");


/////////////////////////////////////
//                FOR              //
/////////////////////////////////////
$for =	@$_POST['hdnFor'];
if($for == "")
{
    $for = @$_GET['for'];
}
//print($for);
$msg = '';


$jobId =	@$_POST['jobId'];
if($jobId == "")
{
    $jobId = @$_GET['jobId'];
}

$query = "SELECT * 
            FROM seed_jobs
            WHERE jobId = '" . $jobId . "';";
$result = $conn -> query($query);

$xml = GetXMLfromQuery($conn, $query, "jobs", "job");
//print_r ($xml);
$proc = new XSLTProcessor();
$xslTabel = GetXSLTable("jobs", "job", "entityEdit.php", "jobId", "&amp;table=seed_jobs");
$proc->importStyleSheet($xslTabel);
//$xmlTabel = $proc->transformToXML($xmlPre);
$xmlTabel = $proc->transformToXML($xml);

//print ($xmlTabel);

/*
while($row = $result -> fetch_object())
{
    $jobId = $row->jobId;
}

*/
$row = $result -> fetch_object();
$jobType = $row->jobType;
$jobAction = $row->jobAction;
$repetitionType = $row->repetitionType;
$repetitionValue = $row->repetitionValue;
//print($jobType);



////////////////////////////////////////////////////////////////////////////////
//                                    SAVE                                    //
////////////////////////////////////////////////////////////////////////////////
if($for == "SAVE")
{
    if($jobType == "rest_pipe")
    {
        $responseContentType = "";
        $token = "";
        execRestPipe($conn, $jobId, $token, $responseContentType);
    }
    elseif($jobType == "script")
    {
        //print('aaa');
        require_once($jobAction);   //with require_once variable values from main page are replaced with the ones from called script
        //exec('php concor.php');
        $okNok = 'OK';
    }


    $query = "INSERT INTO seed_jobs_log VALUES (default, '".$jobId."', now(), 'OK', '');";
    $result = $conn -> query($query);
    //$msg = $query;
    
    if($conn->errno === 0)
        $msg = "&#128076; <font color='blue'>JOB RUN SUCCESSFULLY </font>";
    else
        $msg = "&#129310; <font color='red'>ERROR [" . $conn->errno . ": " . $conn->error ."]</font>";
}
?>


<html>
<head>
<script type="text/javascript">
var tim = 0;
var EXECUTION_INTERVAL = 600000;    //milisecunde


function jobExecute() {
    if(confirm('Confirm execution!'))
    {
        document.frmForm.hdnFor.value = "SAVE";
        document.frmForm.submit();
    }
}
</script>
<title>Job manual execution</title>
<link rel="stylesheet" type="text/css" href="./styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="header">
        <h1><a href="./" class="header">&#127968; Home</a>&nbsp;&nbsp;>&nbsp; Job manual execution</h1>
    </div>



<div class="right">
    <h2>&#127937; Job manual execution</h2>
    <?php
    print($xmlTabel);
    ?>

    
    <br/><br/><br/>
<TABLE width="100%" cellpadding="3" cellspacing="0" class='responsive' style="text-align: center">
	<tr>
            <TD bgcolor="#e6f2ff" style="text-align: center">
                <b><?php print("&nbsp;".$msg) ?></b>
            </TD>
	</TR>
</TABLE>
<br/>
        
    
    
<h2>&#128220; Job execution history (last 10 records)</h2>

<?php
$query = "SELECT * 
            FROM seed_jobs_log
            WHERE jobId = '" . $jobId . "'
            ORDER BY logDateTime DESC
            LIMIT 10;";
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
<br/><br/><br/>

    <div class="card">
        <?php
        $query = "SELECT max(logDateTime) as m
            FROM seed_jobs_log
            WHERE jobId = '" . $jobId . "';";
        $result = $conn -> query($query);
        $row = $result -> fetch_object();
        //$m = $row->m;
        ?>
        Last run: <?php print($row->m); ?><br/><br/>
        
        <input type="button" value="&#128472; Execute job" onclick="jobExecute()">
    </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<form name='frmForm' method='post'>
    <input type="hidden" name="hdnFor">
</form>
</body>
</html>