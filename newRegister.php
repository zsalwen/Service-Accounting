<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Register                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 11, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();

$start = '2008-08-26';
$end = '2008-09-30';
// 
//
// 
//
// Start All Withdraws 
//
// 
//
// 
//
$balance='';
$r=@mysql_query("select * from ac_register where accountID = '5' and trans = 'WITHDRAW' and entered >= '$start' and entered <= '$end' order by checkNumber ");
?>
<style>td { white-space:pre; border-bottom: solid 1px; }</style>
<div style="font-size:36px; padding-left:200px;"><br />
<br />
<br />
<br />

<strong>Accounting Reports</strong><br />From <?=$start?> to <?=$end?><br />By: ______________________<br />$_______.__:  Oustanding<br />$_______.__:  Cleared From Last Month<br />$_______.__:  Paid Out<br />$_______.__:  Paid In</div>
<div style="font-size:18px; page-break-before:always">Withdraws from <?=$start?> to <?=$end?></div> 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>Autherized</td>
    	<td>Detail</td>
    	<td>Date</td>
    	<td>Amount</td>
	</tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><input type="checkbox" />#<?=$d['checkNumber']?> by <?=id2name($d['userID'])?></td>
    	<td><?=$d['detail']?></td>
    	<td><?=$d['entered']?></td>
    	<td><? 
		if($d['trans'] == "WITHDRAW"){
			echo "-";
			$balance = $balance - $d['amount'];
		}else{
			$balance = $balance + $d['amount'];
		}	?>$<?=number_format($d['amount'],2)?></td>
	</tr>
<? } ?> 
	<tr>   
    	<td colspan="11" align="right">Statement balance is $<?=number_format($balance,2)?></td>
        </tr>
	<tr>   
    	<td colspan="11" align="right">Account balance is <?=accountBalance('5');?></td>
        </tr>
</table>  
<div style="font-size:18px; page-break-before:always">Deposits from <?=$start?> to <?=$end?></div> 
<?
// 
//
// 
//
// Start All deposits
// 
//
// 
//
$balance='';
$r=@mysql_query("select * from ac_register where accountID = '5' and trans = 'DEPOSIT' and entered >= '$start' and entered <= '$end' order by entered, checkNumber ");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>Code</td>
    	<td>Autherized</td>
    	<td>Detail</td>
    	<td>Date</td>
    	<td>amount</td>
	</tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><input type="checkbox" /><?=codeName($d['codeID']);?></td>
    	<td><?=id2name($d['userID'])?></td>
    	<td><?=$d['detail']?></td>
    	<td><?=$d['entered']?></td>
    	<td><? 
		if($d['trans'] == "WITHDRAW"){
			echo "-";
			$balance = $balance - $d['amount'];
		}else{
			$balance = $balance + $d['amount'];
		}	?>$<?=number_format($d['amount'],2)?></td>
	</tr>
<? } ?> 
	<tr>   
    	<td colspan="11" align="right">Statement balance is $<?=number_format($balance,2)?></td>
        </tr>
	<tr>   
    	<td colspan="11" align="right">Account balance is <?=accountBalance('5');?></td>
        </tr>
</table>   
     
     
  <div style="font-size:18px; page-break-before:always">Voids from <?=$start?> to <?=$end?></div> 
   
     
<?
// 
//
// 
//
// Start All voids
//
// 
//
// 
//
$balance='';
$r=@mysql_query("select * from ac_register where accountID = '5' and trans = 'VOID' and entered >= '$start' and entered <= '$end' order by entered, checkNumber ");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td>Check</td>
    	<td>Autherized</td>
    	<td>Detail</td>
    	<td>Date</td>
    	<td>Amount</td>
	</tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><input type="checkbox" /><?=$d['checkNumber']?></td>
    	<td><?=id2name($d['userID'])?></td>
    	<td><?=$d['detail']?></td>
    	<td><?=$d['entered']?></td>
    	<td><? 
		if($d['trans'] == "WITHDRAW"){
			echo "-";
			$balance = $balance - $d['amount'];
		}else{
			$balance = $balance + $d['amount'];
		}	?>$<?=number_format($d['amount'],2)?></td>
	</tr>
<? } ?> 
	<tr>   
    	<td colspan="11" align="right">Statement balance is $<?=number_format($balance,2)?></td>
        </tr>
	<tr>   
    	<td colspan="11" align="right">Account balance is <?=accountBalance('5');?></td>
        </tr>
</table>   
     