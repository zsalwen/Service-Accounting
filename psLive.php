<?
if ($_SERVER['HTTP_HOST'] != 'alpha.mdwestserve.com'){ 
	header('Location: http://alpha.mdwestserve.com'.$_SERVER['REQUEST_URI']);
}

session_start();
$_SESSION[Service]=0;
$_SESSION[Mail]=0; 
$_SESSION[Filing]=0;
$_SESSION[Paid1]=0;
$_SESSION[Paid2]=0;
$_SESSION[Paid3]=0;
$_SESSION[serviceReduction]=0;
?>
<style>
td {white-space:pre}
table { border-collapse:collapse}
</style>
<?
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
$q="SELECT * FROM ps_packets where packet_id = '$packet'";
$r=@mysql_query($q) or die("Query 1<br>".mysql_error());
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
$packetTotal = $totalService + $mailFee + $filingCost - $d[serviceReduction];


$_SESSION[Paid1] = $_SESSION[Paid1] + $d[client_paid];
$_SESSION[Paid2] = $_SESSION[Paid2] + $d[client_paida];
$_SESSION[Paid3] = $_SESSION[Paid3] + $d[client_paidb];
$_SESSION[serviceReduction] = $_SESSION[serviceReduction] + $d[serviceReduction];

$clear = $packetTotal - $d[client_paid] - $d[client_paida] - $d[client_paidb] + $d[serviceReduction]; 

$csvData = "<tr><td><a href='http://mdwestserve.com/AC/ps_pay.php?id=$packet' target='_Blank'>".$packet."</a></td><td>".$d['client_file']."</td><td>".$d['date_received']."</td><td>".$totalService."</td><td>".$mailFee."</td><td>".$filingCost."</td><td>".$packetTotal."</td><td>".$d['service_status']."</td><td>".$d['client_paid']."</td><td>".$d['client_check']."</td><td>".$d['client_paida']."</td><td>".$d['client_checka']."</td><td>".$d['client_paidb']."</td><td>".$d['client_checkb']."</td><td>".$clear."</td><td>$d[serviceReduction]</td></tr>";
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
// header
$data = "<table border='1'><tr><td>Packet Number</td><td>File Number</td><td>Date Received</td><td>Service</td><td>Mailing</td><td>Filing</td><td>Packet Total</td><td>Service Result</td><td>Check 1</td><td>Number 1</td><td> Check 2</td><td> Number 2</td><td> Check 3</td><td> Number 3</td><td> Clear </td><td>Reduced</td></tr>";
// items
$q="select packet_id, service_status from ps_packets where
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
$totalBill = $_SESSION[Service] + $_SESSION[Mail] + $_SESSION[Filing] - $_SESSION[serviceReduction];
$data .= "<tr><td> </td><td> </td><td> </td><td>".$_SESSION[Service]."</td><td>".$_SESSION[Mail]."</td><td>".$_SESSION[Filing]."</td><td>".$totalBill."</td><td> </td><td>$_SESSION[Paid1]</td><td> </td><td> $_SESSION[Paid2]</td><td> </td><td> $_SESSION[Paid3]</td><td> </td><td>  </td><td> </td></tr>";

$totalPaid = $_SESSION[Paid1] + $_SESSION[Paid2] + $_SESSION[Paid3] + $_SESSION[serviceReduction];
$due = $totalBill - $totalPaid;
$data .= "<tr><td>Total Due</td><td>$due </td><td> </td><td>Total Reductions</td><td> $_SESSION[serviceReduction]</td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td></td><td></td><td></td><td></td><td></td></tr></table> \n";


echo $data;
}else{
echo "This link has expired.";
}
?>