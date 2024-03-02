<?php
require_once("../connection.inc");
//require_once("../counter.inc");
//require_once("./constante.inc");
//require_once("./ubl_functions.inc");


/////////////////////////////////////
//                FOR              //
/////////////////////////////////////
$for =	@$_POST['hdnFor'];
//print($for);
$msg = '';


$fast_invoice_id    = @$_POST['fast_invoice_id'];
$fast_client_id     = @$_POST['fast_client_id'];
$fast_item_id       = @$_POST['fast_item_id'];

$ItemID                             = @$_POST['ItemID'];
$InvoicedQuantity                   = @$_POST['InvoicedQuantity'];
$InvoicedQuantityUnitCode           = @$_POST['InvoicedQuantityUnitCode'];
$ItemLineExtensionAmount            = @$_POST['ItemLineExtensionAmount'];
$ItemName                           = @$_POST['ItemName'];
$SellersItemIdentificationID        = @$_POST['SellersItemIdentificationID'];
$ItemClassificationCode             = @$_POST['ItemClassificationCode'];
$ItemClassificationCodeListID       = @$_POST['ItemClassificationCodeListID'];
$ClassifiedTaxCategoryID            = @$_POST['ClassifiedTaxCategoryID'];
//print($ClassifiedTaxCategoryID);
$ClassifiedTaxCategoryPercent       = @$_POST['ClassifiedTaxCategoryPercent'];
$ClassifiedTaxCategoryTaxSchemeID   = @$_POST['ClassifiedTaxCategoryTaxSchemeID'];
if($ClassifiedTaxCategoryTaxSchemeID == "")
{
    $ClassifiedTaxCategoryTaxSchemeID = "VAT";
}

$PriceAmount                        = @$_POST['PriceAmount'];
$PriceAmountCurrencyID              = @$_POST['PriceAmountCurrencyID'];
$PriceBaseQuantity                  = @$_POST['PriceBaseQuantity'];
if($PriceBaseQuantity == "")
{
    $PriceBaseQuantity = "null";
}
$PriceBaseQuantityUnitCode          = @$_POST['PriceBaseQuantityUnitCode'];
$TaxExemptionReasonCode             = @$_POST['TaxExemptionReasonCode'];





if($fast_invoice_id != "" || $fast_invoice_id != 0)
{
    $sql = "SELECT ID as InvoiceID, IssueDate, fast_supplierparty.PartyName as SupplierParty, fast_customerparty.PartyName as CustomerParty
            FROM fast_invoice
            INNER JOIN fast_supplierparty on fast_invoice.fast_supplierparty = fast_supplierparty.fast_id
            INNER JOIN fast_customerparty on fast_invoice.fast_customerparty = fast_customerparty.fast_id
            WHERE fast_invoice.fast_id = " . $fast_invoice_id . ";";
    $result = $conn -> query($sql);

    if ($row = $result ->fetch_object())
    {
        $InvoiceID              = $row->InvoiceID;
        $IssueDate              = $row->IssueDate;
        //$DueDate              = $row->DueDate;
        //$Note                 = $row->Note;
        //$DocumentCurrencyCode = $row->DocumentCurrencyCode;
        //$TaxAmount            = $row->TaxAmount;
        //$LineExtensionAmount  = $row->LineExtensionAmount;
        //$TaxExclusiveAmount   = $row->TaxExclusiveAmount;
        //$TaxInclusiveAmount   = $row->TaxInclusiveAmount;
        //$PayableAmount        = $row->PayableAmount;
        $SupplierParty          = $row->SupplierParty;
        $CustomerParty          = $row->CustomerParty;
    }
}





if($for == "EDIT")
{
    $sql = "SELECT * FROM fast_invoiceline WHERE fast_id =" . $fast_item_id . ";";
    //print($sql);
    $result = $conn -> query($sql);
    if ($row = $result ->fetch_object())
    {
        $ItemID                             = $row->ID;
        $InvoicedQuantity                   = $row->InvoicedQuantity;
        $InvoicedQuantityUnitCode           = $row->InvoicedQuantityUnitCode;
        $ItemLineExtensionAmount            = $row->LineExtensionAmount;
        $ItemName                           = $row->ItemName;
        $SellersItemIdentificationID        = $row->SellersItemIdentificationID;
        $ItemClassificationCode             = $row->ItemClassificationCode;
        $ItemClassificationCodeListID       = $row->ItemClassificationCodeListID;
        $ClassifiedTaxCategoryID            = $row->ClassifiedTaxCategoryID;
        $ClassifiedTaxCategoryPercent       = $row->ClassifiedTaxCategoryPercent;
        $ClassifiedTaxCategoryTaxSchemeID   = $row->ClassifiedTaxCategoryTaxSchemeID;
        $PriceAmount                        = $row->PriceAmount;
        $PriceAmountCurrencyID              = $row->PriceAmountCurrencyID;
        $PriceBaseQuantity                  = $row->PriceBaseQuantity;
        $PriceBaseQuantityUnitCode          = $row->PriceBaseQuantityUnitCode;
        $TaxExemptionReasonCode             = $row->TaxExemptionReasonCode;
    }
}


if($for == "SAVE")
{
    //print("SAVE");
    if($fast_item_id == "" || $fast_item_id == 0)
    {
        //print("INSERT");
        //////////////////////////////////////////////////////////////////////////////////////////
        //                                  INSERT                                              //
        //////////////////////////////////////////////////////////////////////////////////////////
        $sql = "INSERT INTO fast_invoiceline VALUES (default, ".$fast_invoice_id.", '".$ItemID."', ".$InvoicedQuantity.", '".$InvoicedQuantityUnitCode."', ".$ItemLineExtensionAmount.", '".$ItemName."', "
                . "'".$SellersItemIdentificationID."', '".$ItemClassificationCode."', '".$ItemClassificationCodeListID."', '".$ClassifiedTaxCategoryID."', "
                .$ClassifiedTaxCategoryPercent .", '".$ClassifiedTaxCategoryTaxSchemeID."', ".$PriceAmount.", '".$PriceAmountCurrencyID."', ".$PriceBaseQuantity.", '".$PriceBaseQuantityUnitCode."', '"
                .$TaxExemptionReasonCode."');";

        //print($sql);
        $result = $conn -> query($sql);
        $ItemID = $conn->insert_id;
        //print($fast_invoice_id);
        
        $msg = "INSERT OK";
    }
    
    
    
    $sql = "UPDATE fast_invoice INV
            INNER JOIN 
            (
                    SELECT LINE.fast_invoice_id, SUM((LINE.ClassifiedTaxCategoryPercent/100) * LINE.InvoicedQuantity * LINE.PriceAmount) as TaxAmount,
                            SUM(LINE.LineExtensionAmount) as LineExtensionAmount, 
                    SUM(LINE.InvoicedQuantity*LINE.PriceAmount) as TaxExclusiveAmount, 
                    SUM(LINE.InvoicedQuantity*(LINE.PriceAmount + (LINE.ClassifiedTaxCategoryPercent/100)*LINE.PriceAmount)) as TaxInclusiveAmount,
                    SUM(LINE.InvoicedQuantity*(LINE.PriceAmount + (LINE.ClassifiedTaxCategoryPercent/100)*LINE.PriceAmount)) as PayableAmount 
                    FROM fast_invoiceline as LINE
                    WHERE LINE.fast_invoice_id = ".$fast_invoice_id."
            ) LIN
            ON INV.fast_id = LIN.fast_invoice_id
            SET INV.TaxAmount = LIN.TaxAmount,
                    INV.LineExtensionAmount = LIN.LineExtensionAmount,
                INV.TaxExclusiveAmount = LIN.TaxExclusiveAmount,
                INV.TaxInclusiveAmount = LIN.TaxInclusiveAmount,
                INV.PayableAmount = LIN.PayableAmount
            WHERE INV.fast_id = LIN.fast_invoice_id;";

            //print($sql);
            $result = $conn -> query($sql);
            //$ItemID = $conn->insert_id;
            //print($fast_invoice_id);

            $msg = "INVOICE UPDATED";
}           
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
<script type="text/javascript" src="./scripturi.js"></script>
<script language=javascript>
function Save()
{
    
    if(document.frmForm.ItemID.value === "")
    //if(document.getElementsByName("ID").value === undefined)
    {
        alert("EROARE! Completați numărul curent!");
        document.frmForm.ItemID.focus();
        return;
    }
    if(document.frmForm.InvoicedQuantity.value === "")
    {
        alert("EROARE! Completați cantitatea!");
        document.frmForm.InvoicedQuantity.focus();
        return;
    }
    if(document.frmForm.InvoicedQuantityUnitCode.value === "")
    {
        alert("EROARE! Completați unitatea de măsură!");
        document.frmForm.InvoicedQuantityUnitCode.focus();
        return;
    }
    if(document.frmForm.ItemLineExtensionAmount.value === "")
    {
        alert("EROARE! Completați valoarea fără TVA!");
        document.frmForm.ItemLineExtensionAmount.focus();
        return;
    }
    if(document.frmForm.ItemName.value === "")
    {
        alert("EROARE! Completați denumirea!");
        document.frmForm.ItemName.focus();
        return;
    }
    if(document.frmForm.ClassifiedTaxCategoryTaxSchemeID.value === "")
    {
        alert("EROARE! Selectați TVA!");
        document.frmForm.ClassifiedTaxCategoryTaxSchemeID.focus();
        return;
    }
    if(document.frmForm.PriceAmount.value === "")
    {
        alert("EROARE! Completați prețul!");
        document.frmForm.PriceAmount.focus();
        return;
    }
    if(document.frmForm.PriceAmountCurrencyID.value === "")
    {
        alert("EROARE! Selectați valuta!");
        document.frmForm.PriceAmountCurrencyID.focus();
        return;
    }
    if(document.frmForm.TaxExemptionReasonCode.value === "")
    {
        alert("EROARE! Selectați motivul exceptării!");
        document.frmForm.TaxExemptionReasonCode.focus();
        return;
    }
    
    document.frmForm.method = "post";
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
    //txt = window.location.href;
    //res = txt.replace('fast_id=1', 'fast_id=');
    //alert(res);
    //document.frmForm.action = res;
    //document.frmForm.reset();
    document.frmForm.hdnFor.value = "ADDNEW";
    document.frmForm.submit();
}

function invoice()
{
    document.frmForm.fast_invoice_id.value = '<?php print($fast_invoice_id) ?>';
    document.frmForm.method = "post";
    document.frmForm.action = "invoice.php";
    document.frmForm.hdnFor.value = "EDIT";
    document.frmForm.submit();
}




</script>
</head>

<body onload="start(<?php print($visitor_id); ?>)">
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
    
     <a target="_blank" href="#">&#128712; Help</a>
</div>
<br><br>





<div class="invoice">
<form name='frmForm' metdod='post'>
<table class="form_table">
<tr>
    <td style="text-align:center;background-color: #e6f2ff" colspan="2">
        <h1 style="font-size:16px">
            FACTURA NR. &nbsp;
            <input type="text" id="ID" name="ID" value="<?php print($InvoiceID) ?>" placeholder="Nr. fact." maxlength="100" style="width:80px;font-weight:bold;font-size:16px" title="Numărul facturii în evidențele dumneavoastră - acesta este de obicei transmis clientului ca Număr factură (ANAF: BT-1)">
            &nbsp;&nbsp;
            din data <input type="date" name="IssueDate" value="<?php print($IssueDate) ?>" title="Data emiterii facturii (ANAF: BT-2)" style="font-size:16px">
        </h1>
        <br/>
    </td>
</tr>
<tr>
    <td style="text-align:center" colspan="2">
        <?php print($msg) ?>
    </td>
</tr>



<tr>
    <td style="text-align:center" colspan="2">
        <table style="width: 100%">
            <tr>
                <td style="text-align:center;background-color: #e6f2ff">
                    <h3>FURNIZOR: &nbsp; <?php print($SupplierParty); ?></h3>
                </td>
                <td>&nbsp;</td>
                <td style="text-align:center;background-color: #e6f2ff">
                    <h3>CLIENT: &nbsp; <?php print($CustomerParty); ?></h3>
                </td>
            </tr>
        </table>
    </td>
</tr>




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
        <input type="number" name="fast_item_id" value="<?php print($fast_item_id) ?>" style="width:50px;text-align: right;" readonly class="read_only"> hidden
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
    <td>Cantitate / U.M.</td>
    <td>
        <input type="number" name="InvoicedQuantity" value="<?php print($InvoicedQuantity); ?>" style="width:100px;text-align: right;">
        <select name="InvoicedQuantityUnitCode">
            <option value=""> --- </option>
            <option value="C62">Bucati</option>
            <option value="HUR" selected>Ore</option>
        </select>
    </td>
</tr>
<tr>
    <td>Preț unitar</td>
    <td>
        <input type="number" name="PriceAmount" value="<?php print($PriceAmount); ?>" style="width:100px;text-align: right;">
        <select name="PriceAmountCurrencyID">
            <option value=""> --- </option>
            <option value="EUR">EUR</option>
            <option value="RON" selected>RON</option>
        </select>
    </td>
</tr>

<tr>
    <td>LineExtensionAmount</td>
    <td>
        <input type="number" name="ItemLineExtensionAmount" value="<?php print($ItemLineExtensionAmount); ?>" style="width:100px;text-align: right;">
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryID</td>
    <td>
        <select name="ClassifiedTaxCategoryID">
            <option value=""> --- </option>
            <option value="E">Exempt from Tax</option>
            <option value="O">Services outside scope of tax</option>
            <option value="S" selected>Standard rate</option>
            <option value="Z">Zero rated goods</option>
        </select>
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryPercent</td>
    <td>
        <input type="number" name="ClassifiedTaxCategoryPercent" value="<?php print($ClassifiedTaxCategoryPercent); ?>" style="width:100px;text-align: right;">
    </td>
</tr>
<tr>
    <td>ClassifiedTaxCategoryTaxSchemeID</td>
    <td>
        <select name="ClassifiedTaxCategoryTaxSchemeID">
            <option value=""> --- </option>
            <option value="VAT" selected>VAT</option>
        </select>
    </td>
</tr>
<tr>
    <td>TaxExemptionReasonCode</td>
    <td>
        <select name="TaxExemptionReasonCode">
            <option value="" selected>None (N/A)</option>
            <option value="VATEX-EU-O">Not subject to VAT</option>
        </select>
        <br /><br />
    </td>
</tr>


</table>
        
        


    
    
    
    <br /><br />
    <input type="button" name="btnAddNew" onclick="AddNew()" VALUE=" Golește formularul  &#128396;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <input type="button" name="btnSave" class='button_default' onclick='Save()' value=' Salvează item  &#128190;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    
    <input type="button" name="btnInvoice" onclick="invoice()" VALUE=" Înapoi la factură  &#128396;">
        
    <input type="hidden" name="hdnFor">
</form>

<br/><br/><br/>
<?php require_once("footer.inc"); ?>
</div>

</div>


    


</body>
</html>