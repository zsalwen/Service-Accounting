<?
mysql_connect();
mysql_select_db('core');
if ($_GET[months]){
	$months = $_GET[months];
}else{
	$months = 3;
}
$lastmonth = mktime(0, 0, 0, date("m")-$months, date("d"),   date("Y"));
$pastDue = date('Y-m-d H:i:s',$lastmonth);

function id2attorney($id){
	$q="SELECT display_name FROM attorneys WHERE attorneys_id = '$id'";
	$r=@mysql_query($q);
	$d=mysql_fetch_array($r, MYSQL_ASSOC);
return $d[display_name];
}

function leading_zeros($value, $places){
    if(is_numeric($value)){
        for($x = 1; $x <= $places; $x++){
            $ceiling = pow(10, $x);
            if($value < $ceiling){
                $zeros = $places - $x;
                for($y = 1; $y <= $zeros; $y++){
                    $leading .= "0";
                }
            $x = $places + 1;
            }
        }
        $output = $leading . $value;
    }
    else{
        $output = $value;
    }
    return $output;
}
include 'menu.php';
?>


		<form><h2 align="center">Underpaid and <input name="months" size="2" value="<?=$months;?>"> Months Past Sale <select name="attid">
				<?	echo "<option value='$_GET[attid]'>".id2attorney($_GET[attid])."</option>";
echo "<option value=''>SHOW ALL</option>";
echo "<option value='others'>Exclude Burson and White</option>";
		$q8 = "SELECT attorneys_id, display_name FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	echo "<option value='$data8[attorneys_id]'>$data8[display_name]</option>";
		} 
		
		?>
</select> <input type="submit" value="Load Report &amp; Reload Report"> <? if ($_GET[attid]){ ?>| <a href="client.php?attid=<?=$_GET[attid]?>&months=<?=$_GET[months]?>" target="_Blank">Client Report</a><? }?></h2></form>

<table border="1" width="100%">
<?
if ($_GET[attid]){
	if($_GET[attid] == 'others'){
		$q="select packet_id, date_received, service_status, filing_status, bill410, bill420, bill430, bill440, code410, code420, code430, code440, code410a, code420a, code430a, code440a, code410b, code420b, code430b, code440b, attorneys_id, DATE_FORMAT(date_received,'%M %D, %Y at %l:%i%p') as date_received_f from ps_packets where bill410 <> '' AND date_received < '$pastDue' AND attorneys_id <> '1' AND attorneys_id <> '3' order by packet_id";
	}else{
		$q="select packet_id, date_received, service_status, filing_status, bill410, bill420, bill430, bill440, code410, code420, code430, code440, code410a, code420a, code430a, code440a, code410b, code420b, code430b, code440b, attorneys_id, DATE_FORMAT(date_received,'%M %D, %Y at %l:%i%p') as date_received_f from ps_packets where bill410 <> '' AND date_received < '$pastDue' AND attorneys_id = '$_GET[attid]' order by packet_id";
	}
}else{
	$q="select packet_id, date_received, service_status, filing_status, bill410, bill420, bill430, bill440, code410, code420, code430, code440, code410a, code420a, code430a, code440a, code410b, code420b, code430b, code440b, attorneys_id, DATE_FORMAT(date_received,'%M %D, %Y at %l:%i%p') as date_received_f from ps_packets where bill410 <> '' AND date_received < '$pastDue' order by packet_id";
}
$r=@mysql_query($q);
$i=0;
while ($d=mysql_fetch_array($r,MYSQL_ASSOC)){
$due = $d[bill410] + $d[bill420]+ $d[bill430]+ $d[bill440] - $d[code410] - $d[code420] -$d[code430] -$d[code440] - $d[code410a] - $d[code420a] -$d[code430a] -$d[code440a] - $d[code410b] - $d[code420b] -$d[code430b] -$d[code440b];
if ($due > 0){
$i++;
$masterDue = $masterDue + $due;
?>
<tr <? if (!$d[bill410]){ echo "style='background-color:FF0000;'";}?>>
	<td style="border-top:solid #999999 5px; border-left:solid #999999 5px;">Packet <?=$d[packet_id];?> on <?=$d[date_received_f];?> for <?=id2attorney($d[attorneys_id])?></td>
	<td style="border-top:solid #999999 5px;">Service Bill: $<?=$d[bill410];?></td>
	<td style="border-top:solid #999999 5px;">Mailing Bill: $<?=$d[bill420];?></td>
	<td style="border-top:solid #999999 5px;">Filing Bill: $<?=$d[bill430];?></td>
	<td style="border-top:solid #999999 5px;">Skip Trace Bill: $<?=$d[bill440];?></td>
	<td style="border-top:solid #999999 5px; border-right:solid #999999 5px;"><a href="http://staff.mdwestserve.com/otd/minips_pay.php?id=<?=$d[packet_id];?>" target="_Blank">Update Payments</a>, <a href="http://staff.mdwestserve.com/otd/order.php?packet=<?=$d[packet_id];?>" target="_Blank">Update Order</a> </td>
</tr>
<tr <? if ($d[code410]){ echo "style='background-color:ccffcc;'";}?>>
	<td style="border-left:solid #999999 5px;"><?=$d[service_status]?></td>
	<td><? if($d[code410] || $d[code420] ||$d[code430] ||$d[code440]){?>Paid: $<?=$d[code410];?>, $<?=$d[code420];?>, $<?=$d[code430];?>, $<?=$d[code440];?><? }?></td>
	<td><? if($d[code410a] || $d[code420a] ||$d[code430a] ||$d[code440a]){?>Paid: $<?=$d[code410a];?>, $<?=$d[code420a];?>, $<?=$d[code430a];?>, $<?=$d[code440a];?><? }?></td>
	<td colspan="2"><? if($d[code410b] || $d[code420b] ||$d[code430b] ||$d[code440b]){?>Paid: $<?=$d[code410b];?>, $<?=$d[code420b];?>, $<?=$d[code430b];?>, $<?=$d[code440b];?><? }?></td>
	<td style="border-right:solid #999999 5px;"><a href="http://staff.mdwestserve.com/invoice.html.php?packet=<?=$d[packet_id];?>" target="_Blank"><b>Unpaid: $<?=$due?></b></a> , <a href="http://staff.mdwestserve.com/otd/ps_write_invoice.php?id=<?=$d[packet_id];?>" target="_Blank">Client Invoice</a></td>
</tr>
<? }  } ?>
</table>
<?
$adv = $masterDue / $i;
?>
<script>
document.title = "<?=$months;?> Months Past Order: <?=$i;?> $<?=number_format($masterDue,2);?>  @ $<?=number_format($adv,2);?> / file";
</script>


