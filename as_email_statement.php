<?php
include 'functions.php';
dbConnect();
function cleanNumber($num){
	$num = str_replace(',','',$num);
	$num = number_format($num,2, '.', '');
	return $num;
}
function cleanWord($str){
	$str = str_replace(',','',$str);
	return $str;
}
$month = $_POST[month];
$year = $_POST[year];
$date = $year."-".$month;
$client = $_POST[client];
$qm = "SELECT * FROM attorneys WHERE attorneys_id = '$client'";
$rm = @mysql_query($qm);
$dm = mysql_fetch_array($rm, MYSQL_ASSOC);
$_SESSION[lines] = 0;
$_SESSION[auction_fee] = 0;
$_SESSION[ad_cost] = 0;
$_SESSION[total] = 0;
$_SESSION[paid] = 0;
$_SESSION[adjusted] = 0;
$_SESSION[due] = 0;
$_SESSION[dwest] = 0;
$_SESSION[dburson] = 0;
$csv = "Invoice, County, Date, Time, File, Address, Auction Fee, Ad Cost, Due, Paid, Adjusted, Balance, Chk 1, Chk 2, Chk3
";
$q = "SELECT * FROM schedule_items, attorneys  WHERE attorneys.attorneys_id = schedule_items.attorneys_id AND schedule_items.attorneys_id = '$client' AND schedule_items.sale_date like '$date%' AND ad_deposit < '1' ORDER BY county, file";		
$r = @mysql_query ($q) or die(mysql_error());
while($d = mysql_fetch_array($r, MYSQL_ASSOC)){
// let's make a friendly date
$d2 = explode('-',$d[sale_date]);
$d2 = $d2[1]."/".$d2[2];
$cost6='';
$total='';
$due='';
$cost6 = cleanNumber($d[ad_cost]) + cleanNumber($d[ad_cost2]) + cleanNumber($d[ad_cost3]);
$total = cleanNumber($d[auction_fee]) + $cost6;
$paid4 = cleanNumber($d[paid]) + cleanNumber($d[paid2]) + cleanNumber($d[paid3]);
		$due = (cleanNumber($d[auction_fee]) + $cost6) - $paid4 - cleanNumber($d[adjusted]);
		if ($due > 1.00 ){ 
			$due_f = $due*-1.00; 
			$_SESSION[dwest] = $_SESSION[dwest] + $due;
		}
		if ($due < 1.00 ){ 
			$due_f = $due*-1.00;  
			$_SESSION[dburson] = $_SESSION[dburson] + $due;
		}
		if ($due == 0.00 || $due == 0){ 
			$due_f = $due;
		}
$csv .= "$d[schedule_id], $d[county], $d2, $d[sale_time], $d[file], ".cleanWord($d[address1]).", $d[auction_fee], $cost6, $total, $paid4, $data[adjusted], ".cleanNumber($due_f).", $d[check_number], $d[check_number2], $d[check_number3]
";
	$_SESSION[lines] = $_SESSION[lines] + 1;
	$_SESSION[auction_fee] = $_SESSION[auction_fee] + $d[auction_fee];
	$_SESSION[ad_cost] = $_SESSION[ad_cost] + $cost6;
	$_SESSION[total] = $_SESSION[total] + $total;
	$_SESSION[paid] = $_SESSION[paid] + $paid4;
	$_SESSION[adjusted] = $_SESSION[adjusted] + $d[adjusted];
	$_SESSION[due] = $_SESSION[due] + $due;
}
$dder = $_SESSION[auction_fee]+$_SESSION[ad_cost];
$csv .= " , , , , , $_SESSION[lines] Auctions, $_SESSION[auction_fee], $_SESSION[ad_cost], $dder, $_SESSION[paid], $_SESSION[adjusted], , , , 
";
$csv .= " , , , , , $_SESSION[dwest] due to Harvey West, , , , , , , , ,  
";
$csv .= " , , , , , $_SESSION[dburson] due back to Client, , , , , , , , ,  
";
$dir = '/data/auction/statements/'.$dm["display_name"];
if (!file_exists($dir)){
	mkdir ($dir,0777);
}
$filename = "Sent-".date('m-d-Y').".CSV";
$fname = $dir.'/'.$filename;
$fp = fopen($fname, "wb");
fwrite($fp, $csv);
fclose($fp);
$addy = $dm[statement_to];
$addy = explode(',',$addy);
$cc = count($addy);
$to = $addy[0];
$cnt1 = 0;
$cnt2 = 1;
$subject = "Auction Statement - $month/$year";
$headers  = "MIME-Version: 1.0 \n";
$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
while ($cnt2 < $cc){
	$cnt1++;
	$cnt2++;
	$headers .= "Cc: ".$addy[$cnt1]." \n";
}
$headers .= "Cc: Ceil West <cwest@hwestauctions.com> \n";
$headers .= "Cc: HWA Archive <hwa.archive@gmail.com> \n";
$headers .= "Cc: Patrick <patrick@mdwestserve.com> \n";
$headers .= "From: ".$_COOKIE[userdata][name]." <".$_COOKIE[userdata][email]."> \n";
$headers .= "Reply-To: Ceil West <cwest@hwestauctions.com>";
// ok we need to be able to pull the file from a different location
$uri = "http://mdwestserve.com/statements/".$dm["display_name"]."/".$filename;
$message = "<a href='$uri'>$_POST[notes]<br>[click here to download statement]<br>$uri";
mail( $to, $subject, $message, $headers );
?>
<script>
alert('Link to <?=$uri?> Sent');
self.close();</script>


