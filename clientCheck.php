<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Review                				  	                      |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: Aug 19, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbAlphaConnect();
session_start();
include 'menu.php';
$_SESSION['code410']='';
$_SESSION['code420']='';
$_SESSION['code430']='';
$_SESSION['code440']='';
$_SESSION['total']='';
if (isset($_GET['id'])){
?>
<style> td {text-align:right}</style>
<a href="clientCheck.php">New Deposit</a><hr />
<div align="left" style="font-size:18px;" class="noprint">Deposit Breakdown:<br />
<? 
$i='0';
$i2='1';
$where='';
$rec = count($_GET['id']);
while ($i < $rec ){?>
<? $where .= "client_check = '".$_GET['id']["$i"]."' or "; ?>
Check <?=$i2;?>: <?=$_GET['id']["$i"];?><br />
<? $i++; $i2++; }
$cut =  strlen($where);
?>

<small><?=substr($where,0,$cut-3);?></small><hr />
</div><table border="1" align="center">
	<tr>
    	<td>Invoice</td>
		<td>Client File</td>
        <td>Check</td>
        <td>Total Paid</td>
        <td>410 - Process Serving</td>
        <td>420 - Mail Service</td>
        <td>430 - Court Filing</td>
        <td>440 - Skip Trace</td>
    </tr>
	<?
	$r=@mysql_query("select * from ps_packets where client_check <> '' AND (".substr($where,0,$cut-3).") ")or die(mysql_error());
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#CCFF66">
    	<td>OTD<?=$d['packet_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_check']?></td>
    	<td>$<?=number_format($d['client_paid'],2)?></td>
        <td>$<?=number_format($d['code410'],2)?></td>
        <td>$<?=number_format($d['code420'],2)?></td>
        <td>$<?=number_format($d['code430'],2)?></td>
        <td>$<?=number_format($d['code440'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440'];
$_SESSION['total']=$_SESSION['total']+$d['client_paid'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from ps_packets where client_checka <> '' AND (".substr($where,0,$cut-3).") ");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#66FFCC">
    	<td>OTD<?=$d['packet_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_checka']?></td>
    	<td>$<?=number_format($d['client_paida'],2)?></td>
        <td>$<?=number_format($d['code410a'],2)?></td>
        <td>$<?=number_format($d['code420a'],2)?></td>
        <td>$<?=number_format($d['code430a'],2)?></td>
        <td>$<?=number_format($d['code440a'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410a'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420a'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430a'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440a'];
$_SESSION['total']=$_SESSION['total']+$d['client_paida'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from ps_packets where client_checkb <> '' AND (".substr($where,0,$cut-3).") ");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#FFFFCC">
    	<td>OTD<?=$d['packet_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_check']?></td>
    	<td>$<?=number_format($d['client_paidb'],2)?></td>
        <td>$<?=number_format($d['code410b'],2)?></td>
        <td>$<?=number_format($d['code420b'],2)?></td>
        <td>$<?=number_format($d['code430b'],2)?></td>
        <td>$<?=number_format($d['code440b'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410b'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420b'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430b'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440b'];
$_SESSION['total']=$_SESSION['total']+$d['client_paidb'];
?>
	<? }?>
	
	
	
	<?
	$r=@mysql_query("select * from evictionPackets where client_check <> '' AND (".substr($where,0,$cut-3).") ")or die(mysql_error());
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#CCFF66">
    	<td>EV<?=$d['eviction_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_check']?></td>
    	<td>$<?=number_format($d['client_paid'],2)?></td>
        <td>$<?=number_format($d['code410'],2)?></td>
        <td>$<?=number_format($d['code420'],2)?></td>
        <td>$<?=number_format($d['code430'],2)?></td>
        <td>$<?=number_format($d['code440'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440'];
$_SESSION['total']=$_SESSION['total']+$d['client_paid'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from evictionPackets where client_checka <> '' AND (".substr($where,0,$cut-3).") ");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#66FFCC">
    	<td>EV<?=$d['eviction_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_checka']?></td>
    	<td>$<?=number_format($d['client_paida'],2)?></td>
        <td>$<?=number_format($d['code410a'],2)?></td>
        <td>$<?=number_format($d['code420a'],2)?></td>
        <td>$<?=number_format($d['code430a'],2)?></td>
        <td>$<?=number_format($d['code440a'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410a'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420a'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430a'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440a'];
$_SESSION['total']=$_SESSION['total']+$d['client_paida'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from evictionPackets where client_checkb <> '' AND (".substr($where,0,$cut-3).") ");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
    ?>
	<tr bgcolor="#FFFFCC">
    	<td>EV<?=$d['eviction_id']?></td>
		<td><?=$d['client_file']?></td>
    	<td><?=$d['client_check']?></td>
    	<td>$<?=number_format($d['client_paidb'],2)?></td>
        <td>$<?=number_format($d['code410b'],2)?></td>
        <td>$<?=number_format($d['code420b'],2)?></td>
        <td>$<?=number_format($d['code430b'],2)?></td>
        <td>$<?=number_format($d['code440b'],2)?></td>
    </tr>
<?
$_SESSION['code410']=$_SESSION['code410']+$d['code410b'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420b'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430b'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440b'];
$_SESSION['total']=$_SESSION['total']+$d['client_paidb'];
?>
	<? }?>
	
	
	<tr bgcolor="#00FF00">
    	<td colspan="4">Check Total: $<?=number_format($_SESSION['total'],2)?></td>
        <td>$<?=number_format($_SESSION['code410'],2)?></td>
        <td>$<?=number_format($_SESSION['code420'],2)?></td>
        <td>$<?=number_format($_SESSION['code430'],2)?></td>
        <td>$<?=number_format($_SESSION['code440'],2)?></td>
    </tr>
</table>
<? }elseif (isset($_GET['items'])){ ?>
<form> 
<? 
$ix=0;
while ($ix < $_GET['items']){ ?>
<div>Enter Check Number <input name="id[<?=$ix;?>]"> </div>
<? $ix++; }?>
<input type="submit" value="Code Deposit"></form>

<? }else{ ?>
<form> Total Items <input name="items"> <input type="submit" value="Next"></form>
<? }?>
