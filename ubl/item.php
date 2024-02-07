<?php
require_once("../connection.inc");
//require_once("../counter.inc");
//require_once("constante.inc");
//require_once("./ubl_functions.inc");


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


$fast_invoice_id    = @$_POST['fast_invoice_id'];
$fast_client_id     = @$_POST['fast_client_id'];

$ItemID                             = @$_POST['ItemID'];
$InvoicedQuantity                   = @$_POST['InvoicedQuantity'];
$InvoicedQuantityUnitCode           = @$_POST['InvoicedQuantityUnitCode'];
$LineExtensionAmount                = @$_POST['LineExtensionAmount'];
$ItemName                           = @$_POST['ItemName'];
$SellersItemIdentificationID        = @$_POST['SellersItemIdentificationID'];
$ItemClassificationCode             = @$_POST['ItemClassificationCode'];
$ItemClassificationCodeListID       = @$_POST['ItemClassificationCodeListID'];
$ClassifiedTaxCategoryID            = @$_POST['ClassifiedTaxCategoryID'];
$ClassifiedTaxCategoryPercent       = @$_POST['ClassifiedTaxCategoryPercent'];
$ClassifiedTaxCategoryTaxSchemeID   = @$_POST['ClassifiedTaxCategoryTaxSchemeID'];
$PriceAmount                        = @$_POST['PriceAmount'];
$PriceAmountCurrencyID              = @$_POST['PriceAmountCurrencyID'];
$PriceBaseQuantity                  = @$_POST['PriceBaseQuantity'];
$PriceBaseQuantityUnitCode          = @$_POST['PriceBaseQuantityUnitCode'];
$TaxExemptionReasonCode             = @$_POST['TaxExemptionReasonCode'];


?>


<html lang="RO">
<head>
<meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Generare XML pentru sistemul e-factura</title>
<meta name="description" content="Generare rapidă UBL pentru sistemul e-factura"/>
<!--link rel="shortcut icon" href="./favicon.ico" /-->
<link name="autdor" content="dataseed.info" />
<link rel="stylesheet" type="text/css" href="./styles.css">
<!--script type="text/javascript" src="./scripturi.js"></script-->
<script language=javascript>
function Save()
{
    if(document.frmForm.ID.value === "")
    //if(document.getElementsByName("ID").value === undefined)
    {
        alert("EROARE! Completați numărul facturii!");
        document.frmForm.ID.focus();
        return;
    }
    if(document.frmForm.IssueDate.value === "")
    {
        alert("EROARE! Completați data facturii!");
        document.frmForm.IssueDate.focus();
        return;
    }
    if(document.frmForm.SUPPayeeFinancialAccountID.value === "")
    {
        alert("EROARE! Completați IBAN-ul furnizorului!");
        document.frmForm.SUPPayeeFinancialAccountID.focus();
        return;
    }
    
    document.frmForm.hdnFor.value = "SAVE";
    document.frmForm.submit();
}
function AddNew()
{
    //document.frmForm..value = "";
    //document.getElementById("frmForm").reset();
    var elements = frmForm.elements;
    for(i=0; i<elements.lengtd; i++) 
    {
        field_type = elements[i].type.toLowerCase();
        switch(field_type)
        {
            case "text":
            case "number":
            case "textarea":
            //case "hidden":
            //    elements[i].value = "";
            //    break;
            case "select-one":
            case "select-multi":
                elements[i].selectedIndex = -1;
                break;
            default:
                  break;
        }
    }
    //alert(window.location.href);
    //alert('fast_id=1');
    txt = window.location.href;
    res = txt.replace('fast_id=1', 'fast_id=');
    //alert(res);
    document.frmForm.action = res;
    //document.frmForm.reset();
    document.frmForm.hdnFor.value = "ADDNEW";
    document.frmForm.submit();
}


$ = function(id) {
  return document.getElementById(id);
}

var show = function(id) {
	$(id).style.display ='block';
}
var hide = function(id) {
	$(id).style.display ='none';
}
</script>
</head>

<body>
<div class="header">
    <h1><a href="./" class="header">&#127968; fastinvoice.ro</a>
        &nbsp;&nbsp;>&nbsp; &#128196; Adăugare item factură
</div>

    
<div class="container">
<div class="top_menu">    
    <a href="./">&#127968; Home</a>&nbsp;
     | &nbsp;
    <a href="./entityView.php?app=_system&table=seed_apps ">&#9881; Apps</a>&nbsp;
     | &nbsp;
    
     <a target="_blank" href="https://data-seed.tech/tutorial.php">&#128712; Help</a>
</div>
<br><br>





<div class="invoice">
<form name='frmForm' metdod='post'>
<table class="form_table">
<tr>
    <td style="font-weight:bold">Invoice fast_id 🔑 </td>
    <td><input type="number" name="fast_invoice_id" value="<?php print($fast_invoice_id) ?>" style="widtd:50px;text-align: right;" readonly class="read_only"> hidden</td>
</tr>
<tr>
    <td>fast_client id *</td>
    <td>
        <input type="number" name="fast_client_id" value="<?php print($fast_client_id) ?>" style="widtd:50px; text-align: right;"> hidden FastInvoice Client Id
    </td>
</tr>
<tr>
    <td>fast item id *</td>
    <td>
        <input type="number" name="fast_id_int1" value="2" style="width:50px;text-align: right;" readonly class="read_only"> hidden
    </td>
</tr>

<tr>
    <td style="text-align:center" colspan="2">
        <?php print($msg) ?>
    </td>
</tr>



<tr>
    <td>
        Nr. crt.
    </td>
    <td>
        <input type="text" name="ItemID" value="<?php print($ItemID); ?>" title="Numărul de ordine al itemului pe factură" maxlengtd="50" style="widtd:200px">
    </td>
</tr>
<tr>
    <td>
        Denumire item:
    </td>
    <td>
        <textarea name="ItemName" rows="2" cols="50"><?php print($ItemName); ?></textarea>
    </td>
</tr>

    
<tr>
    <td>Cantitate</td>
    <td><input type="number" name="InvoicedQuantity" value="<?php print($InvoicedQuantity); ?>" style="width:100px;text-align: right;"></td>
</tr>    
<tr>        
    <td>U.M.</td>
    <td>
        <select name="InvoicedQuantityUnitCode">
            <option value=""> --- </option>
            <option value="C62">Bucati</option>
            <option value="HUR" selected>Ore</option>
        </select>
        InvoicedQuantityUnitCode
    </td>
</tr>
<tr>
    <td>Preț unitar</td>
    <td><input type="number" name="PriceAmount_decimal14" value="PriceAmount" style="width:100px;text-align: right;">PriceAmount</td>
</tr>
<tr>
    <td>PriceAmountCurrencyID</td>
    <td>
        <select name="PriceAmountCurrencyID">
            <option value=""> --- </option>
            <option value="EUR">EUR</option>
            <option value="RON" selected>RON</option>
        </select>
        PriceAmountCurrencyID
    </td>
</tr>
<tr>
    <td>LineExtensionAmount</td>
    <td>
        <input type="number" name="LineExtensionAmount" value="100.00" style="width:100px;text-align: right;">
        LineExtensionAmount
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryID</td>
    <td>
        <select name="ClassifiedTaxCategoryID_varchar11">
            <option value=""> --- </option>
            <option value="E">Exempt from Tax</option>
            <option value="O">Services outside scope of tax</option>
            <option value="S" selected>Standard rate</option>
            <option value="Z">Zero rated goods</option>
        </select>
        ClassifiedTaxCategoryID
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryPercent</td>
    <td>
        <input type="number" name="ClassifiedTaxCategoryPercent" value="19.00" style="width:100px;text-align: right;">
        ClassifiedTaxCategoryPercent
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryTaxSchemeID</td>
    <td>
        <select name="ClassifiedTaxCategoryTaxSchemeID">
            <option value=""> --- </option>
            <option value="VAT" selected>VAT</option>
        </select>
        ClassifiedTaxCategoryTaxSchemeID
    </td>
</tr>
<tr>
    <td>TaxExemptionReasonCode</td>
    <td>
        <select name="TaxExemptionReasonCode"><option value=""> --- </option>
            <option value="" selected>None (N/A)</option>
            <option value="VATEX-EU-O">Not subject to VAT</option>
        </select>
        TaxExemptionReasonCode
    </td>
</tr>


</table>
        
        


    
    
    
    <br /><br />
    <input type="button" name="btnAddNew" onclick="AddNew()" VALUE=" Golește formularul  &#128396;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <input type="button" name="btnSave" class='button_default' onclick='Save()' value=' Salvează item  &#128190;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    
    <input type="button" name="btnInvoice" onclick="Invoice()" VALUE=" Înapoi la factură  &#128396;">
        
    <input type="hidden" name="hdnFor">
    <input type="hidden" name="fast_id">
</form>

<br/><br/><br/>
</div>

</div>


    


</body>
</html>