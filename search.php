<script>document.title = "Company Data Search";</script>
<? 
include 'functions.php';
dbConnect();
if ($_GET[t]){
$q = $_GET[q];
$t = $_GET[t];

if ($_GET['date']){
	$date = explode('-',$_GET['date']);
	$day = $date[2];
	$month = $date[1];
	$year = $date[0];
} else {
	$day = date ('d');
	$month = date('m');
	$year = date('Y');
}
?>
<table bgcolor="#FFFFFF" width="100%" cellpadding="1" align="center" style="border-collapse:collapse" border="0">
<tr>
<td colspan="8"><?
 echo "Searching $_GET[t] for $_GET[q]";
 log_action($_COOKIE[userdata][user_id],"Searching $_GET[t] for $_GET[q]");
 
 ?></td>
</tr>
	<tr>
		<td></td>
		<td><strong>Scheduled</strong></td>
		<td><strong>Address</strong></td>
		<td><strong>County</strong></td>
		<td><strong>Deposit</strong></td>
		<td><strong>Ad Start</strong></td>
		<td><strong>Attorney</strong></td>
		<td><strong>Processor</strong></td>
	</tr>
<? // DATE_FORMAT(sale_date,'%l:%i%p') as sale_date_f
$i=0;
$qdate = $year.'-'.$month.'-'.$day;
$q1 = "SELECT * FROM schedule_items, attorneys  WHERE schedule_items.attorneys_id = attorneys.attorneys_id AND schedule_items.$t like '%$q%' ORDER BY schedule_items.sale_date, schedule_items.sort_time";		
$r1 = @mysql_query ($q1) or die(mysql_error());
while ($data1 = mysql_fetch_array($r1, MYSQL_ASSOC)) {	
$i++;
	if ( $data1[item_status] == "SALE CANCELLED"){ $class = 'canceled';	} else {$class = 'active';	}
	$code = "x".$data1[attorneys_id];
	//---
	if ($data1[updated_id] > 0){
		$user = $data1[updated_id];
	} else {
		$user = $data1[created_id];
	}
	$q2 = "SELECT * FROM users WHERE user_id = '$user'";		
	$r2 = @mysql_query ($q2) or die(mysql_error());
	$data2 = mysql_fetch_array($r2, MYSQL_ASSOC);
	//---
	$_SESSION[search_jump] = $data1[schedule_id];
	echo "
	<tr class='$class'>
		<td class='$code' style='text-align:center' nowrap>$i <a  href='?page=details&id=$data1[schedule_id]'><img src='images/details.gif' border='0'></a></td>	
		<td class='$code' nowrap>$data1[sale_date] @ $data1[sale_time]</td>
		<td class='$code' nowrap>$data1[prefix]$data1[address1]</td>
		<td class='$code' nowrap>$data1[county]</td>
		<td class='$code' nowrap>$$data1[deposit]K</td>
		<td class='$code'>$data1[ad_start]</td>
		<td nowrap>$data1[display_name] / $data1[file]</td>
		<td>$data2[tag]</td>
	</tr>";
}
if($i == '1'){ echo "<h1>JUMP</h1><script>window.location.href='?page=details&id=$_SESSION[search_jump]'</script>";}
?>
</table>
<? } else{?>


<div style="background-color:#6699CC; color:#FFFFFF; font-weight:bold; text-align:left">

<? $search = $_GET[q]; ?>


<div style="font-size:24px;">Running complete search of auction files for <?=$search?></div>
<? 
function systemLookup($field, $query){ 
	$r=@mysql_query("select * from schedule_items where $field like '%$query%'");
	while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		if ($d[schedule_id]){
			?>
			<div style="border:ridge 3px #999999;">
            <div style="font-size:18px">Found string '<strong><?=$d[$field]?></strong>' <small>delta.mdwestserve.com.intranet.schedule_items.<?=$field?></small></div>
			<div style="font-size:16px; padding-left:50px;">Auction #<?=$d[schedule_id]?> :: <a  href='?page=details&id=<?=$d[schedule_id]?>'>View Details</a> :: <?=$d[address1]?> <?=$d[sale_date]?> @ <?=$d[sale_time]?> </div>
            </div>
			<?
		}
	} 
}
$q="SHOW FIELDS FROM schedule_items";
$r=@mysql_query($q);
$i = 0;
while ($row = mysql_fetch_array($r)){
	systemLookup($row['Field'], $search);
}


?>
</div>
<div style="background-color:#993300; color:#FFFFFF; font-weight:bold; text-align:left">

<div style="font-size:24px;">Running complete search of ad packets for <?=$search?></div>
<? 
function systemLookup2($field, $query){ 
	$r=@mysql_query("select * from ad_packets where $field like '%$query%'");
	while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		if ($d[packet_id]){
			?>
			<div style="border:ridge 3px #999999;">
            <div style="font-size:18px">Found string '<strong><?=$d[$field]?></strong>' <small>delta.mdwestserve.com.intranet.ad_packets.<?=$field?></small></div>
			<div style="font-size:16px; padding-left:50px;">Packet #<?=$d['packet_id']?> :: <a  href='?page=search&q=<?=$d['file']?>'>Search File Number <?=$d['file']?></a></div>
            </div>
			<?
		}
	} 
}
$q="SHOW FIELDS FROM ad_packets";
$r=@mysql_query($q);
$i = 0;
while ($row = mysql_fetch_array($r)){
	systemLookup2($row['Field'], $search);
}





?></div>

<? }?>