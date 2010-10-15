<?
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Process Server Checks                				                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 7, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
include 'security.php';
include 'functions.php';
dbConnect();
function serve2name($id){
	$q="SELECT name FROM ps_users WHERE id = '$id'";
	$r=@mysql_query($q);
	$d=mysql_fetch_array($r, MYSQL_ASSOC);
	return $d[name];
}
include 'menu.php';

?>
<table>
<?
$r=@mysql_query("SELECT DISTINCT server_id FROM ps_packets WHERE process_status = 'INVOICED'");
$i=0;
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){$i++; ?>
	<tr bgcolor="<?=row_color($i,'#FFFFCC','#99ccff')?>">
		<td align="center"><a href="/AC/check.php?id=<?=$d['server_id']?>" target="_blank"><?=serve2name($d['server_id']);?></a></td>
    </td>

<? } ?>
<?
$r=@mysql_query("SELECT DISTINCT server_ida FROM ps_packets WHERE process_status = 'INVOICED'");
while ($d=mysql_fetch_array($r, MYSQL_ASSOC)){$i++; ?>
	<tr bgcolor="<?=row_color($i,'#FFFFCC','#99ccff')?>">
		<td align="center"><a href="/AC/checka.php?id=<?=$d['server_ida']?>" target="_blank"><?=serve2name($d['server_ida']);?></a></td>
    </td>

<? } ?>
</table>
<? if ($i == 0)
	echo "No checks to print";
	?>