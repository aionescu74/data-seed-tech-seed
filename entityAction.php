<?php
require_once("./connection.inc");
require_once("./xml_functions.inc");
require_once("./json_functions.inc");
require_once("./messaging.inc");
require_once("./rest_pipes.inc");

$msg = '';

$entityActionId =	@$_POST['action'];
if($entityActionId == "")
{
    $entityActionId = @$_GET['action'];
}

$entityId =	@$_POST['id'];
if($entityId == "")
{
    $entityId = @$_GET['id'];
}




$query = "SELECT * 
            FROM seed_entity_actions
            WHERE id = '" . $entityActionId . "';";
$result = $conn -> query($query);

$xml = GetXMLfromQuery($conn, $query, "actions", "action");
//print_r ($xml);
$proc = new XSLTProcessor();
$xslTable = GetXSLTable("actions", "action", "entityEdit.php", "entityActionId", "seed_entity_actions");
$proc->importStyleSheet($xslTable);
//$xmlTable = $proc->transformToXML($xmlPre);
$xmlTable = $proc->transformToXML($xml);

//print ($xmlTable);

/*
while($row = $result -> fetch_object())
{
    $entityActionId = $row->entityActionId;
}

*/
$row = $result -> fetch_object();
$actionType         = $row->actionType;
$actionDescription  = $row->actionDescription;
$action             = $row->action;
$table              = $row->table;
//print($actionType);



////////////////////////////////////////////////////////////////////////////////
//                                    SAVE                                    //
////////////////////////////////////////////////////////////////////////////////
$rez = "";

$msg = $action;

$responseContentType = "";
$token = "";
$httpCode = "";
    
if($actionType === "api_get")
{
    
    $key_value = [];
    $key_value["table"] = $table;
    $key_value["id"] = $entityId;
    //$body = '{"table" : $table}';
    $rez = CallAPI("GET", $action, $key_value, "", "", "", $responseContentType, $httpCode);
    //$rez ="aaa";
}
elseif($actionType === "api_delete")
{
    //print($entityId);
    $key_value = [];
    $key_value["table"] = $table;
    $key_value["id"] = $entityId;
    //$body = '{"table" : "' . $table . '", "id" : "' . $entityId . '"}';
    $rez = CallAPI("DELETE", $action, $key_value, "", "", "", $responseContentType, $httpCode);
}
elseif($actionType === "rest_pipe") //TBD: UNDER PROGRESS:
{
    $responseContentType = "";
    $token = "";
    $rez = execRestPipe($conn, $entityActionId, $token, $responseContentType);
}
elseif($actionType === "script")
{
    require_once($action);   //with require_once variable valuess from main page are replaced with the ones from called script
    $rez = 'OK';
}
elseif($actionType === "cmd")
{
    $action = str_replace("{table}", $table, $action);
    $action = str_replace("{id}", $entityId, $action);
    //$entityId
    
    //$command = escapeshellcmd($action);
    
    $rez = shell_exec($action . "  2>&1; echo $?");
    
    //$msg = $command;
    //$okNok = 'OK';
}
?>


<html>
<head>

<title>Entity action execution</title>
<link rel="stylesheet" type="text/css" href="./styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="header">
        <h1><a href="./" class="header">&#127968; Home</a>&nbsp;&nbsp;>&nbsp; &#127937; Entity action execution</h1>
    </div>



<div class="right">
    <TABLE width="100%" cellpadding="3" cellspacing="0" class='responsive' style="text-align: center">
            <tr>
                <TD bgcolor="#e6f2ff" style="text-align: center">
                    <b><?php print($actionType.":&nbsp;".$msg) ?></b>
                </TD>
            </TR>
    </TABLE>
    <br/>
    <?php
    print($xmlTable);
    ?>
    
    <br/>
    Response:
    <br/>
    <textarea rows="10" cols="100"><?php print($rez); ?></textarea><br/><br/>
</div>    
    


<form name='frmForm' method='post'>
    <input type="hidden" name="hdnFor">
</form>
</body>
</html>