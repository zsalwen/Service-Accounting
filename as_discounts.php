<?php
// +----------------------------------------------------------------------+
// | AC-CORE                                                              |
// | Auction Publication Commisions						                  |
// | Requirements: Security, Functions                                    |
// +----------------------------------------------------------------------+
// | Author: Patrick McGuire <insidenothing@gmail.com>                    |
// | Published: July 8, 2008   						                      |
// | Updated: n/a														  |
// +----------------------------------------------------------------------+
//error_reporting(E_ALL);
include 'security.php';
include 'functions.php';
dbConnect();

//include 'menu.php';


$month = $_GET[month];
$year = $_GET[year];
$court = $_GET[court];
$date = $year."-".$month;
$client = $_GET[client];


function paperBlock($publisher,$date,$client){
	$i=0;
	$_SESSION[disc1] = 0;
	$_SESSION[disc2] = 0;
	$_SESSION[disc3] = 0;

	
	$q = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court <> 'PG CHS' ORDER BY court, file";
	$r = @mysql_query ($q) or die(mysql_error());
	$r2 = @mysql_query ($q) or die(mysql_error());
	$test = mysql_fetch_array($r2, MYSQL_ASSOC);
	
	$qa = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper2 = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court <> 'PG CHS' ORDER BY court, file";
	$ra = @mysql_query ($qa) or die(mysql_error());
	$ra2 = @mysql_query ($qa) or die(mysql_error());
	$testa = mysql_fetch_array($ra2, MYSQL_ASSOC);
	
	$qb = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper3 = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court <> 'PG CHS' ORDER BY court, file";
	$rb = @mysql_query ($qb) or die(mysql_error());
	$rb2 = @mysql_query ($qb) or die(mysql_error());
	$testb = mysql_fetch_array($rb2, MYSQL_ASSOC);

	if ($test || $testa || $testb){
		?>
		<tr>
			<td colspan="9"><font size="+2"><?=$publisher?></font></td>
		</tr>
		<?
	}
	if ($test){
		// first publication
		while ($data = mysql_fetch_array($r, MYSQL_ASSOC)){
			$i++;
			?>	
			<tr>
				<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$data[schedule_id]?>" target="_blank"><?=$i?></a> (1st)</td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data[county]?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right"><?=$data[sale_date]?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right"><?=$data[sale_time]?></td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data['file']?></td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data[address1]?> <? if ($data[adjusted]){ echo "--== $$data[adjusted] Waived ==--"; }?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($data[auction_fee]-$data[adjusted],2)?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($data[ad_cost],2)?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $data[auction_fee] + $data[ad_cost] - $data[adjusted]; echo number_format($total,2);?></td>
			</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1] + $data[auction_fee] - $data[adjusted];
			$_SESSION[disc2] = $_SESSION[disc2] + $data[ad_cost];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end first publication display
	}
	if ($testa){
		// second publication
		while ($dataa = mysql_fetch_array($ra, MYSQL_ASSOC)){
			$i++;
			?>	
				<tr>
					<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$dataa[schedule_id]?>" target="_blank"><?=$i?></a> (2nd)</td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa[county]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$dataa[sale_date]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$dataa[sale_time]?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa['file']?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa[address1]?> <? if ($dataa[adjusted]){ echo "--== $$dataa[adjusted] Waived ==--"; }?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($dataa[ad_cost2],2)?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $dataa[ad_cost2] + $dataa[ad_cost] - $dataa[adjusted]; echo number_format($total,2);?></td>
				</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1];
			$_SESSION[disc2] = $_SESSION[disc2] + $dataa[ad_cost2];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end second publication display
	 }
	 if ($testb){
		// third publication
		while ($datab = mysql_fetch_array($rb, MYSQL_ASSOC)){
			$i++;
			?>	
				<tr>
					<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$datab[schedule_id]?>" target="_blank"><?=$i?></a> (3rd)</td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab[county]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$datab[sale_date]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$datab[sale_time]?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab['file']?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab[address1]?> <? if ($datab[adjusted]){ echo "--== $$datab[adjusted] Waived ==--"; }?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($datab[ad_cost3],2)?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $datab[ad_cost3] + $datab[ad_cost] - $datab[adjusted]; echo number_format($total,2);?></td>
				</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1];
			$_SESSION[disc2] = $_SESSION[disc2] + $datab[ad_cost3];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end third publication display
	}
 ?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc1],2)?></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc2],2)?></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc3],2)?></td>
	</tr>
<?
$q = "SELECT * FROM papers WHERE publisher = '$publisher'";
$r = @mysql_query ($q) or die(mysql_error());
$data = mysql_fetch_array($r, MYSQL_ASSOC);
$discount = $_SESSION[disc2] * ($data[commission] / 100);
$pay = $_SESSION[disc2] - $discount;
?>	
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right" style="border-style:solid;">$<?=number_format($discount,2)?></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right">$<?=number_format($pay,2)?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="9"><br><br></td>
	</tr>


<? }   // end test and function?>
<script src="javascript/common.js"></script>
<script>document.title = "Paper Discounts";</script>
<style>
body
{
font-family:Arial; 
font:Arial;
}
</style>
<? 

function paperBlock2($publisher,$date,$client){
	$i=0;
	$_SESSION[disc1] = 0;
	$_SESSION[disc2] = 0;
	$_SESSION[disc3] = 0;

	
	$q = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court = 'PG CHS' ORDER BY court, file";
	$r = @mysql_query ($q) or die(mysql_error());
	$r2 = @mysql_query ($q) or die(mysql_error());
	$test = mysql_fetch_array($r2, MYSQL_ASSOC);
	
	$qa = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper2 = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court = 'PG CHS' ORDER BY court, file";
	$ra = @mysql_query ($qa) or die(mysql_error());
	$ra2 = @mysql_query ($qa) or die(mysql_error());
	$testa = mysql_fetch_array($ra2, MYSQL_ASSOC);
	
	$qb = "SELECT * FROM papers, schedule_items WHERE schedule_items.paper3 = papers.paper_name AND papers.publisher = '$publisher' AND schedule_items.sale_date like '$date%' AND schedule_items.attorneys_id = '$client' AND schedule_items.ad_deposit < '1' AND court = 'PG CHS' ORDER BY court, file";
	$rb = @mysql_query ($qb) or die(mysql_error());
	$rb2 = @mysql_query ($qb) or die(mysql_error());
	$testb = mysql_fetch_array($rb2, MYSQL_ASSOC);

	if ($test || $testa || $testb){
		?>
		<tr>
			<td colspan="9"><font size="+2"><?=$publisher?></font></td>
		</tr>
		<?
	}
	if ($test){
		// first publication
		while ($data = mysql_fetch_array($r, MYSQL_ASSOC)){
			$i++;
			?>	
			<tr>
				<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$data[schedule_id]?>" target="_blank"><?=$i?></a> (1st)</td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data[county]?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right"><?=$data[sale_date]?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right"><?=$data[sale_time]?></td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data['file']?></td>
				<td style="font-size:10px" nowrap="nowrap"><?=$data[address1]?> <? if ($data[adjusted]){ echo "--== $$data[adjusted] Waived ==--"; }?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($data[auction_fee]-$data[adjusted],2)?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($data[ad_cost],2)?></td>
				<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $data[auction_fee] + $data[ad_cost] - $data[adjusted]; echo number_format($total,2);?></td>
			</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1] + $data[auction_fee] - $data[adjusted];
			$_SESSION[disc2] = $_SESSION[disc2] + $data[ad_cost];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end first publication display
	}
	if ($testa){
		// second publication
		while ($dataa = mysql_fetch_array($ra, MYSQL_ASSOC)){
			$i++;
			?>	
				<tr>
					<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$dataa[schedule_id]?>" target="_blank"><?=$i?></a> (2nd)</td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa[county]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$dataa[sale_date]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$dataa[sale_time]?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa['file']?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$dataa[address1]?> <? if ($dataa[adjusted]){ echo "--== $$dataa[adjusted] Waived ==--"; }?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($dataa[ad_cost2],2)?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $dataa[ad_cost2] + $dataa[ad_cost] - $dataa[adjusted]; echo number_format($total,2);?></td>
				</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1];
			$_SESSION[disc2] = $_SESSION[disc2] + $dataa[ad_cost2];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end second publication display
	 }
	 if ($testb){
		// third publication
		while ($datab = mysql_fetch_array($rb, MYSQL_ASSOC)){
			$i++;
			?>	
				<tr>
					<td style="font-size:10px" nowrap="nowrap"><a href="discounts_edit.php?id=<?=$datab[schedule_id]?>" target="_blank"><?=$i?></a> (3rd)</td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab[county]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$datab[sale_date]?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"><?=$datab[sale_time]?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab['file']?></td>
					<td style="font-size:10px" nowrap="nowrap"><?=$datab[address1]?> <? if ($datab[adjusted]){ echo "--== $$datab[adjusted] Waived ==--"; }?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right"></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<?=number_format($datab[ad_cost3],2)?></td>
					<td style="font-size:10px" nowrap="nowrap" align="right">$<? $total = $datab[ad_cost3] + $datab[ad_cost] - $datab[adjusted]; echo number_format($total,2);?></td>
				</tr>
			<?
			$_SESSION[disc1] = $_SESSION[disc1];
			$_SESSION[disc2] = $_SESSION[disc2] + $datab[ad_cost3];
			$_SESSION[disc3] = $_SESSION[disc3] + $total;
		} // end third publication display
	}
 ?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc1],2)?></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc2],2)?></td>
		<td style="font-size:10px" align="right">$<?=number_format($_SESSION[disc3],2)?></td>
	</tr>
<?
$q = "SELECT * FROM papers WHERE publisher = '$publisher'";
$r = @mysql_query ($q) or die(mysql_error());
$data = mysql_fetch_array($r, MYSQL_ASSOC);
$discount = $_SESSION[disc2] * ($data[commission] / 100);
$pay = $_SESSION[disc2] - $discount;
?>	
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right" style="border-style:solid;">$<?=number_format($discount,2)?></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="font-size:10px" align="right">$<?=number_format($pay,2)?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="9"><br><br></td>
	</tr>


<? }   // end test and function?>
<script src="javascript/common.js"></script>
<script>document.title = "Paper Discounts";</script>
<style>
body
{
font-family:Arial; 
font:Arial;
}
</style>
<body onLoad="Maximize()">
<? if(!$month){?>
<table align="center" border="1" width="200" bgcolor="#FFFFFF">
<form>

	<tr>
		<td>Client</td>
		<td align="left" valign="middle"><select name="client">
				<?
				mysql_select_db('ccdb');

		$q8 = "SELECT * FROM attorneys where attorneys_id >'0' ORDER BY attorneys_id";		
		$r8 = @mysql_query ($q8) or die(mysql_error());
		while ($data8 = mysql_fetch_array($r8, MYSQL_ASSOC)){ // we need to only display if sales exist
	echo "<option value='$data8[attorneys_id]'>$data8[display_name]</option>";
		}
		?>
</select></td>
	</tr>
	<tr>
		<td>Statement Date</td><td><select name="month"><option><?=$_GET[month]?></option><option>01</option>
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
			<option><?=$_GET[year]?></option>
				<option>2007</option>
				<option>2008</option>
				<option>2009</option>
			</select></td>
	</tr>
		<td colspan="2" align="center"><input type="submit" value="Display These Files" /></td>
	</tr>
</form>
</table>
<hr />
<? }?>



<table border="1" style="border-collapse:collapse" align="right" cellpadding="3">
<?
// query distince papers
mysql_select_db('intranet');
$q = "SELECT DISTINCT publisher FROM papers ORDER BY publisher";
$r = @mysql_query ($q) or die(mysql_error());

while ($data = mysql_fetch_array($r, MYSQL_ASSOC)){



	

	paperBlock($data[publisher],$date,$client);
	paperBlock2($data[publisher],$date,$client);
}
?>
</table>
