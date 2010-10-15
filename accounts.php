<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Manager                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 7, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();
if (isset($_POST['insert'])){
@mysql_query("insert into ac_accounts (routingNumber, accountNumber, accountName) values ('$routingNumber', '$accountNumber', '$accountName')");
header('Location: accounts.php');
}
if (isset($_POST['update'])){
@mysql_query("update ac_accounts set routingNumber='$routingNumber', accountNumber='$accountNumber', accountName='$accountName' where accountID = '".$_POST['accountID']."'");
header('Location: accounts.php');
}
include 'menu.php';

if (isset($_GET['id'])){
$r=@mysql_query("select * from ac_accounts where accountID = '".$_GET['id']."'");
$d=mysql_fetch_array($r, MYSQL_ASSOC);
?>
<form method="post"><input name="accountID" value="<?=$_GET['id'];?>" /><input name="routingNumber" value="<?=$d['routingNumber'];?>" /><input name="accountNumber" value="<?=$d['accountNumber'];?>" /><input name="accountName" value="<?=$d['accountName'];?>" /><input name="update" value="update" type="submit" /></form>
<? }else{?>
<form method="post"><input name="routingNumber" value="routingNumber" /><input name="accountNumber" value="accountNumber" /><input name="accountName" value="accountName" /><input name="insert" value="insert" type="submit" /></form>
<? }
$r=@mysql_query("select * from ac_accounts ");
?>
<table>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><a href="?id=<?=$d['accountID']?>">EDIT</a></td>
    	<td><?=$d['routingNumber']?></td>
    	<td><?=$d['accountNumber']?></td>
    	<td><?=$d['accountName']?></td>
	</tr>
<? } ?>    
</table>        