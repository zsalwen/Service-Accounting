<?
session_start();
include 'functions.php';
dbConnect();
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
	/*if (window.outerWidth) {
		window.outerWidth = width;
		window.outerHeight = height;
	}
	else if (window.resizeTo) {
		window.resizeTo(width,height);
	}
	else {
		alert("Not supported.");
	}*/
}

//-->
</script>
<?
if ($_POST[submit]){
$notes = addslashes($_POST[notes]);
mysql_select_db ('intranet');

$_SESSION[dateUsed] = $_POST[check_date];
$_SESSION[checkUsed] = $_POST[check_number];





	$q1 = "UPDATE schedule_items SET 
									location_id='3', 
									check_number='$_POST[check_number]',
									check_number2='$_POST[check_number2]', 
									check_number3='$_POST[check_number3]', 
									check_date='$_POST[check_date]',
									check_date2='$_POST[check_date2]', 
									check_date3='$_POST[check_date3]', 
									ad_deposit='$_POST[ad_deposit]', 
									update_date=NOW(), 
									updated_id='12', 
									auction_fee='$_POST[auction_fee]', 
									ad_cost='$_POST[ad_cost]', 
									ad_cost2='$_POST[ad_cost2]', 
									ad_cost3='$_POST[ad_cost3]', 
									paid='$_POST[paid]', 
									paid2='$_POST[paid2]', 
									paid3='$_POST[paid3]', 
									adjusted='$_POST[adjusted]' 
										WHERE schedule_id='$_POST[id]'";		
	$r1 = @mysql_query ($q1) or die(mysql_error());
	
addNote($_POST[id],$_COOKIE[userdata][name].': Entered Payment on '.date('m/d/Y'));
	
	//echo $q1;
	echo "<script>automation();</script>";
} else {

$q1 = "SELECT * FROM schedule_items WHERE schedule_id = $_GET[id]";		
$r1 = @mysql_query ($q1) or die(mysql_error());
$data = mysql_fetch_array($r1, MYSQL_ASSOC);



}
?>
<title>Edit</title>
<script>document.title = "<?=$data[address1];?>";</script>
<body onLoad="setSize(500,500)" bgcolor="#99CCFF">



<table style="border-style:solid; position:absolute; top:0px; left:0px;" bgcolor="#FFFFFF"> 
<form method="post">
<input type="hidden" name="id" value="<?=$_GET[id]?>" />
	<tr>
		<td colspan="3" align="center"><font size="+2"><?=$data[address1]?></font></td>
	</tr>
	<tr>
		<td>Deposit</td>
		<td>$<input name="ad_deposit" size="7" maxlength="7" value="<?=$data[ad_deposit]?>" /></td>
		<td></td>
	</tr>
	<tr>
		<td>Auction Fee</td>
		<td>$<input name="auction_fee" size="7" maxlength="7" value="<?=$data[auction_fee]?>" /></td>
		<td></td>
	</tr>
	<tr>
		<td>Main Publication</td>
		<td>$<input name="ad_cost" size="7" maxlength="7" value="<?=$data[ad_cost]?>" /></td>
		<td><?=$data[paper]?></td>
	</tr>
	<tr>
		<td>Secondary Publication</td>
		<td>$<input name="ad_cost2" size="7" maxlength="7" value="<?=$data[ad_cost2]?>" /></td>
		<td><?=$data[paper2]?></td>
	</tr>
	<tr>
		<td>Tertiary Publication</td>
		<td>$<input name="ad_cost3" size="7" maxlength="7" value="<?=$data[ad_cost3]?>" /></td>
		<td><?=$data[paper3]?></td>
	</tr>
	<tr>
		<td>Check</td>
		<td>Amount</td>
		<td>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number</td>
	</tr>
	<tr>
		<td>Check 1</td>
		<td>$<input name="paid" size="7" maxlength="7" value="<?=$data[paid]?>" /></td>
		<td><input size="5" name="check_date" value="<? if ($data[check_date]){ echo $data[check_date]; }else{ echo $_SESSION[dateUsed]; }?>" /> <input size="5" name="check_number" value="<? if ($data[check_number]){ echo $data[check_number]; }else{ echo $_SESSION[checkUsed]; }?>" /></td>
	</tr>
	<tr>
		<td>Check 2</td>
		<td>$<input name="paid2" size="7" maxlength="7" value="<?=$data[paid2]?>" /></td>
		<td><input size="5" name="check_date2" value="<?=$data[check_date2]?>" /> <input size="5" name="check_number2" value="<?=$data[check_number2]?>" /></td>
	</tr>
	<tr>
		<td>Check 3</td>
		<td>$<input name="paid3" size="7" maxlength="7" value="<?=$data[paid3]?>" /></td>
		<td><input size="5" name="check_date3" value="<?=$data[check_date3]?>" /> <input size="5" name="check_number3" value="<?=$data[check_number3]?>" /></td>
	</tr>
	<tr>
		<td>Amount Adjusted</td>
		<td>$<input name="adjusted" size="7" maxlength="7" value="<?=$data[adjusted]?>" /></td>
	</tr>
	<tr>
		<td colspan="3" align="right"><input name="submit" type="submit"  value="Record Payment"/></td>
	</tr>
</table>

