<?php
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Auction Statement        							                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 8, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
//error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();

include 'menu.php';
$month = $_GET[month];
$year = $_GET[year]; 
$court = $_GET[court];
$date = $year."-".$month;
$client = $_GET[client];
$q1 = "SELECT * FROM schedule_items  WHERE attorneys_id = '$client' AND sale_date like '$date%' AND court like '$court%' AND ad_deposit < '1' ORDER BY court, file";		
$r1 = @mysql_query ($q1) or die(mysql_error());
//setLocation("Working on Statement for $date");
$_SESSION[lines] = 0;
$_SESSION[auction_fee] = 0;
$_SESSION[ad_cost] = 0;
$_SESSION[total] = 0;
$_SESSION[paid] = 0;
$_SESSION[adjusted] = 0;
$_SESSION[due] = 0;
$_SESSION[dwest] = 0;
$_SESSION[dburson] = 0;

$_SESSION[out] = 0;


$template = "<div style='text-align:left; padding:50px; font-size:14'>The payments made to date have been entered. The following files still have an outstanding balance.<br>";
// start functions
function checks($number,$date){
		$count = 0;
		$amount = 0;
		$q1 = "SELECT * FROM schedule_items WHERE check_number = '$number' AND sale_date like '$date%' AND ad_deposit < '1'";		
		$r1 = @mysql_query ($q1) or die(mysql_error());
		while ($data1 = mysql_fetch_array($r1, MYSQL_ASSOC)){ 
			$count++;
			$amount = $amount + $data1[paid];
		}
		$q2 = "SELECT * FROM schedule_items WHERE check_number2 = '$number' AND sale_date like '$date%' AND ad_deposit < '1'";		
		$r2 = @mysql_query ($q2) or die(mysql_error());
		while ($data2 = mysql_fetch_array($r2, MYSQL_ASSOC)){ 
			$count++;
			$amount = $amount + $data2[paid2];
		}
		$q3 = "SELECT * FROM schedule_items WHERE check_number3 = '$number' AND sale_date like '$date%' AND ad_deposit < '1'";		
		$r3 = @mysql_query ($q3) or die(mysql_error());
		while ($data3 = mysql_fetch_array($r3, MYSQL_ASSOC)){ 
			$count++;
			$amount = $amount + $data3[paid3];
		}
if ($number > '0'){
?>

	<tr <?=$mouseover?>>
		<td><?=$number?></td>
		<td align="right">$<?=number_format($amount,2)?></td>
		<td><?=$count?></td>
	</tr>
<?
}// end only display if $$
}


// end functions
?>
<title>Monthly Statement</title>
<SCRIPT SRC="javascript/common.js" language="JavaScript1.2"></script>
<body onLoad="Maximize()">
<script language="JavaScript" type="text/javascript" src="wysiwyg-state.js"></script>
<? $mouseover = "onmouseover=\"style.backgroundColor='#FFFF00';\" onmouseout=\"style.backgroundColor='#ffffff'\"";?>
<? if ($month && $year && $client){?>
<table align="center" border="1" cellpadding="0px" cellspacing="0px">
	<tr>
    	<td align="center">
        <form method="post" action="as_email_statement.php" target="_blank">
        	<table>
            	<tr>
                	<td>
                    	<input name="month" type="hidden" value="<?=$month?>">
                        <input name="year" type="hidden" value="<?=$year?>">
                        <input name="client" type="hidden" value="<?=$client?>">
                        <textarea id="note" cols="40" rows="5" name="notes">Enter Notes Here</textarea>
						<script language="JavaScript">generate_wysiwyg('note');</script>
                        <input type="submit" name="send" value="Send Statement to Client">
                    </td>
                </tr>
            </table>
        </form>
<table align="center" width="100%" border="1" style="border-collapse:collapse" cellpadding="2">
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td>Court</td>
    <td>Date</td>
    <td>Time</td>
    <td>File</td>
    <td>Address</td>
    <td align="center" bgcolor="#FFFF99">Auction Fee</td>
    <td align="center" bgcolor="#FFFF99">Waived</td>
    <td align="center" bgcolor="#FFFF99">Ad Fee</td>
    <td align="center" bgcolor="#FFFF99">Total Due</td>
    <td align="center" bgcolor="#FFFF99">Paid</td>
    <td align="center" bgcolor="#FFFF99">Adjusted</td>
    <td align="center" bgcolor="#FFFF99">Balance Due</td>
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
    <td style="white-space:pre"><a name="<?=$data[schedule_id]?>"/><a target="_blank" href="as_pay.php?id=<?=$data[schedule_id]?>">PAY <?=$data[schedule_id]?></a></td>
    <td style="white-space:pre"><a name="<?=$data[schedule_id]?>"/><a target="_blank" href="as_edit.php?id=<?=$data[schedule_id]?>">EDIT</a></td>
    <td style="white-space:pre"><a name="<?=$data[schedule_id]?>"/><a target="_blank" href="as_details.php?id=<?=$data[schedule_id]?>">DETALS</a></td>
    <td style="white-space:pre"><?=$data[court]?></td>
    <td style="white-space:pre"><?=$date?></td>
    <td style="white-space:pre"><?=$data[sale_time]?></td>
    <td style="white-space:pre"><? echo str_replace('FGLSN','',str_replace('WHITE','',str_replace('BUR','',$data['file'])))?></td>
    <td style="white-space:pre"><?=$data[address1]?></td>
    <td style="white-space:pre" align="right"><?=number_format($data[auction_fee],2)?></td>
    <td style="white-space:pre" align="right" <? if($data[adjusted] > 0){ echo "bgcolor='00FF00'";} ?>><?=number_format($data[adjusted],2)?></td>
    <? $cost6 = $data[ad_cost] + $data[ad_cost2] + $data[ad_cost3]; ?>
	<td style="white-space:pre" align="right"><?=number_format($cost6,2)?></td>
    <?
		$total = $data[auction_fee] + $cost6;
		?>
    <td style="white-space:pre" align="right"><? echo number_format($total,2); ?></td>
    <td style="white-space:pre" align="right"><? 
		
		$paid4 = $data[paid] + $data[paid2] + $data[paid3];
		echo number_format($paid4,2);
		//echo number_format($data[paid],2);
		?></td>
    <td align="right"><?=number_format($data[adjusted],2)?></td>
    <? 
		
//		$due = ($data[auction_fee] + $data[ad_cost]) - $data[paid] -$data[adjusted];
		$due = ($data[auction_fee] + $cost6) - $paid4 -$data[adjusted];
		if ($due > 0 ){ $class = 'NEG'; $_SESSION[dwest] = $_SESSION[dwest] + $due;
		
		if(number_format($due,2) != "0.00"){
		
		$template .= "File $data[file] for $data[address1] on $data[sale_date] at $data[sale_time] has a balance of $".number_format($due,2)."<br>";
		
		$_SESSION[out] = $_SESSION[out] + $due;
		
		
		}
		
		
		}
		if ($due < 0 ){ $class = 'POS';$due = $due * -1;  $_SESSION[dburson] = $_SESSION[dburson] + $due;}
		if ($due == 0 ){ $class = 'EVEN';}
		?>
    <td style="white-space:pre" align="right" class="<?=$class;?>"><? echo number_format($due,2) ?></td>
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
    <td style="white-space:pre"><?=$data[check_number]?> <?=$data[check_date]?></td>
    <td style="white-space:pre"><?=$data[check_number2]?> <?=$data[check_date2]?></td>
    <td style="white-space:pre"><?=$data[check_number3]?> <?=$data[check_date3]?></td>
  </tr>
  <?  } ?>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="center" bgcolor="#FFFF99"></td>
    <td align="center" bgcolor="#FFFF99">Our charges for <?=$_SESSION[lines]?> auctions.</td>
    <td align="center" bgcolor="#FFFF99">Ad Cost</td>
    <td align="center" bgcolor="#FFFF99">Total</td>
    <td align="center" bgcolor="#FFFF99">Paid</td>
    <?
		$end_of_month = $_SESSION[paid] + $_SESSION[adjusted] - ($_SESSION[auction_fee] + $_SESSION[ad_cost]);
		if ($end_of_month > 0 ){ $class2 = 'NEG';}
		if ($end_of_month < 0 ){ $class2 = 'POS'; $end_of_month = $end_of_month *-1;}
		if ($end_of_month == 0 ){ $class2 = 'EVEN';}

		?>
    <td>Due&nbsp;Client</td>
    <td>Due&nbsp;West</td>
    <td>---</td>
    <td>---</td>
    <td>---</td>
    <td>---</td>
    <td>---</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="center" bgcolor="#FFFF99">$<?=number_format($_SESSION[auction_fee],2)?> of which <?=number_format($_SESSION[adjusted],2)?> waived</td>
    <td align="center" bgcolor="#FFFF99">$<?=number_format($_SESSION[ad_cost],2)?></td>
    <td align="center" bgcolor="#FFFF99">$<?=number_format($_SESSION[auction_fee]+$_SESSION[ad_cost]-$_SESSION[adjusted] ,2)?></td>
    <td align="center" bgcolor="#FFFF99">$<?=number_format($_SESSION[paid],2)?></td>
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
    <td>---</td>
    <td>---</td>
  </tr>
</table>


<?=$template;?>Total Due: <?=number_format($_SESSION[out],2)?></div>




 <? } else {?>
 <form method="get"> 
<table align="center" border="1">

	<tr>
		<td>Client</td>
		<td align="left" valign="middle"><select name="client">
				<?
				mysql_select_db ('ccdb');

		$q8 = "SELECT * FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	echo "<option value='$data8[attorneys_id]'>$data8[display_name]</option>";
		}
		?>
</select></td>
	</tr>
	<tr>
		<td>Statement Date</td><td><select name="month"><option><?=$_GET[month]?></option><option>01</option>
				<option>02</option>
				<option>03</option>
				<option>04</option>
				<option>05</option>
				<option>06</option>
				<option>07</option>
				<option>08</option>
				<option>09</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
			</select><select name="year">
			<option><?=$_GET[year]?></option>
				<option>2007</option>
				<option>2008</option>
				<option>2009</option>
			</select></td>
	</tr>
		<td colspan="2" align="center"><input type="submit" value="Display These Files" /></td>
	</tr>

</table>
</form>
<? }?>