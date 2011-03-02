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
include 'menu.php';
$_SESSION['code410']='';
$_SESSION['code420']='';
$_SESSION['code430']='';
$_SESSION['code440']='';
$_SESSION['total']='';
?>
<style> td {text-align:right}</style>
<div align="center" style="font-size:18px;">Code Breakdown</div>
<table border="1" align="center">
	<tr>
    	<td>Invoice</td>
    	<td>Check</td>
        <td>Total Paid</td>
        <td>410 - Process Serving</td>
        <td>420 - Mail Service</td>
        <td>430 - Court Filing</td>
        <td>440 - Skip Trace</td>
    </tr>
	<?
    $r=@mysql_query("select * from ps_packets, ps_pay WHERE ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
	if ($d['code410'] || $d['code420'] || $d['code430'] || $d['code440']){
    ?>
    <tr bgcolor="#CCFF66">
    	<td><?=$d['packet_id']?></td>
    	<td><?=$d['client_check']?></td>
    	<td>$<?=number_format($d['client_paid'],2)?></td>
        <td>$<?=number_format($d['code410'],2)?></td>
        <td>$<?=number_format($d['code420'],2)?></td>
        <td>$<?=number_format($d['code430'],2)?></td>
        <td>$<?=number_format($d['code440'],2)?></td>
    </tr>
<?
	}
$_SESSION['code410']=$_SESSION['code410']+$d['code410'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440'];
$_SESSION['total']=$_SESSION['total']+$d['client_paid'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from ps_packets, ps_pay WHERE ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
	if ($d['code410a'] || $d['code420a'] || $d['code430a'] || $d['code440a']){
    ?>
	<tr bgcolor="#66FFCC">
    	<td><?=$d['packet_id']?></td>
    	<td><?=$d['client_checka']?></td>
    	<td>$<?=number_format($d['client_paida'],2)?></td>
        <td>$<?=number_format($d['code410a'],2)?></td>
        <td>$<?=number_format($d['code420a'],2)?></td>
        <td>$<?=number_format($d['code430a'],2)?></td>
        <td>$<?=number_format($d['code440a'],2)?></td>
    </tr>
<?
	}
$_SESSION['code410']=$_SESSION['code410']+$d['code410a'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420a'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430a'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440a'];
$_SESSION['total']=$_SESSION['total']+$d['client_paida'];
?>
	<? }?>
    <?
    $r=@mysql_query("select * from ps_packets, ps_pay WHERE ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'");
    while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
	if ($d['code410b'] || $d['code420b'] || $d['code430b'] || $d['code440b']){
    ?>
	<tr bgcolor="#FFFFCC">
    	<td><?=$d['packet_id']?></td>
    	<td><?=$d['client_checkb']?></td>
    	<td>$<?=number_format($d['client_paidb'],2)?></td>
        <td>$<?=number_format($d['code410b'],2)?></td>
        <td>$<?=number_format($d['code420b'],2)?></td>
        <td>$<?=number_format($d['code430b'],2)?></td>
        <td>$<?=number_format($d['code440b'],2)?></td>
    </tr>
<?
	}
$_SESSION['code410']=$_SESSION['code410']+$d['code410b'];
$_SESSION['code420']=$_SESSION['code420']+$d['code420b'];
$_SESSION['code430']=$_SESSION['code430']+$d['code430b'];
$_SESSION['code440']=$_SESSION['code440']+$d['code440b'];
$_SESSION['total']=$_SESSION['total']+$d['client_paidb'];
?>
	<? }?>
	<tr bgcolor="#00FF00">
    	<td colspan="3">Total: $<?=number_format($_SESSION['total'],2)?></td>
        <td>$<?=number_format($_SESSION['code410'],2)?></td>
        <td>$<?=number_format($_SESSION['code420'],2)?></td>
        <td>$<?=number_format($_SESSION['code430'],2)?></td>
        <td>$<?=number_format($_SESSION['code440'],2)?></td>
    </tr>
</table>
