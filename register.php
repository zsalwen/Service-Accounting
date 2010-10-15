<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Account Register                					                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 11, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();
$balance='';
if (isset($_POST['insert'])){
@mysql_query("insert into ac_register (trans, accountID, codeID, userID, status, detail, amount, entered, checkNumber) values ('$trans', '$accountID', '$codeID', '$userID', '$status', '$detail', '$amount', NOW(), '$checkNumber')") or die(mysql_error());
header('Location: register.php?account='.$accountID);
}
if (isset($_POST['post'])){
@mysql_query("update ac_register set checkNumber='".$_POST['checkNumber']."', trans='".$_POST['trans']."', accountID='".$_POST['accountID']."', codeID='".$_POST['codeID']."', userID='".$_POST['userID']."', status='".$_POST['status']."', detail='".$_POST['detail']."', amount='".$_POST['amount']."', posted=NOW()  where registerID = '".$_POST['registerID']."'") or die(mysql_error());
header('Location: register.php?account='.$accountID);
}
if (isset($_POST['recon'])){
@mysql_query("update ac_register set checkNumber='".$_POST['checkNumber']."', trans='".$_POST['trans']."', accountID='".$_POST['accountID']."', codeID='".$_POST['codeID']."', userID='".$_POST['userID']."', status='".$_POST['status']."', detail='".$_POST['detail']."', amount='".$_POST['amount']."', recon=NOW()  where registerID = '".$_POST['registerID']."'") or die(mysql_error());
header('Location: register.php?account='.$accountID);
}
include 'menu.php';
// account bar to set get varriable
?><table width="100%"><tr><td><a href="?account=5">Process Serving</a></td><td><a href="?account=7">AMEX</a></td></tr></table><?
if (isset($_GET['account'])){
if (isset($_GET['id'])){
$r=@mysql_query("select * from ac_register where registerID = '".$_GET['id']."'");
$d=mysql_fetch_array($r, MYSQL_ASSOC);
?>
<form method="post"><a href="?account=<?=$_GET['account'];?>">NEW</a>
	<select name="trans"><option><?=$d['trans'];?></option><option>DEPOSIT</option><option>WITHDRAW</option></select>
    <input type="hidden" name="registerID" value="<?=$_GET['id'];?>" />
    <input type="hidden" name="accountID" value="<?=$d['accountID'];?>" />
    <select name="codeID"><?=codeSelect($d['codeID'])?></select>
    <input name="checkNumber" value="<?=$d['checkNumber'];?>" />
    <input type="hidden" name="userID" value="<?=$_COOKIE['userdata']['user_id'];?>" />
    <input type="hidden" name="status" value="POSTED" />
    <input name="detail" value="<?=$d['detail'];?>" />
    <input name="amount" value="<?=$d['amount'];?>" />
    <input name="post" value="post" type="submit" />
</form>
<? }elseif(isset($_GET['id2'])){
$r=@mysql_query("select * from ac_register where registerID = '".$_GET['id2']."'");
$d=mysql_fetch_array($r, MYSQL_ASSOC);
?>
<form method="post"><a href="?account=<?=$_GET['account'];?>">NEW</a>
	<select name="trans"><option><?=$d['trans'];?></option><option>DEPOSIT</option><option>WITHDRAW</option></select>
	<input type="hidden" name="registerID" value="<?=$_GET['id2'];?>" />
    <input type="hidden" name="accountID" value="<?=$d['accountID'];?>" />
    <select name="codeID"><?=codeSelect($d['codeID'])?></select>
    <input name="checkNumber" value="<?=$d['checkNumber'];?>" />
    <input type="hidden" name="userID" value="<?=$_COOKIE['userdata']['user_id'];?>" />
    <input type="hidden" name="status" value="RECONCILED" />
    <input name="detail" value="<?=$d['detail'];?>" />
    <input name="amount" value="<?=$d['amount'];?>" />
    <input name="recon" value="recon" type="submit" />
</form>
<? }else{?>
<form method="post">
	<select name="trans"><option>DEPOSIT</option><option>WITHDRAW</option></select>
    <input type="hidden" name="accountID" value="<?=$_GET['account']?>" />
    <select name="codeID"><?=codeSelect($d['codeID'])?></select>
    <input name="checkNumber" value="checkNumber" />
    <input type="hidden" name="userID" value="<?=$_COOKIE['userdata']['user_id'];?>" />
    <input type="hidden" name="status" value="ENTERED" />
    <input name="detail" value="detail" />
    <input name="amount" value="amount" />
    <input name="insert" value="insert" type="submit" />
</form>
<? }
if(isset($_GET['id3'])){ @mysql_query("update ac_register set codeID='0', status='VOID', amount='', trans='VOID' where registerID = '".$_GET['id3']."'"); }
$limit = $_GET[limit];
if ($limit){
	$r=@mysql_query("select * from ac_register where accountID = '".$_GET['account']."' order by entered DESC, checkNumber limit 0, $limit ");
}else{
	$r=@mysql_query("select * from ac_register where accountID = '".$_GET['account']."' order by entered DESC, checkNumber ");
}
?>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
	<tr>
    	<td>Check</td>
    	<td>Code</td>
    	<td>Processed By</td>
    	<td>Check Detail</td>
    	<td>Date Cut</td>
    	<td>Check Amount</td>
	</tr>
<? while($d=mysql_fetch_array($r, MYSQL_ASSOC)){?>
	<tr>
    	<td><?=$d['checkNumber']?></td>
    	<td><?=$d['codeID'];?></td>
    	<td><?=id2name($d['userID'])?></td>
    	<td><?=$d['detail']?></td>
    	<td><?=$d['entered']?></td>
    	<td><? 
		if($d['trans'] == "WITHDRAW"){
			//echo "-";
			$balance = $balance - $d['amount'];
		}else{
			$balance = $balance + $d['amount'];
		}	?>$<?=number_format($d['amount'],2)?></td>
	</tr>
<? } ?> 
</table>   

<? }else{ ?>    
Please Select Account
<? } ?>    
     