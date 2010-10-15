<?
include 'functions.php';
dbConnect();
function mkmonth($keep){
	//if (!$keep){$keep = date('M');}
	$opt = "<option selected value='$keep'>$keep</option>";
	$opt .= "<option value='01'>1</option>";
	$opt .= "<option value='02'>2</option>";
	$opt .= "<option value='03'>3</option>";
	$opt .= "<option value='04'>4</option>";
	$opt .= "<option value='05'>5</option>";
	$opt .= "<option value='06'>6</option>";
	$opt .= "<option value='07'>7</option>";
	$opt .= "<option value='08'>8</option>";
	$opt .= "<option value='09'>9</option>";
	$opt .= "<option value='10'>10</option>";
	$opt .= "<option value='11'>11</option>";
	$opt .= "<option value='12'>12</option>";
	return $opt;
}

function mkyear($keep){
	$opt = "<option selected value='$keep'>$keep</option>";
	$opt .= "<option value='2006'>2006</option>";
	$opt .= "<option value='2007'>2007</option>";
	$opt .= "<option value='2008'>2008</option>";
	$opt .= "<option value='2009'>2009</option>";
	$opt .= "<option value='2010'>2010</option>";
	$opt .= "<option value='2011'>2011</option>";
	return $opt;
}
mysql_select_db('intranet');
?>

<table width="100%"><tr><td valign="top">
<table border="1" cellspacing="0" cellpadding="1">
<form method="post">
	<tr>
    	<td colspan="2">Check#:<input name="check"></td>
	</tr>
	<tr>
    	<td colspan="2">Date: <select name="month"><?=mkmonth($_POST[month])?></select><select name="year"><?=mkyear($_POST[year])?></select></td>
	</tr>
	<tr>
    	<td colspan="2"><input type="submit" value="Lookup File Information"></td>
    </tr>
<?
$i=0;
$limit = 30;
while ($i < $limit){
$i++;
?>
	<tr>
    	<td><?=$i?>)</td>
    	<td><input name="lookup[<?=$i?>]"></td>
	</tr>
<? }?>
	<tr>
    	<td colspan="2"><input type="submit" value="Lookup File Information"></td>
    </tr>
</form>
</table>
</td><td valign="top">

<table width="100%" border="1" style="border-collapse:collapse; font-size:14px;">
	<tr>
    	<td colspan="4" align="center" style="font-size:22px;">Check # <?=$_POST[check]?></td>
	</tr>
	<tr bgcolor="#99CCCC"><td>Client File</td><td>Address</td><td>Sale Date</td><td>Sale Time</td></tr>
<?
if ($_POST[lookup]){
$t=0;
$date = $_POST[year]."-".$_POST[month]."-";
	foreach ($_POST[lookup] as $key => $value) {
		
		
		
		$t++;
			$q= "select * from schedule_items where file = '$value' AND sale_date like '$date%'";
			$r=@mysql_query($q) or die("Query: $q<br>".mysql_error());
			while($d=mysql_fetch_array($r, MYSQL_ASSOC)){
		if ($d[file]){
			
			echo "<tr bgcolor='".row_color_light($t)."'><td>$t) $d[file]</td><td>$d[address1]</td><td>".l2rDate($d[sale_date])."</td><td>$d[sale_time]</td></tr>";
		
		}
		
			}
		
	}
}
?>
</table>
</td></tr></table>


