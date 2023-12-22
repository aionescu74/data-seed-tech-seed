<?php
//require_once("connection.inc");

if($appCode != "")
{
    $sel_reports     = "SELECT * FROM seed_filters WHERE appCode = '".$appCode."' AND showInHomeApp = true ORDER BY appCode, filterCode;";
}
else
{
    $sel_reports     = "SELECT * FROM seed_filters ORDER BY appCode, filterCode;";
}
//print($sel_reports);

$result_reports  = $conn -> query($sel_reports);
 //print($conn->error);
?>
<table class="xml_table">
    <tr>
        <th>Filter Code</th>
        <th width="50%">Filter Description</th>
    </tr>
    <tr>
    <?php
    while ($row_filter = $result_reports ->fetch_object())
    {
    $appCode            = $row_filter->appCode;    
    $filterCode         = $row_filter->filterCode;
    //$reportName         = $row_filter->reportName;
    $description  = $row_filter->description;
    $sql_report         = $row_filter->sqlReport;
    ?>
    <tr>
        <td><a href="filter.php?q=&app=<?php print($appCode); ?>&filter=<?php print($filterCode); ?>"><?php print($filterCode); ?></a></td>
        <td><a href="filter.php?q=&app=<?php print($appCode); ?>&filter=<?php print($filterCode); ?>"><?php print($description); ?></a></td>
        
    </tr>
    <?php
    }
    ?>
    </tr>
</table>
<br/>

<div style="text-align:right;">
<?php
print("[+] <a href='entityEdit.php?app=".$appCode."&parent=seed_apps&amp;parentId=".$appCode."&amp;table=seed_filters'>Add new filter</a>");
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>