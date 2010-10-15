<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Process Server Statement        					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 7, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
if (isset($_GET['year']) && isset($_GET['month']) && isset($_GET['attid'])){
header('Location: http://mdwestserve.com/ps/billControl.php?year='.$_GET['year'].'&month='.$_GET['month'].'&attid='.$_GET['attid'].'&week='.$_GET['week']);
}

dbAlphaConnect();



include 'menu.php';
?>
<center>
 <form>
<table align="center" border="1" bgcolor="#FFFFFF">

	<tr>
		<td>Client</td>
		<td align="left" valign="middle"><select name="attid">
				<?
		function psAttLoad($id){
		mysql_select_db ('core');
			$r=mysql_query("SELECT * FROM ps_packets WHERE attorneys_id = '$id'");
			$count =  mysql_num_rows($r);
			return $count;
		}
		mysql_select_db ('ccdb');
		$q8 = "SELECT * FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	$hitter = psAttLoad($data8['attorneys_id']);
	if ($hitter > 0){
	echo "<option value='".$data8['attorneys_id']."'>".$data8['display_name']." - ".$hitter."</option>";
	}
		}
		mysql_select_db ('core');
		?>
</select></td>
	</tr>
	<tr>
		<td>Statement Date</td><td><select name="month"><option>01</option>
				<option>02</option>
				<option>03</option>
				<option>04</option>
				<option>05</option>
				<option>06</option>
				<option>07</option>
				<option>08</option>
				<option>09</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
			</select><select name="year">
			
				<option>2008</option>
				<option>2009</option>
				<option>2010</option>
			</select><select name="week"><option> </option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option></td>
	</tr>
		<td colspan="2" align="center"><input type="submit" name="direction" value="Load Statement" /></td>
	</tr>

</table>
</form>
</center>