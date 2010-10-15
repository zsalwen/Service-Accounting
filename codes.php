<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Code Manager                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 9, 2008   						                      |
// | Updated: July 10, 2008														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
mysql_connect();
mysql_select_db('core');
if (isset($_POST['insert'])){
@mysql_query("insert into ac_codes (codeID, codeName) values ('$codeID', '$codeName')");
header('Location: codes.php');
}
if (isset($_POST['update'])){
@mysql_query("update ac_codes set codeName='$codeName' where codeID = '".$_POST['codeID']."'");
header('Location: codes.php');
}
include 'menu.php';

if (isset($_GET['id'])){
$r=@mysql_query("select * from ac_codes where codeID = '".$_GET['id']."'") or die(mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
?>
<form method="post"><input name="codeID" value="<?=$_GET['id'];?>" /><input name="codeName" value="<?=$d['codeName'];?>" /><input name="update" value="update" type="submit" /></form>
<? }else{?>
<form method="post"><input name="codeID" value="codeID" /><input name="codeName" value="codeName" /><input name="insert" value="insert" type="submit" /></form>
<? }
$r=@mysql_query("select * from ac_codes order by codeID desc ") or die(mysql_error());
?>
<table>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><a href="?id=<?=$d['codeID']?>">EDIT</a></td>
    	<td><?=$d['codeID']?></td>
    	<td><?=$d['codeName']?></td>
	</tr>
<? } ?>    
</table>        