<?
session_start();
$_SESSION[Service]=0;
$_SESSION[Mail]=0; 
$_SESSION[Filing]=0;
$_SESSION[Paid1]=0;
$_SESSION[Paid2]=0;
$_SESSION[Paid3]=0;
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//-------------------------------------   FUNCTIONS  ---------------------------------------------------------------

function csvRow($packet){
$count=0;
$q="SELECT * FROM ps_packets, ps_pay where packet_id = '$packet' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r,MYSQL_ASSOC);
$client_file = $d['client_file'];
// loop through names and addresses
$row = 1;
$counter = 1;
while ($row < 6){ // run through each defendant
//if (personCheck($data['name'.$row])){
if ($d['name'.$row]){
if ($d['name'.$row]){
$order[$counter]['logic']=$packet.'-'.$row;
$order[$counter]['person']=strtoupper($d['name'.$row]);
$order[$counter]['address1']=strtoupper($d['address'.$row]);
$order[$counter]['city'] = strtoupper($d['city'.$row]);
$order[$counter]['state'] = strtoupper($d['state'.$row]);
$order[$counter]['zip'] = strtoupper($d['zip'.$row]);
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
if ($order[$countb]['state'] == "MD"){
$ins++;
}else{
$outs++;
}
}
}
}else{
if ($order[$countb]['state'] == "MD"){
$ins++;
}else{
$outs++;
}
}
}
// run some numbers
if ($d[service_status] == "MAILING AND POSTING" ){
$mailCost = 25;
$mailFee = $counta*$mailCost;
$_SESSION[Mail] = $_SESSION[Mail] + $mailFee;
}
if ($d[filing_status] != "DO NOT FILE" && $d[filing_status] != "CANCELLED"){
$filingCost = 25;
$_SESSION[Filing] = $_SESSION[Filing] + 25;
}
$totalService = $ins*75 + $outs*125;
$_SESSION[Service] = $_SESSION[Service] + $totalService;
$packetTotal = $totalService + $mailFee + $filingCost;


$_SESSION[Paid1] = $_SESSION[Paid1] + $d[client_paid];
$_SESSION[Paid2] = $_SESSION[Paid2] + $d[client_paida];
$_SESSION[Paid3] = $_SESSION[Paid3] + $d[client_paidb];

$clear = $packetTotal - $d[client_paid] - $d[client_paida] - $d[client_paidb]; 

$csvData = $packet.",".$d['client_file'].",".$d['date_received'].",".$totalService.",".$mailFee.",".$filingCost.",".$packetTotal.",".$d['service_status'].",".$d['client_paid'].",".$d['client_check'].", ".$d['client_paida'].", ".$d['client_checka'].", ".$d['client_paidb'].", ".$d['client_checkb'].",".$clear." \n";
return $csvData;
}
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------
//------------------------------------------ CODE ------------------------------------------------------------------
$step1 = @mysql_connect ();
$step2 = mysql_select_db ('core');

// x = attorneys id
// y = 2008-01
if ($_GET[x] && $_GET[y]){ 
$myFile = "psClientBill.$_GET[x].$_GET[y].csv";
if (file_exists($myFile)){
	unlink($myFile);
}
$fh = fopen($myFile, 'w') or die("can't open file");
// header
$data = "Packet Number,File Number,Date Received,Service,Mailing,Filing,Packet Total,Service Result,Check 1,Number 1, Check 2, Number 2, Check 3, Number 3, Clear \n";
// items
$q="select packet_id from ps_packets where
									 date_received >= '$_GET[y]-01' and 
									 date_received <= '$_GET[y]-31' and 
									 process_status <> 'DAMAGED PDF' and 
									 service_status <> 'WAIVED' and 
									 service_status <> 'DAMAGED PDF' and 
									 process_status <> 'DUPLICATE' and 
									 process_status <> 'FILE COPY' and 
									 attorneys_id = '$_GET[x]' 
									 		order by service_status, packet_id";

$r=@mysql_query($q);
while ($d=mysql_fetch_array($r,MYSQL_ASSOC)){





$data .= csvRow($d['packet_id']);

}




// totals
$totalBill = $_SESSION[Service] + $_SESSION[Mail] + $_SESSION[Filing];
$data .= " , , ,".$_SESSION[Service].",".$_SESSION[Mail].",".$_SESSION[Filing].",".$totalBill.", ,$_SESSION[Paid1], , $_SESSION[Paid2], , $_SESSION[Paid3], ,  \n";

$totalPaid = $_SESSION[Paid1] + $_SESSION[Paid2] + $_SESSION[Paid3];
$due = $totalBill - $totalPaid;
$data .= "Total Due,$due , , , , , , , , , , , , , \n";

fwrite($fh, $data);
fclose($fh);
// ok download the file
header("Content-type: application/force-download"); 
header('Content-Disposition: inline; filename="'.$myFile.'"'); 
header("Content-Transfer-Encoding: Binary"); 
header("Content-length: ".filesize($myFile)); 
header('Content-Type: application/excel'); 
header('Content-Disposition: attachment; filename="'.$myFile.'"'); 
// now load the data for download
readfile($myFile); 
}else{
echo "This link has expired.";
}
?>