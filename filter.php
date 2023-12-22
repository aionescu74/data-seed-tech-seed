<?php
require_once("./connection.inc");
require_once("./counter.inc");
require_once("./xml_functions.inc");
require_once("./appCode.inc");


$table = "";


/////////////////////////////////////
//                PAGE              //
/////////////////////////////////////
$page =	@$_POST['p'];
if($page == "")
{
    $page = @$_GET['p'];
}
if($page == "")
{
    $page = 1;
}
//print($page);


if(count($_GET) > 0)
{
    //print_r($_GET);
    $id_name = array_keys($_GET)[0];
    $id_value = $_GET[$id_name];
    //var_dump($_GET);
}

if($id_name == "")
{
    if(count($_POST) > 0)
    {
        //print_r($_POST);
        $id_name = array_keys($_POST)[0];
        $id_value = $_POST[$id_name];
        //var_dump($_POST);
    }
}

//print($id_value);


$filterCode = @$_GET['filter'];
//print $filterCode;
if($filterCode == "")
{
    $filterCode = @$_POST['filter'];
}
/*
if($filterCode == "")
{
    $filterCode = 1;
}
 * 
 */

$parent  =	@$_POST['parent'];
if($parent == "")
    $parent  = @$_GET['parent'];

        

$query_filter = "SELECT * FROM seed_filters WHERE filterCode = '".$filterCode."'";
//print($query);
$result_filter = $conn -> query($query_filter);
$filter = $result_filter ->fetch_object();

$sql_report         = $filter->sqlReport;
$filter_query = str_replace('???', $id_value, $sql_report);

//print($filter_query);

$linkId            = $filter->linkId;        
$link_details      = $filter->linkDetails;
$linkAddress       = $filter->linkAddress;
$filterName        = $filter->filterCode;
$filterDescription = $filter->description;


/*
$result = $conn -> query($filter_query);


//print("<li>". $row->description);
//print("<span title='". $filter_query . "'>". $row->description."</span>");
$xml = GetXMLfromQuery($conn, $query_entity_item_filter, 'records', 'record');
//print_r($xml);

$proc = new XSLTProcessor();

//$xslTable = GetXSLTable('records', 'record');
$xslTable = GetXSLTable('records', 'record', 'filter.php', "", "&amp;app=".$appCode."&amp;table=".$link_details, "_self", "", "xml_filter");
$proc->importStyleSheet($xslTable);
//$xmlTable = $proc->transformToXML($xmlPre);
$xmlTable = $proc->transformToXML($xml);

print ($xmlTable);
print("<br/>");
*/




//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
//                       MAIN SELECT                        //
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////
$no_of_records_per_page = 15;
$offset = ($page - 1) * $no_of_records_per_page;

//print($query);
$result = $conn -> query($filter_query);
$total_rows = mysqli_num_rows($result);
$total_pages = ceil($total_rows / $no_of_records_per_page);

//print($total_pages);
$query = str_replace(";", "", $query);
$query = $query . " LIMIT ".$offset.", ".$no_of_records_per_page.";";
//print($query);
?>

<html>
<head>
    <title><?php print($filterName); ?></title>
    <link rel="stylesheet" type="text/css" href="./styles.css">
</head>
<body>
<div class="header">
    <h1><a href="./" class="header">&#127968; <?php print($Site); ?></a>
        &nbsp;&nbsp;>&nbsp; <a href="./indexApp.php?app=<?php print($appCode); ?>" class="header"><?php print($appIcon); ?> <?php print($appName); ?></a>
        
        
        &nbsp;&nbsp;>&nbsp; <?php print($filterName); ?></h1>
    
</div>
<div class="container">
   <?php require_once("./menu.inc"); ?>
   <?php require_once("./menu_left.inc"); ?>
   
   <div class="right">
    <div style="border-style: groove; border-width: 1px; border-radius: 3px; background-color: #ffffdc; padding: 5px">
        <b><a href="./">&#127968; <?php print($Site); ?></a></b> &nbsp; &gt;
        &nbsp; 
        <b><a href="./indexApp.php?app=<?php print($appCode); ?>"><?php print($appIcon); ?> <?php print($appName); ?></a></b> &nbsp; &gt;
        &nbsp; 
        
        
        <?php
        if ($parent != "")
        {
            ?>
            <a href="./filter.php?q=<?php print($id_value); ?>&filter=<?php print($parent); ?>&app=<?php print($appCode); ?>"><?php print($parent); ?></a>
            &nbsp; &gt;
            <?php
        }
        ?>
        
        
        <?php 
        print($filterDescription); 
        
        ?>
    </div><br />
   
<?php
// comes from xml_functions.inc:
$xml = GetXMLfromQuery($conn, $filter_query);
//print_r ($xml);

//$proc = new XSLTProcessor();




////////////////////////////////////////////////////////////////////////////////
// DISPLAY THE REPORT
////////////////////////////////////////////////////////////////////////////////
//$xslPreprocesare = new DOMDocument();
//$xslPreprocesare->load('filter_preprocesare.xsl');
//$proc->importStyleSheet($xslPreprocesare);
//$xmlPre = $proc->transformToXML($xml);
//print ($xmlPre);
//$xmlPre = simplexml_load_string($xmlPre);

//$xslTable = GetXSLTabel($root_element_name, $element_name, $linkAddress,    linkId, $filterCode);
//$xslTable = GetXSLTabel($root_element_name, $element_name, $linkAddress,  linkId, $filterCode."&amp;app=".$appCode);
//$xslTable = GetXSLTable('records', 'record', $linkAddress, $linkId, $filterCode."&amp;app=".$appCode);
//$xml = GetXMLfromQuery($conn, $filter_query, 'records', 'record');
//print_r($xml);

$proc = new XSLTProcessor();

//$xslTable = GetXSLTable('records', 'record');
//$xslTable = GetXSLTable('records', 'record', $linkAddress, $linkId, "&amp;app=".$appCode."&amp;table=".$link_details, "_self", "", "xml_filter");

if($linkAddress == 'entityEdit.php' || $linkAddress == './entityEdit.php')
{
    $xslTable = GetXSLTable('records', 'record', $linkAddress, $linkId, "&amp;app=".$appCode."&amp;table=".$link_details, "_blank", "");
}
elseif($linkAddress == 'filter.php' || $linkAddress == './filter.php')
{
    $xslTable = GetXSLTable('records', 'record', $linkAddress, $linkId, $filterCode."&amp;app=".$appCode."&amp;filter=".$link_details, "_self");
}
else
{
    $xslTable = GetXSLTable('records', 'record', $linkAddress, $linkId, $filterCode."&amp;app=".$appCode."&amp;filter=".$link_details, "_self");
}


$proc->importStyleSheet($xslTable);
//$xmlTable = $proc->transformToXML($xmlPre);
$xmlTable = $proc->transformToXML($xml);

print ($xmlTable);

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
if($page > 1)
{
?>
<a href='filter.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>&p=1'>[ &lt;&lt; ]</a>
&nbsp;
<a href='filter.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>&p=<?php print($page - 1); ?>'>[ &lt; ]</a>
<?php
}
else
{
?>
<font color="gray">[ &lt;&lt; ]</font>
&nbsp;
<font color="gray">[ &lt; ]</font>
<?php
}
?>

&nbsp;

<font color="gray">&nbsp; [ Page <?php print($page); ?> ] &nbsp;</font>

&nbsp;

<?php
if($page < $total_pages)
{
?>
<a href='filter.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>&p=<?php print($page + 1); ?>'>[ &gt; ]</a>
&nbsp;
<a href='filter.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>&p=<?php print($total_pages); ?>'>[ &gt;&gt; ]</a>
<?php
}
else
{
?>
<font color="gray">[ &gt; ]</font>
&nbsp;
<font color="gray">[ &gt;&gt; ]</font>
<?php
}
?>    
    
    
    
<br/><br/>
<?php
//tabelul cu sumele:

$xslTableSUM = GetXSLTableSUM($conn, $filter_query, 'records', 'record', $linkAddress, $linkId, $filterCode."&amp;app=".$appCode);
$proc->importStyleSheet($xslTableSUM);
//$xmlTable = $proc->transformToXML($xmlPre);
$xmlTable = $proc->transformToXML($xml);

//print ($xmlTable);  // este afisat mai jos intr-un DIV ascuns
?>
    
<div id='divTotaluri' class='reportDescription' style='display:none;'><?php print($xmlTable); ?></div><br />
<br/><br/>
    
    
    
    
    
    
<br /><br />
<div style="border-style: groove; border-width: 1px; border-radius: 3px; background-color: #ffffdc; padding: 5px">
    <a target="_blank" href="filter_csv.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>">[ Download CSV ]</a>
    
    
    
    &nbsp;
    <a href="#" onclick="javascript:document.getElementById('divTotaluri').style.display = 'inline-block';" title='Totals applies just to current page!'>[ &#8721; Totals ]</a> 
    
    &nbsp;
    <a target="_blank" href="graph.php?app=<?php print($appCode) ?>&id=<?php print($filterCode); ?>">[ &#128480; Graph ]</a> 
    
    &nbsp;
    <a href="#" onclick="javascript:document.getElementById('divDescriere').style.display = 'inline-block';">[ &#128161; Report Description ]</a> 
</div>



<div id='divDescriere' class='filterDescription' style='display:none;'><?php print(nl2br($filterDescription)); ?></div><br />

    

</div>
</div>
</body>
</html>
