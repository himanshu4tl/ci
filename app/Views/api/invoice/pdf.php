<table border="1px" cellspacing="0" cellpadding="4">
    <tr>
        <th colspan="4">
            <span style="text-align: center;">
                <h1 >DHAN VIHAR RESIDENCY</h1>
                <h3>DHAN VIHAR COMMERCIAL & HOUSING CO. OP. SOC. LTD</h3><br/>
                REGD NO.GH 21586 DATED 17.05.2006
            </span>
        </th>
    </tr>
    <tr>
        <th colspan="4" align="center">TP 22, OPP. Dev Nandan Sky,Chandkheda,Ahmedabad - 382424</th>
    </tr>
    <tr>
       <th colspan="4">Date : <?= $invoice['paid_at']?date(DATE_FORMAT,$invoice['paid_at']):'';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       Flat/Shop No : <?= @$home['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       Receipt N0 : <?= $invoice['id'];?>
       </th>
    </tr>
    <tr>
        <th align="left" colspan="1" width="50px">N0</th>
        <th align="center" colspan="3">PARTICULARS</th>
        <th align="right" colspan="1">AMOUNT (₹)</th>
    </tr>
    <tr>
        <th colspan="1">1</th>
        <th colspan="3"><?= $invoice['title'];?><?php if(!$invoice['type']){?> MAINTAINANCE MONTHS : <?php echo $invoice['months'];}?></th>
        <th colspan="1" align="right"><?= $invoice['amount'];?></th>
    </tr>
    <?php 
    $i=1;
    if($invoice['due_charge']){ $i++;?>
    <tr>
        <th colspan="1"><?= $i;?></th>
        <th colspan="3">Penalty</th>
        <th colspan="1" align="right"><?= $invoice['due_charge'];?></th>
    </tr>
    <?php }?>
    <?php if($invoice['discount_amount']){ $i++;?>
    <tr>
        <th colspan="1"><?= $i;?></th>
        <th colspan="3">Discount</th>
        <th colspan="1" align="right"><?= $invoice['discount_amount'];?></th>
    </tr>
    <?php }?> 
    <tr>
        <th colspan="4" align="right">TOTAL</th>
        <th colspan="1" align="right"><?= ($invoice['amount']+$invoice['due_charge'])-$invoice['discount_amount'];?></th>
    </tr>
    
  </table>
  <p align="right">For,CHAIRMAN/SECRETARY</p>
  <hr>
  <p>This is system generated receipt, hence doesn't require signature.</p>