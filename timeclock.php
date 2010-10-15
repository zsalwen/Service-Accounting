<?
// +----------------------------------------------------------------------+
// | AS-CORE                                                              |
// | Time Clock                     					                  |
// | Requirements: Functions, Database    			                      |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: June 30, 2008   					                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'functions.php';
dbConnect();
if (isset($_POST['action'])){
	if (isset($_POST['note'])){
		$record = $_POST['note'];
	}else{
		$record = $_POST['action'];
	}
	$q="INSERT INTO autoclock (user_id, punch_time, punch_date, action) values ('".$_COOKIE['userdata']['user_id']."', NOW(), NOW(), '$record')";
	$r = @mysql_query($q);
	logAction("Timeclock: ".dbCleanIn($_POST['action']));
	header('Location: timeclock.php');
}
include 'menu.php';

$q="SELECT * FROM paychecks WHERE period_start <= '".date('Y-m-d')."' AND period_end >= '".date('Y-m-d')."' ";
$r=@mysql_query($q) or die(mysql_error());
$d = mysql_fetch_array($r, MYSQL_ASSOC);

$q = "SELECT *,DATE_FORMAT(punch_date, '%m/%d/%Y') as punch_date_f, DATE_FORMAT(punch_time, '%r') as punch_time_f FROM autoclock WHERE punch_date >= '".$d['period_start']."' AND punch_date <= '".$d['period_end']."' AND user_id = '".$_COOKIE['userdata']['user_id']."' ORDER BY punch_date DESC, punch_time DESC";
$r = @mysql_query($q);

?>

Current Week: <?=$d['period_start']?> to <?=$d['period_end']?>

<table>
<form method="post">
	<tr>
    	<td><input type="submit" name="submit" value="Punch Card" />
        <select name="action"><option>IN</option><option>OUT</option><option>BREAK - IN</option><option>BREAK - OUT</option></select><input name="note" /></td>
    </tr>
</form>
</table>

<table cellpadding="3" cellspacing="0">
<? while($d = mysql_fetch_array($r, MYSQL_ASSOC)){?>
    <tr>
        <td nowrap="nowrap" style="border-top:solid; border-top-width:1px;"><?=$d['punch_date_f'];?></td>
        <td nowrap="nowrap" style="border-top:solid; border-top-width:1px;"><?=$d['punch_time_f'];?></td>
        <td nowrap="nowrap" style="border-top:solid; border-top-width:1px;"><?=$d['action'];?></td>
    </tr>
<? }?>
</table>


<table>
<form action="../AS/printTimeclock.php" target="_blank">
    <tr>
		<td>
        	<select name="start"><?=payWeeks();?></select><input type="submit" value="View Time Card" />
        </td>    
    </tr>
</form>   
</table>