<?
//include 'ps/common.php';


//include 'ps/lock.php';
dbConnect('delta.mdwestserve.com','intranet','','');
mysql_select_db ('core');

$totalr=@mysql_query("SELECT packet_id FROM ps_packets order by packet_id DESC") or die (mysql_error());
$totald=mysql_fetch_array($totalr, MYSQL_ASSOC);
$lastID = $totald['packet_id'];

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

function dbCleaner($str){
$str = trim($str);
$str = addslashes($str);
$str = strtolower($str);
$str = ucwords($str);
return $str;
}


if (isset($_COOKIE['psdata']['user_id'])){
	$id=$_COOKIE['psdata']['user_id'];
}

function mkCC($str){
$q="SELECT * FROM county";
$r=@mysql_query($q);
$option = '<option>'.$str.'</option>';
while($d=mysql_fetch_array($r, MYSQL_ASSOC)){;
$option .= '<option>'.$d[name].'</option>';
}
return $option;
}






$r=@mysql_query("SELECT * FROM ps_packets where packet_id='$_GET[packet]'");
$d=mysql_fetch_array($r, MYSQL_ASSOC);

?>
<style>
a { text-decoration:none}
table { padding:0px; margin:0px;}
body { margin:0px; padding:0px;}
input, select {  background-color:#CCFFFF; font-variant:small-caps; }
td { font-variant:small-caps;}
legend {border:solid 1px #FF0000; background-color:#FFFFFF; padding:2px; font-size:12;}
legend.a {border:solid 1px #FF0000; background-color:#FFFFFF; padding:2px; font-size:12px}
fieldset {padding-left:0px; padding-right:0px;}
.single{background-color:#00FF00}
.duplicate{background-color:#FF0000}
</style>
<script type="text/javascript">
function ClipBoard()
{
holdtext.innerText = copytext.innerText;
Copied = holdtext.createTextRange();
Copied.execCommand("Copy");
}</script>
<body onClick="document.bgColor = '#ffcccc';" bgcolor="#CCCCCC">
<table><tr><td>
<table>
<tr>
<td align="center" width="25%">Client Status<br><strong><?=$d[status]?></strong></td>
</tr><tr>
<td align="center" width="25%">Service Status<br><strong><?=$d[service_status]?></strong></td>
</tr><tr>
<td align="center" width="25%">Filing Status<br><strong><? if ($d[filing_status]){ echo $d[filing_status];}else{ echo "AWAITING AFFIDAVIT"; }?></strong></td>
</tr>
</table>
</td><td>
<table>
<tr>
<td align="center" width="25%">Process Status<br><strong><?=$d[process_status]?></strong></td>
</tr><tr>
<td align="center" width="25%">Affidavit Status<br><strong><?=$d[affidavit_status]?></strong></td>
</tr><tr>
<td align="center" width="25%">Mail Status<br><strong><? if ($d[mail_status]){ echo strtoupper($d[mail_status]);}else{ echo "N/A"; }?></strong></td>
</tr>
</table>
</td></tr></table>
<table width="100%">
<tr>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Packet <?=$d[packet_id]?> Received from <?=id2attorneys($d[attorneys_id])?> on <?=$d[date_received]?>.</LEGEND>


<table width="100%"><tr><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Property Subject to Mortgage or Deed of Trust</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1]?></td>
</tr>
</table>    
</FIELDSET>
</td><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Last Known Address of Record</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1a]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1a]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1a]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1a]?></td>
</tr>
</table>    
</FIELDSET>
</td><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Additional Address 1</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1b]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1b]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1b]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1b]?></td>
</tr>
</table>    
</FIELDSET>
</td></tr>
<? if ($d[attorneys_id] == '1'){ ?>
<tr><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Additional Address 2</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1c]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1c]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1c]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1c]?></td>
</tr>
</table>    
</FIELDSET>
</td><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Additional Address 3</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1d]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1d]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1d]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1d]?></td>
</tr>
</table>    
</FIELDSET>
</td><td>
<FIELDSET>
<LEGEND class="a" ACCESSKEY=C>Additional Address 4</LEGEND>
<table>
<tr>
<td>Street</td>
<td><?=$d[address1e]?></td>
</tr>
<tr>
<td>City</td>
<td><?=$d[city1e]?></td>
</tr>
<tr>
<td>State</td>
<td><?=$d[state1e]?></td>
</tr>
<tr>
<td>ZIP</td>
<td><?=$d[zip1e]?></td>
</tr>
</table>    
</FIELDSET>
</td></tr>
<? } ?>
</table>



<table width="100%"><tr>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>File Data</LEGEND>
<? 
$result = dupCheck('client_file',$d['client_file']);
$result2 = dupCheck('case_no',$d['case_no']);
?>

<table>
<tr>
<td>Client&nbsp;File </td>
<td><?=$d[client_file]?></td>
</tr>
<tr>
<td>Case&nbsp;Number </td>
<td><?=$d[case_no]?></td>
</tr>
<tr>
<td>Circuit&nbsp;Court</td>
<td><?=$d[circuit_court]?></td>
</tr>
<tr>
<td>Instructions</td>
<td><?=$d[attorney_notes];?></td>
</tr>
</table>
</FIELDSET>

</td>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Persons to Serve</LEGEND>
<table>
<tr>
<td nowrap><?=$d[name1]?></td><? $mult=1;?>
</tr><tr>
<td nowrap><?=$d[name2]?></td><? if ($d[name2]){$mult++;}?>
</tr><tr>
<td nowrap><?=$d[name3]?></td><? if ($d[name3]){$mult++;}?>
</tr><tr>
<td nowrap><?=$d[name4]?></td><? if ($d[name4]){$mult++;}?>
</tr><tr>
<td nowrap><?=$d[name5]?></td><? if ($d[name5]){$mult++;}?>
</tr><tr>
<td nowrap><?=$d[name6]?></td><? if ($d[name6]){$mult++;}?>
</tr>
</table>
</FIELDSET>
</td>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Notes</LEGEND>
<table>
<tr>
<td>Server: <?=$d[server_notes]?></td>
</tr>
<tr>
<td>Operations: <?=$d[processor_notes]?></td>
</tr>
<tr>
<td>Vacancy Desc: <?=$d[vacantDescription]?></td>
</tr>
<tr>
<td>Alt Plaintiff: <?=$d[altPlaintiff]?></td>
</tr>
<tr>
<td>Reopen Notes: <?=$d[reopenNotes]?></td>
</tr>
<tr>
<td>Auction Note: <?=$d[auctionNote]?></td>
</tr>
</table>    
</FIELDSET>
</td>
</tr></table>









<table width="100%">
<tr>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server #<?=$d[server_id]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_id]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVER.PDF";
}else{
$filename = $d["case_no"]."-"."SERVER.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? if ($d[server_ida]){ ?>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server "a" #<?=$d[server_ida]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_ida]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVERa.PDF";
}else{
$filename = $d["case_no"]."-"."SERVERa.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? }?>
<? if ($d[server_idb]){ ?>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server "b" #<?=$d[server_idb]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_idb]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVERb.PDF";
}else{
$filename = $d["case_no"]."-"."SERVERb.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? }?>
</tr>
<tr>
<? if ($d[server_idc]){ ?>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server "c" #<?=$d[server_idc]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_idc]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVERc.PDF";
}else{
$filename = $d["case_no"]."-"."SERVERc.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? }?>
<? if ($d[server_idd]){ ?>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server "d" #<?=$d[server_idd]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_idd]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVERd.PDF";
}else{
$filename = $d["case_no"]."-"."SERVERd.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? }?>
<? if ($d[server_ide]){ ?>
<td valign="top">
<FIELDSET>
<LEGEND ACCESSKEY=C>Process Server "e" #<?=$d[server_ide]?></LEGEND>
<?
$r2=@mysql_query("select * from ps_users where id = '$d[server_ide]'");
$d2=mysql_fetch_array($r2, MYSQL_ASSOC);
?>
<table>
<tr>
<td><?=$d2[company]?></td>
</tr>
<tr>
<td><?=$d2[name]?></td>
</tr>
<tr>
<td><?=$d2[phone]?></td>
</tr>
<tr>
<td><?=$d2[address]?><br><?=$d2[city]?> <?=$d2[state]?> <?=$d2[zip]?></td>
</tr>
<tr>
<td>
<?   
if ($d["attorneys_id"] == 1 || $d["attorneys_id"] == 44){
$filename = $d["client_file"].'-'.$d["date_received"]."-"."SERVERe.PDF";
}else{
$filename = $d["case_no"]."-"."SERVERe.PDF";
}
?>
</td>
</tr>
</table>    
</FIELDSET>
</td>
<? }?>
<td valign="top">
</td></tr><tr><td>
<?
$q5="SELECT DISTINCT serverID from ps_history WHERE packet_id='$d[packet_id]'";
$r5=@mysql_query($q5) or die(mysql_error());
$i=0;
$data5=mysql_num_rows($r5);
if ($data5 > 0){
while ($d5=mysql_fetch_array($r5, MYSQL_ASSOC)){$i++;
$q6="SELECT * FROM ps_history WHERE serverID='$d5[serverID]' and packet_id='$d[packet_id]'";
$r6=@mysql_query($q6) or die(mysql_error());
$d6=mysql_num_rows($r6);
if ($i == '1'){
if ($d6 > 1){
$server = $d6." entries by ".id2name($d5[serverID]);
}else{
$server = $d6." entry by ".id2name($d5[serverID]);
}
}else{
if ($d6 > 1){
$server .= ", ".$d6." entries by ".id2name($d5[serverID]);
}else{
$server .= ", ".$d6." entry by ".id2name($d5[serverID]);
}
}
}
}else{
$server="no history entries";
}
?>
</td>
</tr></table>
</FIELDSET>
</td></tr></table>
<?
function id2svr($id){
	$q="SELECT name FROM ps_users WHERE id = '$id'";
	$r=@mysql_query($q);
	$d=mysql_fetch_array($r, MYSQL_ASSOC);
return $d[name];
}

$packet=$_GET['packet'];
$q="SELECT * from ps_packets where packet_id = '$packet'";
$r=@mysql_query($q) or die(mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
?>
<style>
table { padding:0px;}
body { margin:0px; padding:0px; background-color:#999999}
input, select { background-color:#CCFFFF; font-variant:small-caps; font-size:12px }
textarea { background-color:#CCFFFF; font-variant:small-caps; }
td { font-variant:small-caps;}
legend {border:solid 1px #FF0000; background-color:#FFFFFF; padding:0px; font-size:13px}
</style>
<table width="530px" align="center">
	<tr align="center">
    	<td align="center">
	        <FIELDSET style="background-color:#CCCCCC; padding:0px">
			<legend accesskey="C" align="center" style="font-size:12px; font-weight:bold;">History Items for Packet <?=$d[packet_id]?>: <?=$d[address1]?> <?=$d[address1a]?> (Servers: <?=id2svr($d[server_id]);?><? if ($d[server_ida]){echo ", ".id2svr($d[server_ida]);}?>)</legend>
<?
$q1="SELECT * from ps_history where packet_id = '$packet' order by history_id ASC";
$r1=@mysql_query($q1) or die("Query: $q1<br>".mysql_error());
while($d1=mysql_fetch_array($r1, MYSQL_ASSOC)){
?>
<FIELDSET style="padding:0px">
<LEGEND ACCESSKEY=C <? if ($d1[action_type] == 'UNLINKED'){?> style="background-color:#FFCCFF" <? } ?>>History Item <?=$d1[history_id]?>, Defendant <?=$d1[defendant_id]?>, by <?=id2svr($d1[serverID]);?>: <?=$d1[action_type]?></LEGEND>
<table width="530px" align="left">
	<tr>
		<td valign="top" align="left" width="40%"><small><?=$d1[action_str]?><? if ($d1[residentDesc]){ echo"<br /><b>D:</b> ".$d1[residentDesc];}?></small></td>
    </tr>
</table>
</FIELDSET>
<? } ?>
</FIELDSET>
</td></tr></table>

<? mysql_select_db ('intranet');?>