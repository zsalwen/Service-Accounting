<style>
table
{
	border-collapse:collapse;
}
a
{
	text-decoration:none;
}
</style>
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
?>


		
<table border="1" width="100%">
<?
if ($_GET[attid]){
	$q="select packet_id, ps_pay.client_check, ps_pay.client_checka, ps_pay.client_checkb, client_file, case_no, date_received, service_status, filing_status, ps_pay.bill410, ps_pay.bill420, ps_pay.bill430, ps_pay.bill440, ps_pay.code410, ps_pay.code420, ps_pay.code430, ps_pay.code440, ps_pay.code410a, ps_pay.code420a, ps_pay.code430a, ps_pay.code440a, ps_pay.code410b, ps_pay.code420b, ps_pay.code430b, ps_pay.code440b, attorneys_id, DATE_FORMAT(date_received,'%M %D, %Y at %l:%i%p') as date_received_f from ps_packets, ps_pay where ps_pay.bill410 <> '' AND date_received < '$pastDue' AND attorneys_id = '$_GET[attid]' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD' order by packet_id";
}else{
	$q="select packet_id, ps_pay.client_check, ps_pay.client_checka, ps_pay.client_checkb, client_file, case_no, date_received, service_status, filing_status, ps_pay.bill410, ps_pay.bill420, ps_pay.bill430, ps_pay.bill440, ps_pay.code410, ps_pay.code420, ps_pay.code430, ps_pay.code440, ps_pay.code410a, ps_pay.code420a, ps_pay.code430a, ps_pay.code440a, ps_pay.code410b, ps_pay.code420b, ps_pay.code430b, ps_pay.code440b, attorneys_id, DATE_FORMAT(date_received,'%M %D, %Y at %l:%i%p') as date_received_f from ps_packets, ps_pay where ps_pay.bill410 <> '' AND date_received < '$pastDue' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD' order by packet_id";
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
	<td style="border-top:solid #999999 5px; border-left:solid #999999 5px;">File <?=$d[client_file];?> as Packet <?=$d[packet_id];?> on <?=$d[date_received_f];?></td>
	<td style="border-top:solid #999999 5px;">Case No: <?=$d[case_no];?></td>
	<td style="border-top:solid #999999 5px;">Service Bill: $<?=$d[bill410];?></td>
	<td style="border-top:solid #999999 5px;">Mailing Bill: $<?=$d[bill420];?></td>
	<td style="border-top:solid #999999 5px;">Filing Bill: $<?=$d[bill430];?></td>
	<td style="border-top:solid #999999 5px;">S.Trace Bill: $<?=$d[bill440];?></td>
	<td style="border-top:solid #999999 5px; border-right:solid #999999 5px;"></td>
</tr>
<tr <? if ($d[code410]){ echo "style='background-color:ccffcc;'";}?>>
	<td style="border-left:solid #999999 5px;"><?=$d[service_status]?></td>
	<td></td>
	<td><? if($d[code410] || $d[code420] ||$d[code430] ||$d[code440]){?>P#<?=$d[client_check]?>: $<?=$d[code410];?>, $<?=$d[code420];?>, $<?=$d[code430];?><? }?></td>
	<td><? if($d[code410a] || $d[code420a] ||$d[code430a] ||$d[code440a]){?>P#<?=$d[client_checka]?>: $<?=$d[code410a];?>, $<?=$d[code420a];?>, $<?=$d[code430a];?><? }?></td>
	<td colspan="2"><? if($d[code410b] || $d[code420b] ||$d[code430b] ||$d[code440b]){?>P#<?=$d[client_checkb]?>: $<?=$d[code410b];?>, $<?=$d[code420b];?>, $<?=$d[code430b];?><? }?></td>
	<td style="border-right:solid #999999 5px;"><b>Unpaid: $<?=$due?></b></td>
</tr>
<? }  } ?>
</table>
<?
$adv = $masterDue / $i;
?>
<script>
document.title = "<?=$months;?> Months Past Order: <?=$i;?> $<?=number_format($masterDue,2);?>  @ $<?=number_format($adv,2);?> / file";
</script>


