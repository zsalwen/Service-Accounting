<?
include 'functions.php';
include 'security.php';
dbConnect();
$id = $_GET[server];
$user=$_COOKIE['userdata']['user_id'];

function num_words($number){
    $ones=array('','ONE','TWO','THREE','FOUR','FIVE','SIX','SEVEN','EIGHT','NINE');
    $tens=array('','ELEVEN','TWELVE','THIRTEEN','FOURTEEN','FIFTEEN','SIXTEEN','SEVENTEEN','EIGHTEEN','NINETEEN');
    $tens2=array('','TEN','TWENTY','THIRTY','FORTY','FIFTY','SIXTY','SEVENTY','EIGHTY','NINETY');
    $tens3=array('','hundred','thousand','million','billion','trillion');

    $numlenght=strlen($number);
    $numarray=str_split($number,1);


    if ($number<10) {
        return $ones[intval($number)];
    }

    //tens
    if ($numlenght==2&&$number<20 && $numarray[1]<>0) {
        return $tens[$number-10];
    }


    if ($numlenght==2&&$number<20 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]<>0) {
        return $tens2[$numarray[0]]." ".$ones[$numarray[1]];
    }


    //hundreds
    if ($numlenght==3) {
        $x=$numarray[1].$numarray[2];
        return $ones[$numarray[0]]." HUNDRED ".tens($x);
    }

    //THOUSANDS
    if ($numlenght==4){
        $y=$numarray[1].$numarray[2].$numarray[3];
        $z=$numarray[2].$numarray[3];
        if (intval($y)>99){
            return $ones[$numarray[0]]." THOUSAND ".hundreds($y);
        }else{
            return $ones[$numarray[0]]." THOUSAND ". tens($z);
        }
    }


    //tensthousand

    if ($numlenght==5){
        $v=$numarray[0].$numarray[1];
        $y=$numarray[2].$numarray[3].$numarray[4];
        $z=$numarray[3].$numarray[4];

        if (intval($y)>99){
            return tens($v)." THOUSAND ".hundreds($y);
        }else{
            return tens($v)." THOUSAND ". tens($z);
        }
    }
}

function tens($number) {
    $ones=array('','ONE','TWO','THREE','FOUR','FIVE','SIX','SEVEN','EIGHT','NINE');
    $tens=array('','ELEVEN','TWELVE','THIRTEEN','FOURTEEN','FIFTEEN','SIXTEEN','SEVENTEEN','EIGHTEEN','NINETEEN');
    $tens2=array('','TEN','TWENTY','THIRTY','FORTY','FIFTY','SIXTY','SEVENTY','EIGHTY','NINETY');
    $tens3=array('','hundred','thousand','million','billion','trillion');

    $numlenght=strlen($number);
    $numarray=str_split($number,1);

    if ($number<10){
        return $ones[intval($number)];
    }
    if ($numlenght==2&&$number<20 && $numarray[1]<>0){
        return $tens[$number-10];
    }

    if ($numlenght==2&&$number<20 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]<>0){
        return $tens2[$numarray[0]]." ".$ones[$numarray[1]];
    }
}

function hundreds($number) {
    $ones=array('','ONE','TWO','THREE','FOUR','FIVE','SIX','SEVEN','EIGHT','NINE');
    $tens=array('','ELEVEN','TWELVE','THIRTEEN','FOURTEEN','FIFTEEN','SIXTEEN','SEVENTEEN','EIGHTEEN','NINETEEN');
    $tens2=array('','TEN','TWENTY','THIRTY','FORTY','FIFTY','SIXTY','SEVENTY','EIGHTY','NINETY');
    $tens3=array('','hundred','thousand','million','billion','trillion');

    $numlenght=strlen($number);
    $numarray=str_split($number,1);

    if ($number<10){
        return $ones[intval($number)];
    }

    //tens

    if ($numlenght==2&&$number<20 && $numarray[1]<>0){
        return $tens[$number-10];
    }
    if ($numlenght==2&&$number<20 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]==0){
        return $tens2[$numarray[0]];
    }

    if ($numlenght==2&&$number>19 && $numarray[1]<>0){
        return $tens2[$numarray[0]]." ".$ones[$numarray[1]];
    }

    //hundreds
    if ($numlenght==3){
        $x=$numarray[1].$numarray[2];
        return $ones[$numarray[0]]." HUNDRED ".tens($x);
    }
}


function packetCost($id){
	$d=mysql_fetch_array(@mysql_query("SELECT * from ps_packets where packet_id = '$id'"), MYSQL_ASSOC);
		$total = 0;
		if ($d[name1]){ $total = $total+$d[contractor_rate];
			$q1="SELECT 'history_id' FROM ps_history where packet_id='$id' AND defendant_id='1' AND serverID='$d[server_id]' AND action_type='Posted Papers'";
			$r1=@mysql_query($q1) or die("Query: $q1<br>".mysql_error());
			$d1=mysql_fetch_array($r1, MYSQL_ASSOC);
			if ($d[address1a] && isset($d1[history_id]) && ($d['server_id'] == $d['server_ida'])){ $total = $total+$d[contractor_rate]; }
			if ($d[address1b] && ($d['server_id'] == $d['server_idb'])){ $total = $total+$d[contractor_rate]; }
		}
		if ($d[name2]){ $total = $total+$d[contractor_rate];
			$q2="SELECT 'history_id' FROM ps_history where packet_id='$id' AND defendant_id='2' AND serverID='$d[server_id]' AND action_type='Posted Papers'";
			$r2=@mysql_query($q2) or die("Query: $q2<br>".mysql_error());
			$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
			if ($d[address2a] && isset($d2[history_id]) && ($d['server_id'] == $d['server_ida'])){ $total = $total+$d[contractor_rate]; }
			if ($d[address2b] && ($d['server_id'] == $d['server_idb'])){ $total = $total+$d[contractor_rate]; }
		}
		if ($d[name3]){ $total = $total+$d[contractor_rate];
			$q3="SELECT 'history_id' FROM ps_history where packet_id='$id' AND defendant_id='3' AND serverID='$d[server_id]' AND action_type='Posted Papers'";
			$r3=@mysql_query($q3) or die("Query: $q3<br>".mysql_error());
			$d3=mysql_fetch_array($r3, MYSQL_ASSOC);
			if ($d[address3a] && isset($d3[history_id]) && ($d['server_id'] == $d['server_ida'])){ $total = $total+$d[contractor_rate]; }
			if ($d[address3b] && ($d['server_id'] == $d['server_idb'])){ $total = $total+$d[contractor_rate]; }
		}
		if ($d[name4]){ $total = $total+$d[contractor_rate];
			$q4="SELECT 'history_id' FROM ps_history where packet_id='$id' AND defendant_id='4' AND serverID='$d[server_id]' AND action_type='Posted Papers'";
			$r4=@mysql_query($q4) or die("Query: $q4<br>".mysql_error());
			$d4=mysql_fetch_array($r4, MYSQL_ASSOC);
			if ($d[address4a] && isset($q4[packet_id]) && ($d['server_id'] == $d['server_ida'])){ $total = $total+$d[contractor_rate]; }
			if ($d[address4b] && ($d['server_id'] == $d['server_idb'])){ $total = $total+$d[contractor_rate]; }
		}
	return $total;
}
////////// end functions
/*
if ($_POST[check]){
$reg='';
$q="SELECT * FROM ps_packets WHERE process_status = 'INVOICED' AND server_id = '$id'";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
	$svc =packetCost($d[packet_id]); 
	$pay = $svc - $d[print_cost]; 
	$reg = $reg + $pay; 
	@mysql_query("update ps_packets set contractor_paid = '$pay', process_status = 'AWAITING PAYMENT', contractor_check = '$_POST[check]' where packet_id = '$d[packet_id]'") or die(mysql_error());
	$result .= "Paying packet $d[packet_id] with check number <strong>$_POST[check]</strong>. Rate is $d[contractor_rate]. Service is $svc. Printing is $d[print_cost]. Amount Paid is <strong>$pay</strong>, status is now '<strong>AWAITING PAYMENT</strong>' from client.<br>";
}
@mysql_query("insert into ac_register (trans, accountID, codeID, userID, status, detail, amount, entered, checkNumber) values ('WITHDRAW', '5', '561', '$user', 'ENTERED', '$_POST[regpayto]', '$reg', NOW(), '$_POST[check]')") or die(mysql_error());

	$result .= "Payment Complete and Entered into Register";


}
*/

$payDate = date('m/d/Y');

// ok let's get auth on server_id

$q="SELECT * FROM ps_users where id = '$id'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
if ($d[company]){$payTo = $d[company];}else{$payTo = $d[name];}



$q="SELECT * FROM ps_packets where server_id <> '' and contractor_check = '' AND payAuth > '0' ORDER BY packet_id";
$r=@mysql_query($q);
$details = "<table width='100%' cellspacing='0'><tr><td>ID</td><td>Received</td><td>Service</td><td>Printing</td><td style='padding-left:20px'>Balance</td></tr>";
$svc=0;
$ptr=0;
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
$svc = $svc + packetCost($d[packet_id]);
if ($d[contractor_rate] == ''){
}else{
	$ptr = $ptr + $d[print_cost];
}

$details .= "<tr><td>$d[packet_id]</td><td>$d[date_received]</td><td>$".number_format(packetCost($d[packet_id]),2)."</td>";
if ($d[contractor_rate] == ''){
	$details .= "<td>-$".number_format('0',2)."</td>";
}else{
	$details .= "<td>-$".number_format($d[print_cost],2)."</td>";
}
if ($d[contractor_rate] == ''){
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]),2)."</td></tr>";
}else{
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]) - $d[print_cost],2)."</td></tr>";
}
}








$q="SELECT * FROM ps_packets where server_ida <> '' and contractor_checka = '' AND payAuth = '1' ORDER BY packet_id";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
$svc = $svc + packetCost($d[packet_id]);
if ($d[contractor_ratea] == ''){
}else{
	$ptr = $ptr + $d[print_cost];
}

$details .= "<tr><td>$d[packet_id]a</td><td>$d[date_received]</td><td>$".number_format(packetCost($d[packet_id]),2)."</td>";
if ($d[contractor_rate] == ''){
	$details .= "<td>-$".number_format('0',2)."</td>";
}else{
	$details .= "<td>-$".number_format($d[print_cost],2)."</td>";
}
if ($d[contractor_rate] == ''){
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]),2)."</td></tr>";
}else{
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]) - $d[print_cost],2)."</td></tr>";
}
}


$q="SELECT * FROM ps_packets where server_idb <> '' and contractor_checkb = '' AND payAuth = '1' ORDER BY packet_id";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
$svc = $svc + packetCost($d[packet_id]);
if ($d[contractor_rateb] == ''){
}else{
	$ptr = $ptr + $d[print_cost];
}

$details .= "<tr><td>$d[packet_id]b</td><td>$d[date_received]</td><td>$".number_format(packetCost($d[packet_id]),2)."</td>";
if ($d[contractor_rate] == ''){
	$details .= "<td>-$".number_format('0',2)."</td>";
}else{
	$details .= "<td>-$".number_format($d[print_cost],2)."</td>";
}
if ($d[contractor_rate] == ''){
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]),2)."</td></tr>";
}else{
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]) - $d[print_cost],2)."</td></tr>";
}
}





$q="SELECT * FROM ps_packets where server_idc <> '' and contractor_checkc = '' AND payAuth = '1' ORDER BY packet_id";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
$svc = $svc + packetCost($d[packet_id]);
if ($d[contractor_ratec] == ''){
}else{
	$ptr = $ptr + $d[print_cost];
}

$details .= "<tr><td>$d[packet_id]c</td><td>$d[date_received]</td><td>$".number_format(packetCost($d[packet_id]),2)."</td>";
if ($d[contractor_rate] == ''){
	$details .= "<td>-$".number_format('0',2)."</td>";
}else{
	$details .= "<td>-$".number_format($d[print_cost],2)."</td>";
}
if ($d[contractor_rate] == ''){
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]),2)."</td></tr>";
}else{
	$details .= "<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]) - $d[print_cost],2)."</td></tr>";
}
}






$details .= "<tr><td style='border-top:solid;'>Total</td><td style='border-top:solid;'> </td><td style='border-top:solid;'>$".number_format($svc,2)."</td><td style='border-top:solid;'>-$".number_format($ptr,2)."</td><td style='border-top:solid; padding-left:20px'>$".number_format(($svc-$ptr),2)."</td></tr></table>";


$payAmount = number_format(($svc-$ptr),2);




$split = explode('.',$payAmount);
$payDollars = str_replace(',','',$split[0]);
$payCents = $split[1];
?>
<table align="center" width="800px">
	<tr>
		<td height="78px" valign="bottom" width="700px"></td>
		<td height="78px" valign="bottom" width="100px"><?=$payDate?></td>
    </tr>
	<tr>
		<td height="50px" valign="bottom" style="padding-left:70px; padding-bottom:5px;"><?=$payTo?></td>
		<td height="50px" valign="bottom"><?=$payAmount?></td>
    </tr>
	<tr>
		<td height="100px" valign="top" colspan="2" style="padding-left:20px; padding-top:10px"><?=num_words($payDollars)?> and <?=$payCents?>/100</td>
    </tr>
	<tr>
		<td height="45px" valign="bottom" colspan="2" style="padding-left:50px; padding-bottom:20px;">PROCESS SERVING</td>
    </tr>
	<tr>
    	<td colspan="2" valign="top" style="padding-top:60px;"><?=$details?></td>
 	</tr>       
</table>        
<style type="text/css">
    @media print {
      .noprint { display: none; }
    }
  </style> 
<div class='noprint'>
<form method="post">Enter Check Number: <input name="check"><input type="hidden" name="regpayto" value="<?=$payTo?>" /><input type="submit" value="Mark Paid"></form>
<a href="envelope.php?id=<?=$_GET[id]?>">Envelope</a>

<hr />
Results:<br />
<?=$result?>

</div>

