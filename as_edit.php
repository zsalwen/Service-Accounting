<? 
session_start();
$user_id = $_COOKIE[userdata][user_id];
?>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
<!--
function automation() {
  window.opener.location.href = window.opener.location.href;
  if (window.opener.progressWindow)
		
 {
    window.opener.progressWindow.close()
  }
  window.close();
}
</script>
<body>
<title>Edit Main Auction Information</title>
<? 
include 'functions.php';
echo db_connect('delta.mdwestserve.com','intranet','','');
if ($_POST['submit']){
//------------------------------( Validate Form )----------------------------
// validate date
$date = $_POST['date']; // now mmddyyyy
$date = str_split($date); //- convert to array mm/dd/yyyy
$date = $date[0].$date[1]."/".$date[2].$date[3]."/".$date[4].$date[5].$date[6].$date[7];
$split_date = explode('/', $date);
if (count($split_date) != 3){ $dateerror .= "-not enough parts in date-"; }
if (strlen($split_date[0]) != 2){ $dateerror .= "-month missing digit-"; }
if ($split_date[0] > 12){ $dateerror .= "-month to high-"; }
if (strlen($split_date[1]) != 2){ $dateerror .= "-day missing digit-"; }
if ($split_date[1] > 31){ $dateerror .= "-day to high-"; }
if (strlen($split_date[2]) != 4){ $dateerror .= "-year missing digit-"; }
if ($split_date[2] != date('Y') && $split_date[2] != (date('Y')+1)){ $dateerror .= "-check year-"; }
$finaldate = $split_date[2].'-'.$split_date[0].'-'.$split_date[1];
//pop($finaldate);

// single line to format invoice date =)
$invoice_date = dbin($_POST[invoice_date][year],$_POST[invoice_date][month],$_POST[invoice_date][day]);





// validate time
$time = $_POST['time'];
$time = str_split($time); //- convert to array mm/dd/yyyy
$time = $time[0].$time[1].":".$time[2].$time[3]." ".$time[4].$time[5];
$split_time = explode(':', $time);
if (count($split_time) != 2){ $timeerror .= "-not enough parts in hour/minutes-"; }
if (strlen($split_time[0]) != 2){ $timeerror .= "-hour missing digit-"; }
if ($split_time[0] > 12){ $timeerror .= "-hour to high-"; }
if (strlen(trim($split_time[1])) != 2){ $timeerror .= "-minute missing digit-"; }
if ($split_time[1] > 59){ $timeerror .= "-minute to high-"; }
$finaltime = $split_time[0].':'.$split_time[1].$_POST[daypart];
// make a sort time (24 hour)
if ($_POST[daypart] == "PM"){
	if ($split_time[0] == 12){
		$hour24 = "12";
	}else{
		$hour24 = $split_time[0] + 12;
	}
} else {
	if ($split_time[0] == 12){
		$hour24 = "00";
	}else{
		$hour24 = $split_time[0];
	}
} 
$sort_time = $hour24.':'.$split_time[1];
// make sure we have an address
if ($_POST[address1]){ $address = strtoupper($_POST[address1]); } else { $addresserror = "missing address";}
// make sure we have a deposit
if ($_POST[deposit]){ $deposit = $_POST[deposit]; } else { $depositerror = "missing deposit";}
// make sure we have a file number
if ($_POST['file']){ $file = strtoupper($_POST['file']); } else { $fileerror = "missing file number";}
// check zip code
$zip = $_POST[zip];
//if (strlen($zip) != 5){ $ziperror .= "-zip code missing / extra digit-"; }
// make sure we have the city
//if ($_POST[city]){ $city = $_POST[city]; } else { $cityerror = "missing city";}
$city = strtoupper($_POST[city]);
// make sure we have the paper
//if ($_POST[paper]){ $paper = $_POST[paper]; } else { $papererror = "missing paper";}
$paper = strtoupper($_POST[paper]);
// make sure we have the notary_exp
//if ($_POST[notary_exp]){ $notary_exp = $_POST[notary_exp]; } else { $notaryerror = "missing notary";}
$notary_exp = $_POST[notary_exp];
// make sure we have the last_fault
//if ($_POST[last_fault]){ $last_fault = $_POST[last_fault]; } else { $last_faulterror = "Missing Client Last Name";}
$last_fault = strtoupper($_POST[last_fault]);
// make sure we have the legal_fault
//if ($_POST[legal_fault]){ $legal_fault = $_POST[legal_fault]; } else { $legal_faulterror = "Missing Client Full Name";}
$legal_fault = strtoupper($_POST[legal_fault]);
// make sure we have the sub_trust
//if ($_POST[sub_trust]){ $sub_trust = $_POST[sub_trust]; } else { $sub_trusterror = "Missing Substute Trustees";}
$sub_trust = strtoupper($_POST[sub_trust]);
// convert some post data
$state = strtoupper($_POST[state]);
$status = strtoupper($_POST[status]);
$prefix = strtoupper($_POST[prefix]);

$ad_cost = $_POST[ad_cost];
$private = $_POST['private'];
$auction_fee = $_POST[auction_fee];
$commission = $_POST[commission];
$county = $_POST[county];
$pub_dates = $_POST[pub_dates];
$notes = addslashes($_POST[notes]);
$court = county2court($_POST[county]);
// submit data

log_action($_COOKIE[userdata][user_id],"Data updated for $address on $finaldate at $finaltime");
$id = $_POST[id];
$attorney = $_POST[attorney];
	
$dot = strtoupper(addslashes($_POST[dot]));	
	
	$q1 = "UPDATE schedule_items SET 
								dot='$dot',
								inst='$_POST[inst]',
								pl90='$_POST[pl90]',
								web_pdf='$_POST[web_pdf]',
								dot_date='$_POST[dot_date]',
								dot_rate='$_POST[dot_rate]',
								dot_from='$_POST[dot_from]',
								dot_to='$_POST[dot_to]',
								gr_date='$_POST[gr_date]',
								gr_month1='$_POST[gr_month1]',
								gr_month2='$_POST[gr_month2]',
								dot_position='$_POST[dot_position]',
								liber='$_POST[liber]',
								folio='$_POST[folio]',
								featured='$_POST[featured]',
								auctioneer='$_POST[auctioneer]', 
								auctioneer2='$_POST[auctioneer2]', 
								trust_type = '$_POST[trust_type]', 
								ground_rent = '$_POST[ground_rent]', 
								location_id='1', 
								private='$private', 
								commission='$commission',  
								invoice_date='$invoice_date', 
								attorneys_id='$attorney', 
								last_fault='$last_fault', 
								legal_fault='$legal_fault', 
								sub_trust='$sub_trust', 
								notary_exp='$notary_exp', 
								county='$county', 
								sort_time='$sort_time', 
								state='$state', zip='$zip', 
								update_date=NOW(), 
								sale_date='$finaldate', 
								sale_time='$finaltime', 
								item_status='$status', 
								address1='$address', 
								city='$city', 
								court='$court', 
								file='$file', 
								updated_id='$user_id', 
								deposit='$deposit' 
									WHERE schedule_id = '$id'";
	

	
	$r1 = @mysql_query ($q1) or die("q1: ".mysql_error());
		addNote($id,$_COOKIE[userdata][name].': Auction Information Updated '.date('m/d/Y'));
	echo "<script>automation();</script>";
} else {
log_action($_COOKIE[userdata][user_id],'Updating Data');
}
?>

<form method="POST" action="edit.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=$_GET[id]?>" />
<?
if (!$_POST[submit]){
	$q3 = "SELECT * FROM schedule_items, attorneys WHERE schedule_items.attorneys_id = attorneys.attorneys_id AND schedule_items.schedule_id = '$_GET[id]'";		
	$r3 = @mysql_query ($q3) or die(mysql_error());
	$data3 = mysql_fetch_array($r3, MYSQL_ASSOC);

$date = explode('-',$data3[sale_date]);
$date = $date[1].$date[2].$date[0];	


$time = explode(' ',$data3[sale_time]);	
$time1 = str_replace(':','',$time[0]);

// ok let's split the invoice date from the db
$invoice_date = dbout($data3[invoice_date]);

	

}
setLocation("Editing $data3[address1] ($data3[sale_date])");

?>
<fieldset>
	<legend>Edit Main Auction Information</legend>
	Welcome to the new look of the data entry system. Only fill out the ground rent if it is not fee simple. Error codes have 
    been removed so make sure you enter all the right information in the right format! Date format is MMDDYYYY. 
    Time format is HHMM. Deed of trust information is to be entered exactly as you want it displayed in the ad. Once you have 
    completed editing all the data you can click <input class="green" type="submit" name="submit" value="Update Information"/>.
</fieldset>


<table align="center" width="100%">
	<tr>
    	<td valign="top" width="31%">
            <fieldset>
                <legend>Attorney Information</legend>
			Attorney:<br>
			<select name="attorney"><option value="<? if ($_POST[attorney]){ echo $_POST[attorney]; }else{ echo $data3[attorneys_id] ;} ?>"><? if ($_POST[attorney]){ 
		
		// we to query the attorney's name
		
		$q7 = "SELECT * FROM attorneys where attorneys_id ='$_POST[attorney]'";		
		$r7 = @mysql_query ($q7) or die(mysql_error());
		$data7 = mysql_fetch_array($r7, MYSQL_ASSOC); // we need to only display if sales exist
		echo $data7[display_name];
		
		 }else{ echo $data3[display_name] ;}?></option>
		<?
		$q8 = "SELECT * FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	echo "<option value='$data8[attorneys_id]'>$data8[display_name]</option>";
		}
		?>
		</select><br>
        	File #<br>
			<input name="file"  value="<? if ($_POST['file']){ echo strtoupper($_POST['file']);}else{ echo $data3['file'];}?>"/><br>
			Bid Deposit<br>
			$<input name="deposit" size="5" value="<? if ($_POST[deposit]){ echo $_POST[deposit];}else{echo $data3[deposit] ;}?>" /> <strong>K</strong><br>
			Client Last Name<br>
			<input size="50" <? if ($last_faulterror){echo "class='error'";} ?> name="last_fault" value="<? if($_POST[last_fault]){echo strtoupper($_POST[last_fault]);}else{echo $data3[last_fault];}?>"  />
			Client Full Name<br>
			<input size="50" <? if ($legal_faulterror){echo "class='error'";} ?> name="legal_fault" value="<? if($_POST[legal_fault]){echo strtoupper($_POST[legal_fault]);}else{echo $data3[legal_fault];}?>"/>
			Additional Substitute Trustees<br>
			<input size="50" <? if ($sub_trusterror){echo "class='error'";} ?> name="sub_trust" value="<? if($_POST[sub_trust]){echo strtoupper($_POST[sub_trust]);}else{echo $data3[sub_trust];}?>"/>
			Type:<br>
			<input name="trust_type" size="50" value="<? if($_POST[trust_type]){echo strtoupper($_POST[trust_type]);}else{echo $data3[trust_type];}?>"/>
			SUBSTITUTE TRUSTEES | SUBSTITUTE TRUSTEE | MORTGAGE ASSIGNEE<br>
            </fieldset>    
		</td>
     	<td valign="top" width="31%">
            <fieldset>
                <legend>Deed of Trust</legend>
			From:<br>
			<input size="50" name="dot_from" value="<?=$data3[dot_from]?>" /><br>
            To:<br>
			<input size="50" name="dot_to" value="<?=$data3[dot_to]?>" /><br>
			Legal Description:<br>
			<textarea name="dot" cols="42" rows="8"><?=stripslashes($data3[dot]);?></textarea><br>
			Mortgage Position:<br>
			<select name="dot_position"><option><?=$data3[dot_position]?></option><option>1</option><option>2</option></select><br>
			DOT Recorded:<br>
			<input name="dot_date" value="<?=$data3[dot_date]?>" /><br>
			PL 90 Recorded:<br>
			<input name="pl90" value="<?=$data3[pl90]?>" /><br>
			Instrument Number:<br>
			<input name="inst" value="<?=$data3[inst]?>" /><br>
			Intrest Rate:<br>
			<input size="10" name="dot_rate" value="<?=$data3[dot_rate]?>" />%<br>
			Liber or Lot<br>
			<input name="liber" value="<?=$data3[liber]?>" /><br>
			Folio or Square<br>
			<input name="folio" value="<?=$data3[folio]?>" /><br>
            </fieldset> 
		</td>
     	<td valign="top" width="38%">
            <fieldset>
                <legend>Property Information</legend>
			First Line of Address:<br>
			<input size="50" name="address1" value="<? if ($_POST[address1]){ echo strtoupper($_POST[address1]); }else{ echo $data3[address1];}?>"/><br>
			City, State, Zip Code, County:<br>
			<input size="50"  name="city"  value="<? if ($_POST[city]){ echo strtoupper($_POST[city]); }else{ echo $data3[city];}?>"/><input name="zip" maxlength="5" size="10"  value="<? if($_POST[zip]){ echo $_POST[zip]; }else{ echo $data3[zip] ;}?>"/><select name="state"><option><? if ($_POST[state]){ echo $_POST[state];}else{echo $data3[state];}?></option><option>MD</option><option>DC</option></select><select name="county">
			<option><? if ($_POST[county]){ echo strtoupper($_POST[county]); }else{ echo $data3[county] ;}?></option>
			<option>ALLEGANY</option>
			<option>ANNE ARUNDEL</option>
			<option>BALTIMORE</option>
			<option>BALTIMORE CITY</option>
			<option>CALVERT</option>
			<option>CAROLINE</option>
			<option>CARROLL</option>
			<option>CECIL</option>
			<option>CHARLES</option>
			<option>DORCHESTER</option>
			<option>FREDERICK</option>
			<option>GARRETT</option>
			<option>HARFORD</option>
			<option>HOWARD</option>
			<option>KENT</option>
			<option>MONTGOMERY</option>
			<option>PRINCE GEORGES</option>
			<option>QUEEN ANNES</option>
			<option>ST MARYS</option>
			<option>SOMERSET</option>
			<option>TALBOT</option>
			<option>WASHINGTON</option>
			<option>WASHINGTON D.C.</option>
			<option>WICOMICO</option>
			<option>WORCESTER</option>
			<option>ON PREMISES</option>
		</select>            
        </fieldset> 
			<fieldset>
				<legend>Auction Information</legend>
				Date and Time of Sale:<br>
                <input <? if ($dateerror){echo "class='error'";} ?> name="date" size="8" maxlength="8" value="<? if($_POST['date']){ echo $_POST['date']; }else{ echo $date; } ?>" > @ <input <? if ($timeerror){echo "class='error'";} ?>  name="time" size="3" maxlength="4" value="<? if($_POST['time']){ echo $_POST['time']; }else{ echo $time1;}?>" /><select name="daypart"><? if ($_POST[daypart]){?><option><?=$_POST[daypart]?></option><? }else{ ?><option><?=$time[1]?></option><? } ?><option>AM</option><option>PM</option></select><br>
                Auctioneers:<br>
    			<select name="auctioneer"><?=mkauctioneerlist($data3[auctioneer])?></select><select name="auctioneer"><?=mkauctioneerlist2($data3[auctioneer2])?></select><br>
				Status:<br>
                <select name="status"><? if ($_POST[status]){?><option><?=$_POST[status]?></option><? }else{  ?><option><?=$data3[item_status]?></option><? } ?><option>ON SCHEDULE</option><option>SALE CANCELLED</option></select><br>
				<input <? if($data3['private']){ echo "checked";} ?> type="checkbox" name="private" value="1" /> Private ][
                <input <? if($data3['featured']){ echo "checked";} ?> type="checkbox" name="featured" value="1" /> Featured <input name="web_pdf" value="<?=$data3['web_pdf']?>">.pdf
			</fieldset>
            <fieldset>
                <legend>Ground Rent</legend>
			A total of $<input size="5" name="ground_rent" value="<?=$data3[ground_rent]?>" /> .00 <br>
			Due on the <input size="2" name="gr_date" value="<?=strtoupper($data3[gr_date])?>">(5th)<br>
 			of <input size="5" name="gr_month1" value="<?=strtoupper($data3[gr_month1])?>"> (JUNE)<br>
  			and <input size="5" name="gr_month2" value="<?=strtoupper($data3[gr_month2])?>"> (DECEMBER)
            </fieldset>
    	</td>
	</tr>
</table>
</form>









	





	
	







