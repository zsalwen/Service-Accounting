<?
include 'functions.php';
dbAlphaConnect();
function dupCheck($field,$string){
$r=@mysql_query("select * from ps_packets where $field = '$string'");
$c=mysql_num_rows($r);
if ($c == 1){
$return[0]="class='single'";
$return[1]=$c;
}else{
$return[0]="class='duplicate'";
$return[1]=$c;
}
return $return;
}

?>
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
function setSize(width,height) {
	if (window.outerWidth) {
		window.outerWidth = width;
		window.outerHeight = height;
	}
	else if (window.resizeTo) {
		window.resizeTo(width,height);
	}
	else {
		alert("Not supported.");
	}
}

//-->
</script>
<?
if ($_POST[submit]){

	$rxx=@mysql_query("select * from psActivity where today='".date('Y-m-d')."'") or die(mysql_error());
	$dxx=mysql_fetch_array($rxx,MYSQL_ASSOC);
	$count=$dxx[clientPayment]+1;
	@mysql_query("update psActivity set clientPayment = '$count' where today='".date('Y-m-d')."'") or die(mysql_error());
	echo "Saved! - $count for the day...";

	$q1 = "UPDATE ps_packets, ps_pay SET 
									ps_pay.code410='$_POST[code410]',
									ps_pay.code410a='$_POST[code410a]',
									ps_pay.code410b='$_POST[code410b]',
									ps_pay.code420='$_POST[code420]',
									ps_pay.code420a='$_POST[code420a]',
									ps_pay.code420b='$_POST[code420b]',
									ps_pay.code430='$_POST[code430]',
									ps_pay.code430a='$_POST[code430a]',
									ps_pay.code430b='$_POST[code430b]',
									ps_pay.code440='$_POST[code440]',
									ps_pay.code440a='$_POST[code440a]',
									ps_pay.code440b='$_POST[code440b]',
									ps_pay.contractor_rate='$_POST[contractor_rate]', 
									ps_pay.contractor_paid='$_POST[contractor_paid]',
									ps_pay.contractor_check='$_POST[contractor_check]', 
									ps_pay.contractor_ratea='$_POST[contractor_ratea]', 
									ps_pay.contractor_paida='$_POST[contractor_paida]',
									ps_pay.contractor_checka='$_POST[contractor_checka]', 
									ps_pay.contractor_rateb='$_POST[contractor_rateb]', 
									ps_pay.contractor_paidb='$_POST[contractor_paidb]',
									ps_pay.contractor_checkb='$_POST[contractor_checkb]', 
									ps_pay.contractor_ratec='$_POST[contractor_ratec]', 
									ps_pay.contractor_paidc='$_POST[contractor_paidc]',
									ps_pay.contractor_checkc='$_POST[contractor_checkc]', 
									ps_pay.contractor_rated='$_POST[contractor_rated]', 
									ps_pay.contractor_paidd='$_POST[contractor_paidd]',
									ps_pay.contractor_checkd='$_POST[contractor_checkd]', 
									ps_pay.contractor_ratee='$_POST[contractor_ratee]', 
									ps_pay.contractor_paide='$_POST[contractor_paide]',
									ps_pay.contractor_checke='$_POST[contractor_checke]', 
									ps_pay.client_rate='$_POST[client_rate]', 
									ps_pay.client_ratea='$_POST[client_ratea]', 
									ps_pay.client_rateb='$_POST[client_rateb]', 
									ps_pay.client_paid='$_POST[client_paid]',
									ps_pay.client_paida='$_POST[client_paida]',
									ps_pay.client_paidb='$_POST[client_paidb]',
									ps_pay.client_check='$_POST[client_check]',
									ps_pay.client_checka='$_POST[client_checka]',
									ps_pay.client_checkb='$_POST[client_checkb]',
									ps_pay.client_ratec='$_POST[client_ratec]', 
									ps_pay.client_rated='$_POST[client_rated]', 
									ps_pay.client_ratee='$_POST[client_ratee]', 
									ps_pay.client_paidc='$_POST[client_paidc]',
									ps_pay.client_paidd='$_POST[client_paidd]',
									ps_pay.client_paide='$_POST[client_paide]',
									ps_pay.client_checkc='$_POST[client_checkc]',
									ps_pay.client_checkd='$_POST[client_checkd]',
									ps_pay.client_checke='$_POST[client_checke]',
									ps_packets.accountingNotes='".addslashes($_POST[accountingNotes])."'
										WHERE packet_id='$_POST[id]' AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
	
//addNote($_POST[id],$_COOKIE[userdata][name].': Entered Payment on '.date('m/d/Y'));
	

	
	
	//echo $q1;
	echo "<script>automation();</script>";
}

$q1 = "SELECT * FROM ps_packets, ps_pay WHERE packet_id = $_GET[id] AND ps_packets.packet_id=ps_pay.packetID AND ps_pay.product='OTD'";		
$r1 = @mysql_query ($q1) or die(mysql_error());
$data = mysql_fetch_array($r1, MYSQL_ASSOC);




?>
<script>
document.title = "Accounting #<?=$data[packet_id];?>";

</script>
<body bgcolor="#99CCFF">
<style>
fieldset { background-color:#FFFFFF; width:600px; border:solid 1px #000000;}
.altset { background-color:#FFFFFF; width:400px; border:solid 1px #000000;}
.altset2 { background-color:#FFFFFF; width:130px; border:solid 1px #000000;}
legend, input, select { padding:5px; background-color:#FFFFCC; border:solid 1px #000000;}
td { font-variant:small-caps }
</style>
<form id="acc" name="acc" method="post">
<input type="hidden" name="id" value="<?=$_GET[id]?>" />
<table><tr><td>
<fieldset style="width:900">
<legend>Accounting Notes</legend>
<textarea name="accountingNotes" cols="110" rows="4"><?=stripslashes($data[accountingNotes])?></textarea>
<input name="submit" type="submit" style="background-color:#00FF00; cursor:pointer; font-size:24px; position:absolute; top:0; right:200px;"  value="SAVE"/>
<input name="submit" type="submit" style="background-color:#00FF00; cursor:pointer; font-size:24px; position:absolute; top:0; left:200px;"  value="SAVE"/>
</fieldset></td><td valign="top">

<FIELDSET class="altset2">
<LEGEND class="a" ACCESSKEY=C>Defendants</LEGEND>
<table>
<tr>
<td nowrap><?=$data[name1]?></td>
</tr><tr>
<td nowrap><?=$data[name2]?></td>
</tr><tr>
<td nowrap><?=$data[name3]?></td>
</tr><tr>
<td nowrap><?=$data[name4]?></td>
</tr><tr>
<td nowrap><?=$data[name5]?></td>
</tr><tr>
<td nowrap><?=$data[name6]?></td>
</tr>
</table>
</FIELDSET>
</td></tr></table>
<table><tr><td valign="top">
<fieldset>
	<legend>Service File Details</legend>
<table width="100%">
	<tr>
    	<td>Packet Number</td>
        <td><?=$data[packet_id]?></td>
    </tr>
	<tr>
    	<td>Case Number</td>
        <td><?=$data[case_no]?></td>
    </tr>
	<tr>
    	<td>Filing Status</td>
        <td><?=$data[filing_status]?></td>
    </tr>
	<tr>
    	<td>Mail Status</td>
        <td><?=$data[mail_status]?></td>
    </tr>
	<tr>
    	<td>Affidavit Status</td>
        <td><?=$data[affidavit_status]?></td>
    </tr>
	<tr>
    	<td>Service Status</td>
        <td><?=$data[service_status]?></td>
    </tr>
	<tr>
		<td>Process Status</td>
		<td><?=$data[process_status]?></std>
	</tr>
</table>
</fieldset>    
<br>
<fieldset>
	<legend>Process Service Rates</legend>

<table width="100%">
	<tr>
    	<td></td>
        <td>1</td>
    	<td>2</td>
    	<td>3</td>
        <td>4</td>
    	<td>5</td>
    	<td>6</td>
    </tr>
	<tr>
    	<td>Contractor Quote</td>
    	<td><input name="contractor_rate" size="7" maxlength="7" value="<?=$data[contractor_rate]?>" /></td>
    	<td><input name="contractor_ratea" size="7" maxlength="7" value="<?=$data[contractor_ratea]?>" /></td>
    	<td><input name="contractor_rateb" size="7" maxlength="7" value="<?=$data[contractor_rateb]?>" /></td>
    	<td><input name="contractor_ratec" size="7" maxlength="7" value="<?=$data[contractor_ratec]?>" /></td>
    	<td><input name="contractor_rated" size="7" maxlength="7" value="<?=$data[contractor_rated]?>" /></td>
    	<td><input name="contractor_ratee" size="7" maxlength="7" value="<?=$data[contractor_ratee]?>" /></td>
    </tr>
    <tr>
    	<td>Client Rate</td>
    	<td><input name="client_rate" size="7" maxlength="7" value="<?=$data[client_rate]?>" /></td>
    	<td><input name="client_ratea" size="7" maxlength="7" value="<?=$data[client_ratea]?>" /></td>
    	<td><input name="client_rateb" size="7" maxlength="7" value="<?=$data[client_rateb]?>" /></td>
    	<td><input name="client_ratec" size="7" maxlength="7" value="<?=$data[client_ratec]?>" /></td>
    	<td><input name="client_rated" size="7" maxlength="7" value="<?=$data[client_rated]?>" /></td>
    	<td><input name="client_ratee" size="7" maxlength="7" value="<?=$data[client_ratee]?>" /></td>
	</tr>
</table>


</fieldset>    

</td><td valign="top">
<fieldset>
	<legend>Client Payment Details</legend>
<table width="100%" cellspacing="0">
	<tr>
    	<td></td>
        <td>First Check</td>
    	<td>Second Check</td>
    	<td>Third Check</td>
    </tr>
    <tr>
    	<td>Client Check</td>
    	<td><input tabindex="1" name="client_check" size="7" maxlength="30" value="<?=$data[client_check]?>" /></td>
    	<td><input name="client_checka" size="7" maxlength="30" value="<?=$data[client_checka]?>" /></td>
    	<td><input name="client_checkb" size="7" maxlength="30" value="<?=$data[client_checkb]?>" /></td>
	</tr>
    <tr>
    	<td>Code: Process Service</td>
    	<td><input tabindex="2" name="code410" size="7" maxlength="7" value="<?=$data[code410]?>" /></td>
    	<td><input name="code410a" size="7" maxlength="7" value="<?=$data[code410a]?>" /></td>
    	<td><input name="code410b" size="7" maxlength="7" value="<?=$data[code410b]?>" /></td>
	</tr>        
    <tr>
    	<td>Code: Mailing Services</td>
    	<td><input tabindex="3" name="code420" size="7" maxlength="7" value="<?=$data[code420]?>" /></td>
    	<td><input name="code420a" size="7" maxlength="7" value="<?=$data[code420a]?>" /></td>
    	<td><input name="code420b" size="7" maxlength="7" value="<?=$data[code420b]?>" /></td>
	</tr>        
    <tr>
    	<td>Code: Filing Services</td>
    	<td><input tabindex="4" name="code430" size="7" maxlength="30" value="<?=$data[code430]?>" /></td>
    	<td><input name="code430a" size="7" maxlength="30" value="<?=$data[code430a]?>" /></td>
    	<td><input name="code430b" size="7" maxlength="30" value="<?=$data[code430b]?>" /></td>
	</tr>        
    <tr>
    	<td>Code: Skip Trace Services</td>
    	<td><input name="code440" size="7" maxlength="30" value="<?=$data[code440]?>" /></td>
    	<td><input name="code440a" size="7" maxlength="30" value="<?=$data[code440a]?>" /></td>
    	<td><input name="code440b" size="7" maxlength="30" value="<?=$data[code440b]?>" /></td>
	</tr>        
    <tr>
    	<td style="border-top:solid 1px;">Total Payment</td>
    	<td style="border-top:solid 1px;"><input tabindex="5" name="client_paid" size="7" maxlength="7" value="<?=$data[client_paid]?>" /></td>
    	<td style="border-top:solid 1px;"><input name="client_paida" size="7" maxlength="7" value="<?=$data[client_paida]?>" /></td>
    	<td style="border-top:solid 1px;"><input name="client_paidb" size="7" maxlength="7" value="<?=$data[client_paidb]?>" /></td>
	</tr>

</table>

</fieldset>    
<br>
<fieldset>
	<legend>Process Server Payment Details</legend>
<table width="100%">
	<tr>
    	<td></td>
        <td>Server </td>
    	<td>Server 'a'</td>
    	<td>Server 'b'</td>
    </tr>
    <tr>
    	<td>Paid</td>
    	<td><input name="contractor_paid" size="7" maxlength="7" value="<?=$data[contractor_paid]?>" /></td>
    	<td><input name="contractor_paida" size="7" maxlength="7" value="<?=$data[contractor_paida]?>" /></td>
    	<td><input name="contractor_paidb" size="7" maxlength="7" value="<?=$data[contractor_paidb]?>" /></td>
	</tr>
    <tr>
    	<td>Check</td>
    	<td><input name="contractor_check" size="7" maxlength="30" value="<?=$data[contractor_check]?>" /></td>
    	<td><input name="contractor_checka" size="7" maxlength="30" value="<?=$data[contractor_checka]?>" /></td>
    	<td><input name="contractor_checkb" size="7" maxlength="30" value="<?=$data[contractor_checkb]?>" /></td>
	</tr>
</table>

</fieldset>    
</td></td><tr><td>
<?
$defs=0;
if ($data[name1]){$defs++;}
if ($data[name2]){$defs++;}
if ($data[name3]){$defs++;}
if ($data[name4]){$defs++;}
if ($data[name5]){$defs++;}
if ($data[name6]){$defs++;}
$inState=0;
$outOfState=0;
$totalCost=0;
$firstClassCost=0;
if (strtoupper($data[state1e]) != 'MD' && $data[state1e] != ''){ $outOfState++; }elseif(strtoupper($data[state1e]) == 'MD' && $data[state1e] != ''){ $inState++; }
if (strtoupper($data[state1d]) != 'MD' && $data[state1d] != ''){ $outOfState++; }elseif(strtoupper($data[state1d]) == 'MD' && $data[state1d] != ''){ $inState++; }
if (strtoupper($data[state1c]) != 'MD' && $data[state1c] != ''){ $outOfState++; }elseif(strtoupper($data[state1c]) == 'MD' && $data[state1c] != ''){ $inState++; }
if (strtoupper($data[state1b]) != 'MD' && $data[state1b] != ''){ $outOfState++; }elseif(strtoupper($data[state1b]) == 'MD' && $data[state1b] != ''){ $inState++; }
if (strtoupper($data[state1a]) != 'MD' && $data[state1a] != ''){ $outOfState++; }elseif(strtoupper($data[state1a]) == 'MD' && $data[state1a] != ''){ $inState++; }
if (strtoupper($data[state1]) != 'MD' && $data[state1] != ''){ $outOfState++; }elseif(strtoupper($data[state1]) == 'MD' && $data[state1] != ''){ $inState++; }
$inStateCost=$defs*$inState*75;
$outOfStateCost=$defs*$outOfState*125;
if ($data[filing_status] != 'CANCELLED' && $data[filing_status] != 'DO NOT FILE'){$filingCost=25;}else{$filingCost=0;}
$q4="SELECT DISTINCT defendant_id from ps_history WHERE packet_id='$data[packet_id]' AND (action_type='Served Defendant' OR action_type='Served Resident')";
$r4=@mysql_query($q4) or die ("Query: $q4<br>".mysql_error());
$PD=mysql_num_rows($r4);
if ($PD <= $defs){
	$q2="SELECT DISTINCT defendant_id from ps_history WHERE packet_id='$data[packet_id]' AND action_type='First Class Mailing'";
	$r2=@mysql_query($q2) or die ("Query: $q2<br>".mysql_error());
	$firstClass=mysql_num_rows($r2);
	$firstClassCost=$defs*$firstClass*10;
	$q3="SELECT DISTINCT defendant_id from ps_history WHERE packet_id='$data[packet_id]' AND action_type='First Class C.R.R. Mailing'";
	$r3=@mysql_query($q3) or die ("Query: $q3<br>".mysql_error());
	$CRR=mysql_num_rows($r3);
}
if ($CRR > 0){
	$CRRCost=(($defs-$PD)*$inState*25)+(($defs-$PD)*$outOfState*25);
}else{
	$CRRCost=0;
}
$totalAdds=$inState+$outOfState;
$totalCost=$inStateCost+$outOfStateCost+$filingCost+$firstClassCost+$CRRCost;
?>
<fieldset>
	<legend>Payment Matrix</legend>
<table cellspacing="0">
	<tr>
    	<td width="300px"></td>
        <td width="75px">Rate</td>
        <td width="100px" align="center"># of defendants</td>
        <td width="100px" align="center"># of addresses</td>
        <td width="100px" align="center">Total</td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px">In-State Service</td>
        <td style="border-bottom:dotted 1px">$75</td>
        <td style="border-bottom:dotted 1px">x    <?=$defs?></td>
        <td style="border-bottom:dotted 1px">x    <?=$inState?></td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$inStateCost?></td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px">Out-of-State Service</td>
        <td style="border-bottom:dotted 1px">$125</td>
        <td style="border-bottom:dotted 1px">x    <?=$defs?></td>
        <td style="border-bottom:dotted 1px">x    <?=$outOfState?></td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$outOfStateCost?></td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px">Mailed First Class</td>
        <td style="border-bottom:dotted 1px">$10</td>
        <td style="border-bottom:dotted 1px">x    <?=($defs-$PD)?></td>
        <td style="border-bottom:dotted 1px">x    <?=$totalAdds?></td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$firstClassCost?></td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px">Mailed First Class and Certified Return Receipt</td>
        <td style="border-bottom:dotted 1px">$25</td>
        <td style="border-bottom:dotted 1px">x    <?=($defs-$PD)?></td>
        <td style="border-bottom:dotted 1px">x    <?=$totalAdds?></td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$CRRCost?></td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px">Affidavit Filing</td>
        <td style="border-bottom:dotted 1px">$25</td>
        <td style="border-bottom:dotted 1px">&nbsp;</td>
        <td style="border-bottom:dotted 1px">&nbsp;</td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$filingCost?></td>
    </tr>
	<tr>
    	<td style="border-bottom:dotted 1px" colspan="4">Total Cost to Client</td>
        <td style="border-bottom:dotted 1px">=(+)  <?=$totalCost?></td>
    </tr>
</table>
</fieldset>
</td></tr></table>
</form>





<table width="100%"><tr><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Property Subject to Mortgage or Deed of Trust</LEGEND>
<table>
<?   $result3 = dupCheck('address1',$data['address1']); ?>
<tr <?=$result3[0]?>>
<td><?=$data[address1]?></td>
</tr>
<tr>
<td><?=$data[city1]?></td>
</tr>
<tr>
<td><?=$data[state1]?></td>
</tr>
<tr>
<td><?=$data[zip1]?></td>
</tr>
</table>    
</FIELDSET>
</td><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Last Known Address of Record</LEGEND>
<table>
<tr>
<td><?=$data[address1a]?></td>
</tr>
<tr>
<td><?=$data[city1a]?></td>
</tr>
<tr>
<td><?=$data[state1a]?></td>
</tr>
<tr>
<td><?=$data[zip1a]?></td>
</tr>
</table>    
</FIELDSET>
</td><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Additional Address 1</LEGEND>
<table>
<tr>
<td><?=$data[address1b]?></td>
</tr>
<tr>
<td><?=$data[city1b]?></td>
</tr>
<tr>
<td><?=$data[state1b]?></td>
</tr>
<tr>
<td><?=$data[zip1b]?></td>
</tr>
</table>    
</FIELDSET>
</td></tr>
<? if ($data[attorneys_id] == '1' || $data[attorneys_id] == '48'){ ?>
<tr><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Additional Address 2</LEGEND>
<table>
<tr>
<td><?=$data[address1c]?></td>
</tr>
<tr>
<td><?=$data[city1c]?></td>
</tr>
<tr>
<td><?=$data[state1c]?></td>
</tr>
<tr>
<td><?=$data[zip1c]?></td>
</tr>
</table>    
</FIELDSET>
</td><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Additional Address 3</LEGEND>
<table>
<tr>
<td><?=$data[address1d]?></td>
</tr>
<tr>
<td><?=$data[city1d]?></td>
</tr>
<tr>
<td><?=$data[state1d]?></td>
</tr>
<tr>
<td><?=$data[zip1d]?></td>
</tr>
</table>    
</FIELDSET>
</td><td valign="top">
<FIELDSET class="altset">
<LEGEND class="a" ACCESSKEY=C>Additional Address 4</LEGEND>
<table>
<tr>
<td><?=$data[address1e]?></td>
</tr>
<tr>
<td><?=$data[city1e]?></td>
</tr>
<tr>
<td><?=$data[state1e]?></td>
</tr>
<tr>
<td><?=$data[zip1e]?></td>
</tr>
</table>    
</FIELDSET>
</td></tr>
<? } ?>
</table>
<form><input name="id" style="position:absolute; top:0px; right:0px; background-color:#CCFF00;" size="5"></form>