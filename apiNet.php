<?php
require_once("./connection.inc");
require_once("./xml_functions.inc");
require_once("./json_functions.inc");
require_once("./messaging.inc");
require_once("./rest_pipes.inc");


/////////////////////////////////////
//                FOR              //
/////////////////////////////////////
$msg = '';

$url =	@$_POST['selEnvironment'];
if($url == "")
{
    $url = @$_GET['selEnvironment'];
}
if($url == "")
{
    $url = "http://localhost/seed/";
}
//print($url);

$urlDest =	@$_POST['selEnvironmentDest'];
if($urlDest == "")
{
    $urlDest = @$_GET['selEnvironmentDest'];
}
if($urlDest == "")
{
    $urlDest = "http://localhost/seed/";
}
//print($urlDest);



$table = @$_POST['table'];
if($table == "")
{
    $table = @$_GET['table'];
}
if($table == "")
{
    $table = "*";
}
//print($table);


$tableDest = @$_POST['tableDest'];
if($tableDest == "")
{
    $tableDest = @$_GET['tableDest'];
}
if($tableDest == "")
{
    $tableDest = $table;
}
if($tableDest == "")
{
    $tableDest = "*";
}
//print($table);


$id =	@$_POST['id'];
if($id == "")
{
    $id = @$_GET['id'];
}
//print($id);

$idDest =	@$_POST['idDest'];
if($idDest == "")
{
    $idDest = @$_GET['idDest'];
}
//print($idDest);


$method = @$_POST['method'];
if($method == "")
{
    $method = @$_GET['method'];
}
//print($method);

if($method === "POST" || $method === "PUT")
{
    $body = @$_POST['txaBody'];
    if($body == "")
    {
        $body = @$_GET['txaBody'];
    }
    
    //print_r($body);
    
    $response = CallSeedAPI($urlDest, $tableDest, $method, '', $body, $json);
    //$v = $response[0];
    //print_r($v['ok']);
    //$isOK = array_search('ok', $v);    
    //print_r($v['error']);
    
    $msg = $json;
}

if($method === "DELETE")
{
    $response = CallSeedAPI($urlDest, $tableDest, 'DELETE', $idDest, '', $json);
    $msg = $json;
}


/////////////////////////////////////
//                PARSE RESPONSE              //
/////////////////////////////////////



//$pointer = ".i[indicator='I4'].val_den_indicator";
//$pointer = ".i[val_den_indicator='CHELTUIELI TOTALE'].val_indicator";
//$pointer = ".j[1].a";
//$pointer = ".an";

//$pointer = ".records";                            // returneaza Array
//$pointer = ".records[0][cod='2P']";                 // returneaza Array
//$pointer = ".records[0][cod='2P'].companie";        // returneaza Valoare
//$pointer = ".records[0][cod=*].companie";        // returneaza prima Valoare
//$pointer = ".records[0][].companie";        // returneaza prima Valoare
//$pointer = ".records[1][].companie";        // returneaza a doua Valoare

//$x = getPointerValue($arr, $pointer);
//print("<br>Valoare = ");
//print_r($x);

?>


<html>
<head>

<title>API Network</title>
<link rel="stylesheet" type="text/css" href="./styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script language=javascript>
function Get()
{
    document.frmForm.method.value = 'GET';
    document.frmForm.submit();
}
function Post()
{
    if(confirm("This will insert SOURCE content at DESTINATION! Confirm?"))
    {
        document.frmForm.method.value = 'POST';
        document.frmForm.submit();
    }
}
function Put()
{
    if(confirm("This will update SOURCE content at DESTINATION! Confirm?"))
    {
        document.frmForm.method.value = 'PUT';
        document.frmForm.submit();
    }
}
function Delete()
{
    if(confirm("Confirm deletion?"))
    {
        document.frmForm.method.value = 'DELETE';
        document.frmForm.submit();
    }
}
</script>
</head>
<body>
    <div class="header">
        <h1><a href="./" class="header">&#127968; localhost</a>&nbsp;&nbsp;>&nbsp; &#128423; API Network</h1>
    </div>



<div class="right">
  

<TABLE width="100%" cellpadding="3" cellspacing="0" class='responsive' style="text-align: center">
	<tr>
            <TD bgcolor="#e6f2ff" style="text-align: center">
                <b>Transfer data from an environment to another</b>
            </TD>
        </tr>
        <?php 
        if($msg != '')
        { 
        ?>
        <tr>
            <td>
                <?php
                $msg = json_encode(json_decode($msg), JSON_PRETTY_PRINT);
                ?>
                <textarea cols="100"><?php print("&nbsp;".$msg) ?></textarea><br/><br/>
            </td>
	</tr>
        <?php 
        } 
        ?>
</TABLE>
<br/>
        
    
    


<form name='frmForm' method='post'>
    
    <div class="descriere">
        &#128423; Source environment:
        <select id="selEnvironment" name="selEnvironment" onchange="Get();">
            <option value="http://localhost/seed/" <?php if($url=="http://localhost/seed/") {print(" selected");} ?>>localhost
            <option value="https://data-seed.tech/seed/" <?php if($url=="https://data-seed.tech/seed/") {print(" selected");} ?>>data-seed.tech
        </select>&nbsp;&nbsp;


        <?php
        $json = "";
        $response = CallSeedAPI ($url, '*' , 'GET', '', '', $json);
        ?>
        &#128479; Source table:
        <select id="table" name="table" onchange="document.frmForm.id.value='';">
            <option value="*">ALL TABLES [*]
            <?php
            foreach($response as $value)
            {
            ?>
            <option value="<?php print($value['TABLE_NAME']); ?>" title="<?php print($value['TABLE_COMMENT']); ?>" <?php if($value['TABLE_NAME'] == $table) {print(" selected");} ?>><?php print($value['TABLE_NAME']); ?>
            <?php
            //print($value['TABLE_COMMENT']);
            }
        ?>
        </select>&nbsp;&nbsp;

        	
        &#128477; Id: 
        <input type="text" name="id" value="<?php print($id); ?>">
        <input type="button" onclick="document.frmForm.id.value='';" value='x'>
        &nbsp;&nbsp;&nbsp;
        <input type="button" name="btnGet" class='button_default' onclick='Get()' value=' Get &#127907;' title="GET all values from SOURCE and DESTINATION">
        
        <br/><br/>



        <?php
            $json = "";
            $response = CallSeedAPI($url, $table , 'GET', $id, '', $json);
            
            $json = json_encode(json_decode($json), JSON_PRETTY_PRINT);
        ?>
        <textarea rows="10" cols="100" name="txaBody"><?php print($json); ?></textarea>
        <br/><br/>
        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="btnPost" onclick='Post()' value=' Post (INSERT)   &#128317; ' title="INSERT BULK: insert all the record that cannot be found on destination (no updates, duplicate fails)" style="width: 200px;">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="btnPut" onclick='Put()' value=' Put (UPDATE) &#128315;' title="UPDATE BULK: only for records found on destination (no insert, new records ignored)">
    </div><br/><br/>
    
    
    <div class="descriere">
        &#128423; Destination environment:
        <select id="selEnvironmentDest" name="selEnvironmentDest" onchange="Get();">
            <option value="http://localhost/seed/" <?php if($urlDest=="http://localhost/seed/") {print(" selected");} ?>>localhost
            <option value="https://data-seed.tech/seed/" <?php if($urlDest=="https://data-seed.tech/seed/") {print(" selected");} ?>>data-seed.tech
        </select>&nbsp;&nbsp;
        
        <?php
        $json = "";
        $response = CallSeedAPI($urlDest, '*' , 'GET', '', '', $json);
        ?>
        &#128479; Destination table:
        <select id="tableDest" name="tableDest">
            <option value="*">ALL TABLES [*]
            <?php
            foreach($response as $value)
            {
            ?>
            <option value="<?php print($value['TABLE_NAME']); ?>" title="<?php print($value['TABLE_COMMENT']); ?>" <?php if($value['TABLE_NAME'] == $tableDest) {print(" selected");} ?>><?php print($value['TABLE_NAME']); ?>
            <?php
            //print($value['TABLE_COMMENT']);
            }
        ?>
        </select>&nbsp;&nbsp;

        &#128477; Id selected: 
        <input type="text" name="idDest" value="<?php print($idDest); ?>">
        <input type="button" onclick="document.frmForm.idDest.value='';" value='x'>
        &nbsp;&nbsp;
        <input type="button" name="btnGetDest" class='button_default' onclick='Get()' value=' Get &#127907;'>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" class='button_delete' onclick='Delete()' value=' Delete &#128502;'><br/><br/>


        <?php
            $json = "";
            $response = CallSeedAPI($urlDest, $tableDest, 'GET', $idDest, '', $json);
            
            $json = json_encode(json_decode($json), JSON_PRETTY_PRINT);
        ?>
        <textarea rows="10" cols="100"><?php print($json); ?></textarea><br/><br/>
    </div>
    
    
    <input type="hidden" name="method">
</form>
</body>
</html>