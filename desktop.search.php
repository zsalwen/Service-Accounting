<?
dbConnect();
// ok this is going to be the main search receiver for the front office
echo "<pre>You are searching for: <strong>$_GET[q]</strong><br>";
echo "Your searching in: <strong>$_GET[type]</strong><br>";
echo "Processing Core: <strong>$_GET[core]</strong></pre>";
$results = "<strong>Search Results:</strong><br>";
if (isset($_GET['core']) && $_GET['core'] == "AC"){
	if (isset($_GET['type']) && $_GET['type'] =='Address'){ $field = 'address1';} 
	if (isset($_GET['type']) && $_GET['type'] =='Client File'){ $field = 'file';} 
	if (isset($_GET['type']) && $_GET['type'] =='Washington Post'){ $field = 'ad_number';} 
	if (isset($_GET['type']) && $_GET['type'] =='Our File Number'){ $field = 'schedule_id';} 
	if (isset($_GET['type']) && $_GET['type'] =='ad_number'){ $field = 'ad_number';}
	if (isset($_GET['type']) && $_GET['type'] =='legal_fault'){ $field = 'legal_fault';}
	if (isset($_GET['type']) && $_GET['type'] =='purchaser'){ $field = 'purchaser';}
	if (isset($_GET['type']) && $_GET['type'] =='ad_cost'){ $field = 'ad_cost';}
	if (isset($_GET['type']) && $_GET['type'] =='auction_fee'){ $field = 'auction_fee';}
	if (isset($_GET['type']) && $_GET['type'] =='attorneys_id'){ $field = 'attorneys_id';}
	mysql_select_db ('intranet');
	$q="select * from schedule_items where $field like '%$_GET[q]%'";
	//echo $q."<br>";
	$r=@mysql_query($q) or die(mysql_error());
	while ($d=mysql_fetch_array($r,MYSQL_ASSOC)){
		if ($d[item_status]=="CANCELLED"){ $color = '990000'; }
		
		$results .= "<a href='?id=$d[schedule_id]'><li style='background-color:#$color'>$d[address1] auction taking place $d[sale_date] at $d[sale_time]  ($d[schedule_id])</li></a>";
	}
}elseif(isset($_GET['core']) && $_GET['core'] == "PS"){
	mysql_select_db ('core');
	if (isset($_GET['type']) && $_GET['type'] =='Address'){ $field = 'address1';} 
	if (isset($_GET['type']) && $_GET['type'] =='Client File'){ $field = 'client_file';} 
	if (isset($_GET['type']) && $_GET['type'] =='Our File Number'){ $field = 'packet_id';} 
	$q="select * from ps_packets where $field like '%$_GET[q]%'";
	echo $q."<br>";
	$r=@mysql_query($q) or die(mysql_error());
	while ($d=mysql_fetch_array($r,MYSQL_ASSOC)){
		$results .= "<a href='?packet=$d[packet_id]'><li>$d[packet_id]</li></a>";
	}



}
?>





<pre><?=$results;?></pre>