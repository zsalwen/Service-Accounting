<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Review                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: Aug 19, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();
session_start(); 
$_SESSION['code196']='';
$_SESSION['code410']='';
$_SESSION['code420']='';
$_SESSION['code430']='';
$_SESSION['code440']='';
$_SESSION['code520']='';
$_SESSION['code561']='';
$_SESSION['code690']='';
$_SESSION['codetotal']='';
function codeTransaction($date,$check,$payee,$code,$amount){
	$line = "<tr><td>$date</td><td>$check</td><td>$payee</td>";
	$r=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		if ($code == $d['codeID']){
			$_SESSION["code$d[codeID]"] = $_SESSION["code$d[codeID]"] + $amount;
			$line .= "<td align='right'>$".number_format($amount, 2)."</td>";
		}else{
			$line .= "<td></td>";
		}
	}
		$_SESSION["codetotal"] = $_SESSION["codetotal"] + $amount;
		$line .= "<td align='right'>$".number_format($amount, 2)."</td></tr>";
	return $line;
}
function codeTransaction2($date,$payee,$code,$amount){
	$line = "<tr><td>$date</td><td>$payee</td>";
	$r=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		if ($code == $d['codeID']){
			$_SESSION["code$d[codeID]"] = $_SESSION["code$d[codeID]"] + $amount;
			$line .= "<td align='right'>$".number_format($amount, 2)."</td>";
		}else{
			$line .= "<td></td>";
		}
	}
		$_SESSION["codetotal"] = $_SESSION["codetotal"] + $amount;
		$line .= "<td align='right'>$".number_format($amount, 2)."</td></tr>";
	return $line;
}
include 'menu.php';
// account bar to set get varriable
?><table width="100%" class="noprint"><tr><td><a href="?account=5">Process Serving</a></td><td><a href="?account=7">AMEX</a></td></tr></table><?
if (isset($_GET['account'])){


$r=@mysql_query("select * from ac_register where accountID = '".$_GET['account']."' AND codeID > '0' AND trans='WITHDRAW' order by entered DESC, checkNumber ");
?>
<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border:solid; ">
	<tr>
		<td>Date </td>
        <td>Check </td>
        <td>Paid To </td>
        <?
	$rx=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($dx=mysql_fetch_array($rx, MYSQL_ASSOC)){
		?>
		<td><?=$dx['codeID']?><br><?=$dx['codeName']?></td>
	<? }?>
		<td>Amount</td>
    </tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
    	<?=codeTransaction($d['entered'],$d['checkNumber'],$d['detail'],$d['codeID'],$d['amount'])?>
<? } ?> 
	<tr>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <?
	$rx=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($dx=mysql_fetch_array($rx, MYSQL_ASSOC)){
		?>
		<td align="right">$<?=number_format($_SESSION["code$dx[codeID]"],2);?></td>
	<? }?>
		<td align="right">$<?=number_format($_SESSION['codetotal'],2);?></td>
    </tr>

</table>   


<?
$_SESSION['code196']='';
$_SESSION['code410']='';
$_SESSION['code420']='';
$_SESSION['code430']='';
$_SESSION['code440']='';
$_SESSION['code520']='';
$_SESSION['code561']='';
$_SESSION['code690']='';
$_SESSION['codetotal']='';



$r=@mysql_query("select * from ac_register where accountID = '".$_GET['account']."' AND codeID > '0' AND trans='DEPOSIT' order by entered, checkNumber ");
?>
<table width="100%" border="1" cellpadding="2" cellspacing="0" style="border:solid; page-break-before:always; ">
	<tr>
		<td>Date </td>
        <td>Deposit </td>
        <?
	$rx=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($dx=mysql_fetch_array($rx, MYSQL_ASSOC)){
		?>
		<td><?=$dx['codeID']?><br><?=$dx['codeName']?></td>
	<? }?>
		<td>Amount</td>
    </tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
    	<?=codeTransaction2($d['entered'],$d['detail'],$d['codeID'],$d['amount'])?>
<? } ?> 
	<tr>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
        <?
	$rx=@mysql_query("SELECT * from ac_codes where codeID > '0' order by codeID");
	while($dx=mysql_fetch_array($rx, MYSQL_ASSOC)){
		?>
		<td align="right">$<?=number_format($_SESSION["code$dx[codeID]"],2);?></td>
	<? }?>
		<td align="right">$<?=number_format($_SESSION['codetotal'],2);?></td>
    </tr>

</table>   


<?
}
?>