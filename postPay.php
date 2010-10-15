<?
include 'functions.php';
//include 'security.php';
dbConnect();
if (!$_POST[Submit2]){
	if ($_POST[auth] && $_POST[check_no]){
		if ($_POST[server] == '1'){
			$slot='';
		}else{
			$slot=''.$_POST[server].'';
		}
		$contractor_paid='contractor_paid'.$slot;
		$contractor_check='contractor_check'.$slot;
	
	mysql_select_db("core");
	@mysql_query("UPDATE ps_packets SET $contractor_paid='$_POST[auth]', $contractor_check='$_POST[check_no]' where packet_id='$_POST[packet]'");
	
	
	header('Location: postPay.php');
	
	//$error='!';
	}elseif($_POST[auth] && !$_POST[check_no]){
		$error="PLEASE ENTER A CHECK NUMBER!";
	}elseif(!$_POST[auth] && $_POST[check_no]){
		$error="PLEASE ENTER AN AMOUNT TO PAY!";
	}
}
?>


<? 
if (!$_POST[packet] && !$_POST[newPacket]){?>
	<form name="search" id="search" method="post">
    Enter Packet #: <input name="packet" />
    <input type="submit" name="Search" value="Submit" />
    </form><script>document.search.packet.focus()</script>
<? }else{ 
	if ($_POST[newPacket]){
		$packet=$_POST[newPacket];
	}else{
		$packet=$_POST[packet];
	}
mysql_select_db("core");
$q="SELECT * from ps_packets where packet_id='$packet'";
$r=@mysql_query($q) or die("Query: $q<br>".mysql_error());
$d=mysql_fetch_array($r, MYSQL_ASSOC);
if ($d[payAuth] == 1){
echo "<style>body{background-color:#00FF00;}</style>";
}else{
echo "<style>body{background-color:#FF0000;}</style>";
}
?>
<table align="center" style="padding:5px" border="1"><tr><td colspan="2" align="center">
<strong>Packet <?=$packet?> - <? if ($d[payAuth] == 1){echo 'AUTHORIZED';}else{echo 'NEEDS AUTHORIZATION';} ?></strong>
</td></tr>
<tr><td>
    <table>
        <tr>
            <td>Defendants:</td>
        </tr>
        <? if ($d[name1]){?>
        <tr>
            <td><?=$d[name1]?></td>
        </tr>
        <? } ?>
    <? if ($d[name2]){?>
        <tr>
            <td><?=$d[name2]?></td>
        </tr>
    <? } ?>
    <? if ($d[name3]){?>
        <tr>
            <td><?=$d[name3]?></td>
        </tr>
    <? } ?>
    <? if ($d[name4]){?>
        <tr>
            <td><?=$d[name4]?></td>
        </tr>
    <? } ?>
    <? if ($d[name5]){?>
        <tr>
            <td><?=$d[name5]?></td>
        </tr>
    <? } ?>
    <? if ($d[name6]){?>
        <tr>
            <td><?=$d[name6]?></td>
        </tr>
    <? } ?>
    </table>
</td><td>
    <table>
        <tr>
            <td>Addresses:</td>
        </tr>
        <? if ($d[address1]){?>
        <tr>
            <td><?=$d[address1]?>, <?=$d[city1]?>, <?=$d[state1]?> <?=$d[zip1]?></td>
        </tr>
        <? } ?>
        <? if ($d[address1a]){ ?>
        <tr>
            <td><?=$d[address1a]?>, <?=$d[city1a]?>, <?=$d[state1a]?> <?=$d[zip1a]?></td>
        </tr>
        <? } ?>
        <? if ($d[address1b]){ ?>
        <tr>
            <td><?=$d[address1b]?>, <?=$d[city1b]?>, <?=$d[state1b]?> <?=$d[zip1b]?></td>
        </tr>
        <? } ?>
        <? if ($d[address1c]){ ?>
        <tr>
            <td><?=$d[address1c]?>, <?=$d[city1c]?>, <?=$d[state1c]?> <?=$d[zip1c]?></td>
        </tr>
        <? } ?>
        <? if ($d[address1d]){ ?>
        <tr>
            <td><?=$d[address1d]?>, <?=$d[city1d]?>, <?=$d[state1d]?> <?=$d[zip1d]?></td>
        </tr>
        <? } ?>
        <? if ($d[address1e]){ ?>
        <tr>
            <td><?=$d[address1e]?>, <?=$d[city1e]?>, <?=$d[state1e]?> <?=$d[zip1e]?></td>
        </tr>
        <? } ?>
    </table>
</td></tr></table>
<br />
<form id="last" name="last" method="post">
Select Server: <select name="server" size="6">
<? if ($d[server_id]){ ?>
<option selected value="1"><?=serve2name($d[server_id])?> <? if($d[contractor_rate]){echo '($'.$d[contractor_rate].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paid]){echo ' - paid '.$d[contractor_paid].' with '.$d[contractor_check];}?></option>
<? } ?>
<? if ($d[server_ida]){ ?>
<option value="a"><?=serve2name($d[server_ida])?> <? if($d[contractor_ratea]){echo '($'.$d[contractor_ratea].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paida]){echo ' - paid '.$d[contractor_paida].' with '.$d[contractor_checka];}?></option>
<? } ?>
<? if ($d[server_idb]){ ?>
<option value="b"><?=serve2name($d[server_idb])?> <? if($d[contractor_rateb]){echo '($'.$d[contractor_rateb].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paidb]){echo ' - paid '.$d[contractor_paidb].' with '.$d[contractor_checkb];}?></option>
<? } ?>
<? if ($d[server_idc]){ ?>
<option value="c"><?=serve2name($d[server_idc])?> <? if($d[contractor_ratec]){echo '($'.$d[contractor_ratec].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paidc]){echo ' - paid '.$d[contractor_paidc].' with '.$d[contractor_checkc];}?></option>
<? } ?>
<? if ($d[server_idd]){ ?>
<option value="d"><?=serve2name($d[server_idd])?> <? if($d[contractor_rated]){echo '($'.$d[contractor_rated].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paidd]){echo ' - paid '.$d[contractor_paidd].' with '.$d[contractor_checkd];}?></option>
<? } ?>
<? if ($d[server_ide]){ ?>
<option value="e"><?=serve2name($d[server_ide])?> <? if($d[contractor_ratee]){echo '($'.$d[contractor_ratee].')';}else{echo '<i>no rate set</i>';} if ($d[contractor_paide]){echo ' - paid '.$d[contractor_paide].' with '.$d[contractor_checke];}?></option>
<? } ?>
</select><br />
<input type="hidden" name="packet" value="<?=$packet?>" />
Check #: <input name="check_no" value="<?=$_POST[check_no]?>"/><br />
Amount Paid: <input id="auth" name="auth" /><br /><input type="submit" value="Record" />
</form><script>document.last.auth.focus()</script>



<br />
<br />
<div style="font-size:50px;" align="center">Packet <?=$packet?>, Amount <?=$_POST[auth]?>, Check <?=$_POST[check_no]?> Recorded</h1></div>
<? if ($error){ ?>
<div style="font-size:24px; border:solid 2px; background-color:#FF0000" align="center"><?=$error?></div>
<? } ?>
OR ENTER NEW PACKET
<form method="post" name="form2" id="form2">
<input name="newPacket" /><input type="submit" name="Submit2" value="Load New Packet" />
</form>
<a href="postPay.php" target="_self">New / Next</a>
<? } ?>