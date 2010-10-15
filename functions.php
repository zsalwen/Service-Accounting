<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Custom Functions                					                  |
// | Requirements: n/a    			                                      |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 2, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
function hardLog($str,$type){
	if ($type == "user"){
		$log = "/logs/user.log";
	}
	// this is important code 
	if ($log){
		error_log('['.date('h:iA m/d/y')."] [".$_SERVER["REMOTE_ADDR"]."] [".trim($str)."]\n", 3, $log);
	}
	// this is important code 
}

function dbConnect(){
	$step1 = @mysql_connect ();
	$step2 = mysql_select_db ('intranet');
}
function dbAlphaConnect(){
	$step1 = @mysql_connect ();
	$step2 = mysql_select_db ('core');
}
function row_color_light($i){
    $bg1 = "#FFFFCC"; // color one lightest yellow
    $bg2 = "#CCFFFF"; // color two lightest blue
    if ( $i%2 ) {
        return $bg1;
    } else {
        return $bg2;
    }
}

function row_color($i,$bg1,$bg2){
    if ( $i%2 ) {
        return $bg1;
    } else {
        return $bg2;
    }
}

function id2attorneys($id){
	$q = "SELECT display_name FROM attorneys WHERE attorneys_id='$id'";
	$r = @mysql_query($q);
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	return $d['display_name'];
}
function payWeeks(){
	$r=@mysql_query("SELECT * FROM paychecks");
	while ($d = mysql_fetch_array($r, MYSQL_ASSOC)){
		$options .= "<option value='".$d['period_start']."'>".$d['period_start']." to ".$d['period_end']."</option>";
	}
	return $options;
}
function logAction($action){
	$action = addslashes($action);
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = "DIRECT";
	}
	$proxy = $_SERVER['REMOTE_ADDR'];
	$user_id = $_COOKIE['userdata']['user_id'];
	@mysql_query("INSERT INTO activity_log (user_id, action, action_on, system_ip, system_proxy) VALUES ('$user_id', '$action', NOW(), '$ip', '$proxy')");		
}
function dbCleanIn($string){
	$string = addslashes($string);
	return $string;
}
function dbCleanOut($string){
	$string = stripslashes($string);
	return $string;
}
function hit($id){
	$q1 = "SELECT hits FROM schedule_items WHERE schedule_id = '$id'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
	$d1 = mysql_fetch_array($r1, MYSQL_ASSOC);
	$hits = $d1[hits] + 1;
	$q1 = "UPDATE schedule_items set hits='$hits' WHERE schedule_id = '$id'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
}
function setLocation($str){
	$id = $_COOKIE[userdata][user_id];
	$page = $_SERVER['PHP_SELF'];
	$query = $_SERVER['QUERY_STRING'];
	$q = "UPDATE users SET system_location = '$str', system_time=NOW(), system_page='$page', system_page_query='$query' WHERE user_id='$id'";
	$r = @mysql_query($q);
}

function log_action($user_id,$action){
	$action = addslashes($action);
	$ip = $_SERVER[HTTP_X_FORWARDED_FOR];
	$proxy = $_SERVER['REMOTE_ADDR'];
	$user_id = $_COOKIE[userdata][user_id];
	$q1 = "INSERT INTO activity_log (user_id, action, action_on, system_ip, system_proxy) VALUES ( '$user_id', '$action', NOW(), '$ip', '$proxy' )";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
}
function id2name($id){
	$q = "select name from users where user_id='$id'";
	$r = @mysql_query($q);
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	return $d['name'];
}
function serve2name($id){
	$q = "select name from ps_users where id='$id'";
	$r = @mysql_query($q);
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	return $d['name'];
}
function accountBalance($id){// account id
	$balance = 0;
	$r=@mysql_query("select * from ac_register where accountID = '$id' order by entered, checkNumber ");
	while($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		
		if($d['trans'] == "WITHDRAW"){
			$balance = $balance - $d['amount'];
		}else{
			$balance = $balance + $d['amount'];
		}	
	}
	return '$'.number_format($balance,2);
}
function codeName($id){
	$q="select codeName from ac_codes where codeID = '$id'";
	$r=@mysql_query($q);
	$d=mysql_fetch_array($r, MYSQL_ASSOC);
	if ($d['codeName']){
		return $d['codeName'];
	}else{
		return $id;
	}
	
}
function codeList($id){
	$q="select codeName from ac_codes order by codeID DESC";
	$r=@mysql_query($q);
	$opt='';
	while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		$opt .= "<option value='".$d['codeID']."'>".$d['codeName']."</option>";
	}
	return $opt;
	
}
function codeSelect($current){
	$q2 = "select * from ac_codes where codeID = '$current'";
	$r2 = @mysql_query($q2) or die(mysql_error());
	$d2 = mysql_fetch_array($r2, MYSQL_ASSOC);
	$q = "select * from ac_codes order by codeID";
	$r = @mysql_query($q) or die(mysql_error());
	if ($d2[codeName]){	
		$option = "<option value='".$d2['codeID']."'>".$d2['codeName']."</option>";
	}
	while ($choice = mysql_fetch_array($r, MYSQL_ASSOC)){
		$option .= "<option value='".$choice['codeID']."'>".$choice['codeName']."</option>";
	}
	return $option;
}


function addressCheck($address){
// do not serve the following addresses EVER
if ($address == "Foreclosure Unit"){ return 0;} 

return 1;
}
function personCheck($person){
// do not serve the following addresses EVER
if ($person == "Commissioner of Financial Regulation"){ return 0;} 

return 1;
}






function billingMatrix($packet,$show=0){
if ($show==0){
echo "<!--";
}
echo "<div align='center' style='font-size:30px'>Invoice for Packet $packet</div>";
echo "<table border='1' align='center'><tr>";
$count=0;
$q="SELECT * FROM ps_packets where packet_id = '$packet'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r,MYSQL_ASSOC);
$client_file = $d['client_file'];
// loop through names and addresses
$row = 1;
$counter = 1;
while ($row < 6){ // run through each defendant
//if (personCheck($data['name'.$row])){
if ($d['name'.$row]){
//echo "<div  style='padding:10px;'>";//<b> DEFENDANT #$row: </b><br />";
if ($d['name'.$row]){
//echo "$packet-$row<br>";
$order[$counter]['logic']=$packet.'-'.$row;
//echo $order[$row]['logic'];
$order[$counter]['person']=strtoupper($d['name'.$row]);
//echo $order[$counter]['person'].'<br>';
$order[$counter]['address1']=strtoupper($d['address'.$row]);
//echo $order[$counter]['address1'].'<br>';
$order[$counter]['city'] = strtoupper($d['city'.$row]);
//echo $order[$counter]['city'].', ';
$order[$counter]['state'] = strtoupper($d['state'.$row]);
//echo $order[$counter]['state'].' ';
$order[$counter]['zip'] = strtoupper($d['zip'.$row]);
//echo $order[$counter]['zip'];
$counter++;
}	
foreach (range('a','f') as $letter){	
if ($d['address'.$row.$letter]){
$order[$counter]['logic']=$packet.'-'.$row.'.'.$letter;
$order[$counter]['person']=strtoupper($d['name'.$row]);
$order[$counter]['address1']=strtoupper($d['address'.$row]);
$order[$counter]['city'] = strtoupper($d['city'.$row]);
$order[$counter]['state'] = strtoupper($d['state'.$row]);
$order[$counter]['zip'] = strtoupper($d['zip'.$row]);
$counter++;
}	
}
}
$row++;
}
$counta = count($order);
$countb = 0;
$ins = 0;
$outs = 0;
while ($countb++ < $counta){
if (isset($order[$countb])){
foreach (range('a','f') as $letter){	
if($letter=='a'){	
echo "<div style='padding:10px;'>SERVE 
".$order[$countb]['logic'].":<br> 
".$order[$countb]['person']."
<br>AT 
".$order[$countb]['address1']."
<br>
".$order[$countb]['city']."
, ".$order[$countb]['state']."
".$order[$countb]['zip']."</div>";
if ($order[$countb]['state'] == "MD"){
$ins++;
}else{
$outs++;
}
}
}
}else{
echo "<div style='padding:10px;'>SERVE 
".$order[$countb]['logic'].":<br> 
".$order[$countb]['person']."
<br>AT 
".$order[$countb]['address1']."
<br>
".$order[$countb]['city']."
, ".$order[$countb]['state']."
".$order[$countb]['zip']."</div>";
if ($order[$countb]['state'] == "MD"){
$ins++;
}else{
$outs++;
}
}
}
echo "</td><td valign='top'>";
echo "<b> Billing rates for this file:</b><br>";
?>
<div  style='padding:10px;'>
<table cellspacing="0">
<tr bgcolor="#CCCCCC">
<td>Item</td>
<td>Units</td>
<td>Total</td>
</tr>
<tr>
<td>In-State Service</td>
<td><?=$ins?></td>
<td>$<?=$ins*75?>.00</td>
</tr>
<tr>
<td>Out-of-State Service</td>
<td><?=$outs?></td>
<td>$<?=$outs*125?>.00</td>
</tr>
<?
// test for mailing
if ($d[service_status] == "MAILING AND POSTING" ){
$mailCost = 25;
?>
<tr>
<td>Mailing Service</td>
<td><?=$counta?></td>
<td>$<?=$counta*$mailCost?>.00</td>
</tr>
<?
}
// test for filing
if ($d[filing_status] != "DO NOT FILE" ){
$filingCost = 25;
?>
<tr>
<td>File and Return Service</td>
<td>n/a</td>
<td>$25.00</td>
</tr>
<? } ?>
<tr>
<td style="border-top:solid 1px;" colspan="3">Current Bill</td>
<td style="border-top:solid 1px;"><? if ($d[service_status] != 'IN PROGRESS'){  $finalBill = ($ins*75)+($outs*125)+($counta*$mailCost)+($filingCost);  ?><strong>$<?=$finalBill?>.00</strong><? }?></td>
</tr>
</table>
</td></tr></table>
<?
if ($show==0){
echo "-->";
}

return $finalBill;
}
function addNote($id,$note){

	$q1 = "SELECT notes FROM schedule_items WHERE schedule_id = '$id'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
	$d1 = mysql_fetch_array($r1, MYSQL_ASSOC);
	$notes = $note.", ".$d1[notes];
	$notes = addslashes($notes);
	$q1 = "UPDATE schedule_items set notes='$notes' WHERE schedule_id = '$id'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
}
function paper2contact($id){
mysql_select_db ('intranet');

	$q = "select name from paper_contacts where contact_id='$id'";
	$r = @mysql_query($q);
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	if ($d[name]){
		$ret = $d[name];
	}else{
		$ret = knownip($id);
	}

	return $ret;
}
function id2tag($id){
	$q = "select tag from users where user_id='$id'";
	$r = @mysql_query($q) or die ('Query: $q<br>'.mysql_error());
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	return $d[tag];
}
function id2contact($id){
mysql_select_db ('ccdb');

	$q = "SELECT attorneys_id FROM contacts WHERE contact_id='$id'";
	$r = @mysql_query($q) or die ("Query: $q<br>".mysql_error());
	$d = mysql_fetch_array($r, MYSQL_ASSOC);
	$q2 = "SELECT * FROM attorneys WHERE attorneys_id='$d[attorneys_id]'";
	$r2 = @mysql_query($q2)or die("Query: $q2<br>".mysql_error());
	$d2 = mysql_fetch_array($r2, MYSQL_ASSOC);
	$who = $d[name];
	return strtoupper($who);
}

?>
