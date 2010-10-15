<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Billing Matrix                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 11, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
//error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();
$start = '2008-09-01';
$end = '2008-09-30';
$totalBill='0';
$i='0';
$r=@mysql_query("select * from ps_packets where date_received >= '$start' and date_received <= '$end' and process_status <> 'DAMAGED PDF' and service_status <> 'WAIVED' and service_status <> 'DAMAGED PDF' and process_status <> 'DUPLICATE' and process_status <> 'FILE COPY' and attorneys_id = '1' order by service_status, packet_id");
echo "<table border='1'><tr><td>Packet</td><td>File Number</td><td>Date Received</td><td>Bill Matrix</td></tr>";
while ($d=mysql_fetch_array($r,MYSQL_ASSOC)){
$i++;
$bill = billingMatrix($d[packet_id],$_GET[show]);
$totalBill = $totalBill + $bill;
?>
<tr><td><?=$d[packet_id];?></td><td><?=$d[client_file];?></td><td><?=$d[date_received];?></td><td>$<?=$bill;?></td></tr>
<? }?>
<h1>$<?=number_format($totalBill,2);?> Due</h1>
</table>
<style> td { white-space:pre} </style>