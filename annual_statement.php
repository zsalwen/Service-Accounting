<?php
/*include 'common/security.php';*/
include 'functions.php';
echo dbConnect();
mysql_select_db('intranet');
$year = $_GET[year]; 
$date = $year;
$client = $_GET[client];

// ok we need to do a little maintance

$qm1 = "SELECT * FROM schedule_items";
$rm1 = @mysql_query($qm1) or die(mysql_error());
while($dm1 = mysql_fetch_array($rm1, MYSQL_ASSOC)){
	
	$replace = str_replace('  ',' ',$dm1[address1]);
	$splat = explode(' ',$replace);
	$address2 = trim(addslashes($splat[1]));
	
	$qm2 = "UPDATE schedule_items SET address2='$address2' WHERE schedule_id = '$dm1[schedule_id]'";
	//$rm2 = @mysql_query($qm2) or die($qm2."<br>".mysql_error());



}




// end

$q1 = "SELECT * FROM schedule_items  WHERE attorneys_id = '$client' AND sale_date like '$date%' AND ad_deposit < '1' ORDER BY address2";		
$r1 = @mysql_query ($q1) or die(mysql_error());
setLocation("Working on Statement for $date");
$_SESSION[lines] = 0;
$_SESSION[auction_fee] = 0;
$_SESSION[ad_cost] = 0;
$_SESSION[total] = 0;
$_SESSION[paid] = 0;
$_SESSION[adjusted] = 0;
$_SESSION[due] = 0;
$_SESSION[dwest] = 0;
$_SESSION[dburson] = 0;
?>
<title>Annual Statement</title>
<SCRIPT SRC="javascript/common.js" language="JavaScript1.2"></script>
<body onLoad="Maximize()">
<style type="text/css">
body{margin-left:0px;margin-top:0px;margin-right:0px;
font-family:Arial; 
font:Arial;
}
a:hover{cursor:pointer;}
.NEG{background-color:#FF9966}
.POS{ background-color:#66FFCC}
.EVEN{ background-color:#FFFFFF}
#lineitem{ background-color:#CCCCCC}
#lineitem:hover{ background-color:#FF0000}
tr{font-size:12px;}
tr:hover {font-weight:bold; cursor:crosshair; font-size:12px;}
//-->
</style>
<script language="JavaScript" type="text/javascript" src="../common/wysiwyg-state.js"></script>
<? $mouseover = "onmouseover=\"style.backgroundColor='#FFFF00';\" onmouseout=\"style.backgroundColor='#FFFFFF'\"";?>
<? if ($year && $client){?>





<table align="center" border="1" style="border-collapse:collapse" cellpadding="2" bgcolor="#FFFFFF">
  <tr>
    <td></td>
    <td>Court</td>
    <td>Date</td>
    <td>Time</td>
    <td>File</td>
    <td>Address</td>
    <td align="center" bgcolor="#FFFF99">Auction Fee</td>
    <td align="center" bgcolor="#FFFF99">Ad Fee</td>
    <td align="center" bgcolor="#FFFF99">Total Due</td>
    <td align="center" bgcolor="#FFFF99">Paid</td>
    <td align="center" bgcolor="#FFFF99">Adjusted</td>
    <td align="center" bgcolor="#FFFF99">Ballance Due</td>
    <td>Check1</td>
    <td>Check2</td>
    <td>Check3</td>
  </tr>
  <? while ($data = mysql_fetch_array($r1, MYSQL_ASSOC)){ ?>
  <?
// format the date
$date = explode('-',$data[sale_date]);
$date = $date[1].'/'.$date[2];
?>
  <tr <?=$mouseover?>>
    <td><a name="<?=$data[schedule_id]?>"/><a target="_blank" href="http://mdwestserve.com/CORE/pay.php?id=<?=$data[schedule_id]?>">PAY</a></td>
    <td><?=$data[court]?></td>
    <td><?=$date?></td>
    <td><?=$data[sale_time]?></td>
    <td><? echo str_replace('FGLSN','',str_replace('WHITE','',str_replace('BUR','',$data['file'])))?></td>
    <td style="white-space:pre"><?=$data[address1]?></td>
    <td align="right"><?=number_format($data[auction_fee],2)?></td>
    <? $cost6 = $data[ad_cost] + $data[ad_cost2] + $data[ad_cost3]; ?>
	<td align="right"><?=number_format($cost6,2)?></td>
    <?
		$total = $data[auction_fee] + $cost6;
		?>
    <td align="right"><? echo number_format($total,2); ?></td>
    <td align="right"><? 
		
		$paid4 = $data[paid] + $data[paid2] + $data[paid3];
		echo number_format($paid4,2);
		//echo number_format($data[paid],2);
		?></td>
    <td align="right"><?=number_format($data[adjusted],2)?></td>
    <? 
		
//		$due = ($data[auction_fee] + $data[ad_cost]) - $data[paid] -$data[adjusted];
		$due = ($data[auction_fee] + $cost6) - $paid4 -$data[adjusted];
		if ($due > 0 ){ $class = 'NEG'; $_SESSION[dwest] = $_SESSION[dwest] + $due;}
		if ($due < 0 ){ $class = 'POS';$due = $due * -1;  $_SESSION[dburson] = $_SESSION[dburson] + $due;}
		if ($due == 0 ){ $class = 'EVEN';}
		?>
    <td align="right" class="<?=$class;?>"><? echo number_format($due,2) ?></td>
    <? 
	$_SESSION[lines] = $_SESSION[lines] + 1;
	$_SESSION[auction_fee] = $_SESSION[auction_fee] + $data[auction_fee];
	$_SESSION[ad_cost] = $_SESSION[ad_cost] + $cost6;
	$_SESSION[total] = $_SESSION[total] + $total;
//	$_SESSION[paid] = $_SESSION[paid] + $data[paid];
	$_SESSION[paid] = $_SESSION[paid] + $paid4;
	$_SESSION[adjusted] = $_SESSION[adjusted] + $data[adjusted];
	$_SESSION[due] = $_SESSION[due] + $due;
	?>
    <td><?=$data[check_number]?></td>
    <td><?=$data[check_number2]?></td>
    <td><?=$data[check_number3]?></td>
  </tr>
  <?  } ?>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="center" bgcolor="#FFFF99">auction_fee</td>
    <td align="center" bgcolor="#FFFF99">ad_cost</td>
    <td align="center" bgcolor="#FFFF99">auction_fee +<br>
      ad_cost</td>
    <td align="center" bgcolor="#FFFF99">paid</td>
    <td align="center" bgcolor="#FFFF99">adjusted</td>
    <?
		$end_of_month = $_SESSION[paid] + $_SESSION[adjusted] - ($_SESSION[auction_fee] + $_SESSION[ad_cost]);
		if ($end_of_month > 0 ){ $class2 = 'NEG';}
		if ($end_of_month < 0 ){ $class2 = 'POS'; $end_of_month = $end_of_month *-1;}
		if ($end_of_month == 0 ){ $class2 = 'EVEN';}

		?>
    <td>Due Client</td>
    <td>Due West</td>
    <td>---</td>
    <td>---</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="center" bgcolor="#FFFF99"> Total of
      <?=$_SESSION[lines]?>
      Lines:</td>
    <td align="center" bgcolor="#FFFF99">$
        <?=number_format($_SESSION[auction_fee],2)?></td>
    <td align="center" bgcolor="#FFFF99">$
        <?=number_format($_SESSION[ad_cost],2)?></td>
    <td align="center" bgcolor="#FFFF99">$
        <?=number_format($_SESSION[auction_fee]+$_SESSION[ad_cost] ,2)?></td>
    <td align="center" bgcolor="#FFFF99">$
        <?=number_format($_SESSION[paid],2)?></td>
    <td align="center" bgcolor="#FFFF99">$
        <?=number_format($_SESSION[adjusted],2)?></td>
    <?
		$end_of_month = $_SESSION[paid] + $_SESSION[adjusted] - ($_SESSION[auction_fee] + $_SESSION[ad_cost]);
		if ($end_of_month > 0 ){ $class2 = 'NEG';}
		if ($end_of_month < 0 ){ $class2 = 'POS'; $end_of_month = $end_of_month *-1;}
		if ($end_of_month == 0 ){ $class2 = 'EVEN';}

		?>
    <td>$<?=number_format($_SESSION[dburson],2)?></td>
    <td>$<?=number_format($_SESSION[dwest],2)?></td>
    <td>---</td>
    <td>---</td>
  </tr>
</table>







<? } else {?>
<br>
<br>
<br>
<br>
<form method="get">
<input type="hidden" name="page" value="annual_statement"> 
<table align="center" border="1" bgcolor="#FFFFFF">
	<tr>
		<td>Client</td>
		<td align="left" valign="middle"><select name="client">
				<?
		$q8 = "SELECT * FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	echo "<option value='$data8[attorneys_id]'>$data8[display_name]</option>";
		}
		?>
</select></td>
	</tr>
	<tr>
		<td>Statement Date</td><td><select name="year">
			<option><?=$_GET[year]?></option>
				<option>2007</option>
				<option>2008</option>
				<option>2009</option>
				<option>2010</option>
			</select></td>
	</tr>
		<td colspan="2" align="center"><input type="submit" value="Display These Files" /></td>
	</tr>
</table>
</form>
<? }?>