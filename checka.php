<?
include 'functions.php';
dbConnect();
$id = $_GET[id];
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
	$d=mysql_fetch_array(@mysql_query("SELECT * from ps_packets, ps_pay where packet_id = '$id' AND process_status <> 'CANCELLED' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'"), MYSQL_ASSOC);
		$total = 0;
		if ($d[name1]){ $total = $total + $d[contractor_ratea]; }
		if ($d[name2]){ $total = $total + $d[contractor_ratea]; }
		if ($d[name3]){ $total = $total + $d[contractor_ratea]; }
		if ($d[name4]){ $total = $total + $d[contractor_ratea]; }
	return $total;
}
////////// end functions
if ($_POST[check]){

$q="SELECT * FROM ps_packets, ps_pay WHERE ps_pay.contractor_ratea <> '' AND (process_status = 'INVOICED' OR (process_status = 'AWAITING PAYMENT' AND ps_pay.contractor_paida='')) AND server_ida = '$id' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'";
$r=@mysql_query($q);
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
	$svc =packetCost($d[packet_id]); 
	$pay = $svc;
	$q="update ps_pay set contractor_paida = '$pay', contractor_checka = '$_POST[check]' where packetID = '$d[packet_id]' AND product='OTD'";
	@mysql_query($q) or die(mysql_error());
	$result .= "Paying packet $d[packet_id] with check number <strong>$_POST[check]</strong>. Rate is $d[contractor_ratea]. Service is $svc.  Amount Paid is <strong>$pay</strong>.<hr>$q";
}
@mysql_query("insert into ac_register (trans, accountID, codeID, userID, status, detail, amount, entered, checkNumber) values ('WITHDRAW', '5', '561', '$user', 'ENTERED', '$_POST[regpayto]', '$pay', NOW(), '$_POST[check]')") or die(mysql_error());

	$result .= "Payment Complete and Entered into Register";


}


$payDate = date('m/d/Y');



$q="SELECT * FROM ps_users where id = '$id'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
if ($d[company]){$payTo = $d[company];}else{$payTo = $d[name];}

$q="SELECT * FROM ps_packets, ps_pay where server_ida = '$id' AND process_status = 'INVOICED' and ps_pay.contractor_ratea <> '' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'"; // 
$r=@mysql_query($q);





$details = "<table width='100%' cellspacing='0'><tr><td>ID</td><td>Service</td><td style='padding-left:20px'>Balance</td></tr>";
$svc=0;
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){
$svc = $svc + packetCost($d[packet_id]);


$details .= "<tr><td>$d[packet_id]</td><td>$".number_format(packetCost($d[packet_id]),2)."</td>
<td style='border-left:solid 1px; padding-left:20px'>$".number_format(packetCost($d[packet_id]),2)."</td></tr>";
}
$details .= "<tr><td style='border-top:solid;'>Total</td><td style='border-top:solid;'>$".number_format($svc,2)."</td><td style='border-top:solid; padding-left:20px'>$".number_format($svc,2)."</td></tr></table>";


$payAmount = number_format(($svc-$ptr),2);




$split = explode('.',$payAmount);
$payDollars = $split[0];
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

