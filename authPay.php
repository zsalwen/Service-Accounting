<?
include 'functions.php';
//include 'security.php'; 
dbAlphaConnect();

if ($_POST[auth]){


$r=@mysql_query("select * from ps_packets where packet_id='$_POST[auth]'");
$d=mysql_fetch_array($r,MYSQL_ASSOC);
if ($d[payAuth] == 0){
echo "Payment authorized for packet $_POST[auth].<br><style>body{background-color:#00FF00;}</style>";
@mysql_query("UPDATE ps_packets SET payAuth = '1' where packet_id='$_POST[auth]'");
}else{
echo "$_POST[auth] has already been authorized. [cc $d[contractor_check]] [cca $d[contractor_checka]] [ccb $d[contractor_checkb]] [ ccc $d[contractor_checkc]] [ ccd $d[contractor_checkd]] [ cce $d[contractor_checke]] <style>body{background-color:#FF0000;}</style><br>";
}

}
?>



<form id="last" name="last" method="post">
Enter packet number to pay <input id="auth" name="auth" /> <input type="submit" value="Authorize" />
</form><script>document.last.auth.focus()</script>



<br />
<br />
<br />
<br />
<br />
<br />
<br />
<div style="font-size:100px;" align="center"><?=$_POST[auth]?></h1>