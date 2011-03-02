<?
include 'functions.php';
include 'security.php';
dbConnect();
session_start();
$_SESSION[filed]=0;
 $_SESSION[notfiled]=0;

function paidStatus($num){
if ($num){ return '00FF00';}else{ return 'ffff00'; }
}
function fStatus($id){
$r=@mysql_query("select filing_status from ps_packets where packet_id='$id'");
$d=mysql_fetch_array($r,MYSQL_ASSOC);
if ( $d[filing_status] == "FILED WITH COURT"){
$_SESSION[filed]++;
return 'X';
}else{
$_SESSION[notfiled]++;
}

}
function slot($slot,$id){
$q="SELECT * FROM ps_packets, ps_pay where server_id".$slot." = '$id' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD' ORDER BY packet_id";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
    <tr bgcolor="#<?=paidStatus($d["contractor_check".$slot])?>">
    	<td><?=$d[packet_id]?></td>
        <td><?=$slot?></td>
        <td><?=fStatus($d[packet_id])?></td>
		<td><?=$d["contractor_rate".$slot]?></td>
    	<td><?=$d["contractor_paid".$slot]?></td>
    	<td><?=$d["contractor_check".$slot]?></td>
	</tr>
<? }} ?>

<? function timeline($id){?>
<table>
	<tr>
    <td valign="top">

<table border="1" style="border-collapse:collapse;">
    <tr>
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td>
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('',$id);?>
</table>

</td><td valign="top">


<table border="1" style="border-collapse:collapse;">
    <tr>
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td>
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('a',$id);?>
</table>

</td><td valign="top">
<table border="1" style="border-collapse:collapse;">
    <tr>
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td>
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('b',$id);?>
</table>

</td><td valign="top">
<table border="1" style="border-collapse:collapse;">
    <tr>
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td>
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('c',$id);?>
</table>

</td><td valign="top">
<table border="1" style="border-collapse:collapse;">
    <tr>
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td>
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('d',$id);?>
</table>

</td><td valign="top">
<table border="1" style="border-collapse:collapse;"> 
    <tr> 
    	<td>Packet</td>
        <td>Slot</td>
        <td>Filed</td> 
    	<td>Rate</td>
    	<td>Paid</td>
    	<td>Check</td>
	</tr>
<?=slot('e',$id);?>
</table>

</td></tr>
</table>
<? }?>

<h1>Complete Physical Protocol Review</h1>
<?
$i=0;
while ($i++ < 300){
echo "<div>#$i</div>";
timeline($i);
}
?><H3><?=$_SESSION[filed];?> Filed In Court</H3>
<H3><?=$_SESSION[notfiled];?> Notfiled</H3>
