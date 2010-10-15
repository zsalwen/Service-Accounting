<?
dbConnect('delta.mdwestserve.com','intranet','','');
if ($_GET[id] == "last"){
	$q77 = "SELECT * FROM schedule_items where created_id = '".$_COOKIE[userdata][user_id]."' ORDER BY schedule_id DESC";
	$r77 = @mysql_query ($q77) or die(mysql_error());
	$d77 = mysql_fetch_array($r77, MYSQL_ASSOC);
	$did = $d77[schedule_id];
	echo "
<script>
function proof(){
	if (confirm(\"Print the Data Sheet?\")) {
		window.open('proof.php?id=$did','proof');
	};
}
function ad(){
	if (confirm(\"Print the Ad?\")) {
		window.open('ad.php?id=$did&preview=1','ad');
	};
}
</script>	";
	echo "<script>ad(); proof();</script>";
}else{
	$did = $_GET[id];
}
	hit($did);
	mysql_select_db('intranet');
	$q3 = "SELECT *, DATE_FORMAT(item_datetime,'%M %D, $Y at %l:%i%p') as item_datetime_f, DATE_FORMAT(item_date,'%M %D, $Y at %l:%i%p') as item_date_f, DATE_FORMAT(update_date,'%M %D, $Y at %l:%i%p') as update_date_f FROM schedule_items WHERE schedule_id = '$did'";		
	$r3 = @mysql_query ($q3) or die(mysql_error());
	$data3 = mysql_fetch_array($r3, MYSQL_ASSOC);
	$q4 = "SELECT name FROM users WHERE user_id = '$data3[created_id]'";		
	$r4 = @mysql_query ($q4) or die(mysql_error());
	$data4 = mysql_fetch_array($r4, MYSQL_ASSOC);
	if ($data3[updated_id] != 0){
	$q5 = "SELECT name FROM users WHERE user_id = '$data3[updated_id]'";		
	$r5 = @mysql_query ($q5) or die(mysql_error());
	$data5 = mysql_fetch_array($r5, MYSQL_ASSOC);
	}
	mysql_select_db ('ccdb');
	$q6 = "SELECT * FROM attorneys WHERE attorneys_id = '$data3[attorneys_id]'";		
	$r6 = @mysql_query ($q6) or die(mysql_error());
	$data6 = mysql_fetch_array($r6, MYSQL_ASSOC);
	mysql_select_db ('intranet');

$_SESSION[details] = $data3;
setLocation("Viewing details for $data3[address1] ($data3[sale_date] @ $data3[sale_time])");
log_action($_COOKIE[userdata][user_id],"Loaded details for $data3[address1] ($data3[sale_date] @ $data3[sale_time])");

$mouseover = "onmouseover=\"style.backgroundColor='#FFFF00';\" onmouseout=\"style.backgroundColor='#FFFFFF'\"";

if ($_GET[undo]){
 @mysql_query("UPDATE schedule_items SET item_status='ON SCHEDULE', pending_cancel='', pending_by='', pending_on='', pending_ip='' WHERE schedule_id='$_GET[id]'");
	addNote($_GET[id],$_COOKIE[userdata][name].': Undid client cancellation');

}

?>
<script type="text/javascript">
function deleteItem(){
	if (confirm("Are you SURE you want to completely DELETE this entry from the database?!")) {
		//alert("Deleting ID<?=$did?>")
		window.open('delete.php?id=<?=$did?>','delete');
	};
}

</script>
<style>
.left{text-align:left;}
a {text-decoration:none}
</style>
<?
// lets have some fun now... 
@mysql_query("INSERT INTO auction_views (user_id, auction_id, stamp_date) values ('".$_COOKIE[userdata][user_id]."', '$data3[schedule_id]', NOW())");
?>
<table border="1" width="100%">
	<tr>
		<td class="left" valign="top" bgcolor="#CCFFFF" width="200px">
		<div style="font-size:18px; color:#6699CC;" align="center">&gt; Documents &lt; </div>
        
		<? if ($data3[pending_cancel]){?>
		<li><a href="cancel_report.php?id=<?=$data3[schedule_id];?>" target="_blank">Cancellation Report</a></li>
		<li><a href="?id=<?=$data3[schedule_id];?>&undo=1" target="_blank">Undo Cancellation</a></li>
		<? }?>
		<li><a href="proof.php?id=<?=$data3[schedule_id];?>" target="_blank">Data Sheet</a></li>
			

<li><a href="templates/merge.php?page=labels&internal=<?=$data3[schedule_id];?>" target="_blank">This Folders Label</a></li>
<hr />
<li><a href="templates/merge.php?page=presale&internal=<?=$data3[schedule_id];?>" target="_blank">Presale Papers</a></li>
<li><a href="templates/merge.php?page=memo&internal=<?=$data3[schedule_id];?>" target="_blank">Memorandum of Sale</a></li>
<li><a href="templates/merge.php?page=postsale&internal=<?=$data3[schedule_id];?>" target="_blank">Postsale Papers</a></li>
<hr />
<li><a href="add2label-queue.php?id=<?=$data3[schedule_id];?>" target="_blank">Label Queue (+)</a></li>
<li><a href="add2label-queue.php?id=clear" target="_blank">Label Queue (-)</a></li>

<li><a href="templates/label-queue-hp6200.php" target="_blank">Label Queue (Print)</a></li>


<hr />
<? 
$sale_date = explode('-',$data3["sale_date"]); // we need to convert the sale date 
$month = $sale_date["1"];
$day = $sale_date["2"];
$year = $sale_date["0"];
$file_month = strtoupper(date('M',mktime(0,0,0,$month,$day,$year)));//"JANUARY";													
$day_of_sale2 = strtoupper(date('j',mktime(0,0,0,$month,$day,$year)));//"20"; 														
$filename = $data3["file"]."-".$file_month.$day_of_sale2.".PDF";
$filename2 = $data3["file"]."-".$file_month.$day_of_sale2.".PDF";
$filename = "invoices/$data6[display_name]/$filename";
if (file_exists($filename)){ ?>
	<li><a href="<?=$filename?>" target="_blank">View Invoice</a></li>
	<li>	<a href="custom_email_invoice.php?id=<?=$data3[schedule_id]?>&folder=invoices/<?=$data6[display_name]?>/&file=<?=$filename2?>" target="_blank">Custom E-Mail Invoice</a></li>
<li><a href="write_invoice.php?id=<?=$data3[schedule_id];?>" target="_blank">Save Updated Invoice</a></li>
<? }else{ ?>

<? if($data3[invoice_date]){?><li><a href="write_invoice.php?id=<?=$data3[schedule_id];?>" target="_blank">Save and Open Invoice</a></li><? }?>

<? }?>
<? if ($data3[dot_date]){?>
<hr />

<?
$dir = './ads/'.$data6["display_name"];
function t2t($full){
	$format = explode('-',$full);
	$month = $format[1];
	$day = $format[2];
	$year = $format[0];
	$format = date('Md',mktime(0, 0, 0, $month, $day,  $year));
	return strtoupper($format);
}


$address = explode(' ',$data3[address1]);
$sale = t2t($data3[sale_date]);


$file="$address[0] $address[1] $address[2].$sale";// set a file name
$filename = $file.".doc";
$fname = $dir.'/'.$filename;
$fname2 = $dir.'/'.$file;


//if (file_exists($fname)){ ?>


<li><a href="ad.php?id=<?=$data3[schedule_id];?>" target="_blank">Update Ad</a></li>
<li><a href="ad.php?id=<?=$data3[schedule_id];?>&preview=1" target="_blank">Preview Ad</a></li>
<li><a href="email_ad.php?id=<?=$data3[schedule_id]?>&folder=ads/<?=$data6[display_name]?>/&file=<?=$filename?>" target="_blank">E-Mail Ad to Paper</a></li>
<? } /*else{ ?>
<li><a href="ad.php?id=<?=$data3[schedule_id];?>" target="_blank">Save Ad</a></li>
<? }}*/?>


</td>
		<td class="left" valign="top" bgcolor="#FFFF99" width="200px">
		<div style="font-size:18px; color:#6699CC" align="center">&gt; E-Mail &lt; </div>
			<li><a href="adjust.php?id=<?=$data3[schedule_id];?>" target="_blank">Adjust Pub Cost</a></li><? if($data3[ad_cost]){ ?><li><a href="finalpub.php?id=<?=$data3[schedule_id];?>" target="_blank">Send Final Pub Cost</a></li><? }?><li><a style="text-decoration:none;" href="invoice.php?id=<?=$did?>" target="_blank">Invoice Sale</a></li><li><a style="text-decoration:none;" href="cancel.php?id=<?=$did?>" target="_blank">Cancel: Invoice</a></li><li><a style="text-decoration:none;" href="cancel-hold.php?id=<?=$did?>" target="_blank">Cancel: Cancel Ad</a></li>
            <? if ($data3[state] == "DC"){?>

<li><a href="postpone.php?id=<?=$data3[schedule_id];?>" target="_blank">Postpone Sale</a></li>
<? }?>

			<li><a onclick="deleteItem()">Delete</a></li>
		</td>
		<td class="left" valign="top" bgcolor="#66FF99" width="200px">
		<div style="font-size:18px; color:#6699CC" align="center">&gt; Update &lt; </div>
        <li><a href="edit.php?id=<?=$did?>" target="_blank">Sale Data</a></li>
		<li><a href="alt_paper.php?id=<?=$did?>" target="_blank">Publications</a></li>
		<li><a onclick="window.open('add_note.php?id=<?=$did?>','edit4','width=330,height=300,toolbar=no,statusbar=no,location=no')">Add Note</a></li>
			<li><a onclick="window.open('schedule_duplicate.php?id=<?=$did?>','edit2','width=330,height=750,toolbar=no,location=no')">Duplicate File</a></li>
			<li><a onclick="window.open('geocode.php?id=<?=$did?>','edit2','width=200,height=100,toolbar=no,location=no')">Geocode</a></li>
		</td>
	</tr>
</table>        

<table border="1" cellpadding="0" cellspacing="0" >
	<tr>
    	<td style="padding:5px" height="20px" colspan="2" bgcolor="<? if ($data3[item_status] == "ON SCHEDULE"){ echo "#66FF99"; }else{ echo "#FF0000"; }?>">
    <?
		$sale = explode('-',$data3[sale_date]);
		$sale = $sale[1]."/".$sale[2]."/".$sale[0];
		?>            
        
        	Auction #<?=$data3[schedule_id]?> <br />		
			<?=$data3[address1];?>, <?=$data3[state];?><br />
            <? if ($data3['private']){ echo "Not on Website <br>";}?>
	        <?=$sale;?> <?=$data3[sale_time];?> <br />
            <?=$data3[item_status]?> <br />
            Viewed <?=$data3[hits];?> Times
        
        
        </td>
    </tr>
    <tr>
    	<td valign="top" width="50%">
<table align="center" width="100%">
	<tr>
    	<td class="left" valign="top">

			<table>
                	<td class="left" valign="top">
Access Log<br />
        <? 
        $qn="SELECT *, DATE_FORMAT(stamp_date,'%b %e %h:%i%p') as stamp_date_f FROM auction_views WHERE auction_id = '$data3[schedule_id]' order by stamp_date DESC";
		$rn=@mysql_query($qn);
		while ($dn=mysql_fetch_array($rn, MYSQL_ASSOC)){?>
		<?=$dn[stamp_date_f]?>:  <?=id2name($dn[user_id])?><br>
		<? } ?>

					</td>
                </tr>
           </table>


        </td>
    </tr>
<tr>
<td colspan="2">
</td>
</tr>
</table>
<!-- start tab info -->

<!-- End tab info -->

<table width="100%">
	<tr>
    	<td style="text-align:left; font-size:14px">
        <? 
		mysql_select_db ('core');
        $qps="SELECT *, DATE_FORMAT(date_received,'%b %e %h:%i%p') as date_received_f FROM ps_packets WHERE client_file = '".$data3['file']."'";
		$rps=@mysql_query($qps) or die(mysql_error());
		while ($dps=mysql_fetch_array($rps, MYSQL_ASSOC)){
			echo "<fieldset><legend><a href='?packet=$dps[packet_id]'>Load Service Packet $dps[packet_id]</a></legend><li>Received on $dps[date_received_f]</li>
			<li>Service Status: <strong>$dps[service_status]</strong></li>
			<li>Filing status: <strong>$dps[filing_status]</strong></li>
			<li>Mail status: <strong>$dps[mail_status]</strong></li>
			<li>Green Card status: <strong>$dps[gcStatus]</strong></li>
			<li>Notes to Auction Staff: <strong>$dps[auctionNote]</strong></li>
	";		
	$q2="SELECT * from ps_affidavits where packetID = '$_GET[id]'"; 
	$r2=@mysql_query($q2) or die("Query $q2<br>".mysql_error());
	$d2=mysql_num_rows($r2);
	if ($d2 > 0){
?>        
        <tr>
            <td>
            
            <?
			while ($d3=mysql_fetch_array($r2, MYSQL_ASSOC)){
				echo "<li><a target='_blank' href='$d3[affidavit]'>$d3[method]</a></li>";
			}
			}
		echo "</fieldset>";
			$serve_found=1;
		}
		if (!$serve_found){
			echo "File ".$data3['file']." <em>not served</em> by MDWestServe.com<br>";
		}
		mysql_select_db ('intranet');
		?>

        
        
        
        
        	<strong>Portal Notes:</strong><br />
        <? 
        $qn="SELECT *, DATE_FORMAT(action_on,'%b %e %h:%i%p') as action_on_f FROM portal_notes WHERE action_file = '$_GET[id]'";
		$rn=@mysql_query($qn);
		while ($dn=mysql_fetch_array($rn, MYSQL_ASSOC)){
		echo "$dn[action_on_f]: $dn[action]<br>";
		}
		?>
		<hr />
        <strong>Staff Notes:</strong><br /><?=str_replace(',', '<br>', $data3[notes]);?>
        <hr />
		</td>
    </tr> 
</table>
<table id="pub"  bgcolor="#FFFFFF">
	<tr <? if (!$data3[car]){ echo "bgcolor='#FFCCFF'"; } ?>>
		<td valign="top">Publication Info</td>
		<td class="left">
			<li>Paper: <strong><?=$data3[paper];?></strong></li>
            <li>C.O.P.:<strong><?=$data3[cop];?></strong></li>
			<li>Ad Confirmed: <strong><?=paper2contact($data3[car_contact]);?> by <?=$data3[car_type]?> -  <?=id2tag($data3[car_id])?></strong></li>
			<li>Ad Cost: $<strong><?=number_format($data3[ad_cost],2);?></strong></li>
			<li>Ad Number: <strong><?=$data3[ad_number];?></strong></li>
			<? if ($data3[paper2]){ ?>	
			<li>Paper2: <strong><?=$data3[paper2];?></strong></li>
			<li>Ad Cost2: $<strong><?=number_format($data3[ad_cost2],2);?></strong></li>
			<li>Ad Number2: <strong><?=$data3[ad_number2];?></strong></li>
			<? } if ($data3[paper3]){ ?>
			<li>Paper3: <strong><?=$data3[paper3];?></strong></li>
			<li>Ad Cost3: $<strong><?=number_format($data3[ad_cost3],2);?></strong></li>
			<li>Ad Number3: <strong><?=$data3[ad_number3];?></strong></li>
			<? } ?>
			<li>First Run: <strong><?=$data3[ad_start];?></strong></li>
			<li>Pub Dates: <strong><?=$data3[pub_dates];?></strong></li>
			<li>First Run Confirmed: <? if ($data3[cfr]){ echo "YES";} else { echo "NO";}?> - <?=id2tag($data3[cfr_id])?></li>
			<li>Second Run Confirmed: <? if ($data3[csr]){ echo "YES";} else { echo "NO";}?> - <?=id2tag($data3[csr_id])?></li>
			<li>Third Run Confirmed: <? if ($data3[ctr]){ echo "YES";} else { echo "NO";}?> - <?=id2tag($data3[ctr_id])?></li>
		</td>
	</tr>
</table>

<table id="acc"  bgcolor="#FFFFFF">
	<tr>
		<td valign="top">Accounting Information</td>
		<td class="left">
			<li>Auction Fee: $<strong><?=number_format($data3[auction_fee],2);?></strong></li>
			<li>Check 1: $<strong><?=number_format($data3[paid],2);?> - <?=$data3[check_number]?></strong></li>
			<li>Check 2: $<strong><?=number_format($data3[paid2],2);?> - <?=$data3[check_number2]?></strong></li>
			<li>Check 3: $<strong><?=number_format($data3[paid3],2);?> - <?=$data3[check_number3]?></strong></li>
			<li>Adjusted: $<strong><?=number_format($data3[adjusted],2);?></strong></li>
		</td>
	</tr>
</table>

<table id="prop"  bgcolor="#FFFFFF">
	<tr>
		<td valign="top">Property Information</td>
		<td class="left">
			<script>document.title = "<?=$data3[address1];?>, <?=$data3[state];?> <?=$data3[zip];?>";</script>
			<div style="padding:10px; font-weight:bold">
			
			<?=$data3[address1];?><br />
			<?=$data3[city];?>, <?=$data3[zip];?> <?=$data3[state];?>
			</div>
			<? if($data3[ground_rent]){ ?><li>Ground Rent: $<?=$data3[ground_rent]?></li><? } ?>
			<li>Latitude: <strong><? if ($data3[lat] =="0"){echo "Unable to Geocode"; }else{ echo $data3[lat];}?></strong></li>
			<li>Longitude: <strong><? if ($data3[lng] =="0"){echo "Unable to Geocode"; }else{ echo $data3[lng];}?></strong></li>
			<li>County: <strong><?=$data3[county];?></strong></li>
		</td>
	</tr>
</table>

<table id="sale"  bgcolor="#FFFFFF">
	<tr>
		<td valign="top">Sale Information</td>
		<td class="left">
			<li>Deposit: $<strong><?=number_format($data3[ad_deposit],2);?></strong></li>
			<li>Sold Amount: $<strong><?=number_format($data3[sold_price],2);?></strong></li>
			<li>Sold to: <?=$data3[purchaser]?>, <?=$data3[purchaser_phone]?><br />
			<?=$data3[purchaser_address]?></li>
		</td>
	</tr>
</table>



<table id="system"  bgcolor="#FFFFFF">
	<tr>
		<td valign="top">System Data</td>
		<td class="left">
			<li>Internal Id: <strong>HWA<?=$data3[schedule_id];?></strong></li>
			<li>Entered: <? if ($data3[item_datetime_f]){ echo $data3[item_datetime_f];}else{ echo $data3[item_date_f];}; ?></li>
			<li>Entered By: <strong><?=$data4[name];?></strong></li>
		</td>
	</tr>
</table>

<table id="status"  bgcolor="#FFFFFF">
	<tr>
		<td class="left">
		<li>Auctioneer: <strong><?=$data3[auctioneer]?>, <?=$data3[auctioneer2]?></strong></li>
		<li>Courthouse: <strong><?=$data3[court];?></strong></li>
		<li>Deposit: $<strong><?=$data3[deposit];?></strong>K</li>
		<li>Ad Set By: <strong><?=id2contact($data3[requested_by])?></strong></li>
		<li>Canceled By: <strong><?=id2contact($data3[canceled_by])?></strong></li></td>
	</tr>
</table>

<table id="att"  bgcolor="#FFFFFF">
	<tr <? if ($data6[display_name] == "BURSON" && !$data3[clr]){ echo "bgcolor='#FFCCFF'";}?>>
			<?
			if ($data3[sub_trust]){
				$trust = $data6[trust_names].", ".$data3[sub_trust];		
				$trust = str_replace(',',',<br>',$trust).",";							
			}else{
				$trust = $data6[trust_names];		
				$trust = str_replace(',',',<br>',$trust).",";							
			}
			$fault = $data3[legal_fault];								
			$fault = str_replace(',',',<br>',$fault);									
			?>
        <td valign="top"><strong>&gt; <?=$data6[display_name];?> FILE &lt; </strong><br />
		  <strong><?=$data3[last_fault];?></strong><div style="background-color:#99FF00; font-size:24px"><?=$data3['file'];?></div>
		  <? if ($data6[display_name] == "BURSON"){ 
		  mysql_select_db('intranet');
		  ?>
		  <hr /><? if ($data3[clr]){ echo "Confirmed by ".id2tag($data3[clr_id]);} else { echo "Not Confirmed";}?>
		  <? } ?>
		  <? if ($data3[ad_deposit] && $data3[sold_price]){ echo "<hr>Sold Third Party";} ?>
		  <? if (!$data3[ad_deposit] && $data3[sold_price]){ echo "<hr>Sold Back to Lender";} ?>
		  
		</td>
		<td class="left">
			<div style="padding:10px;">
			<strong><?=$trust?><br />
			<?=$data3[trust_type];?></strong>
			<br />
			VS
			<br />
			<strong><?=$fault?></strong>
			</div>
		</td>
	</tr>
</table>





</td></tr></table>
<? //$alert2 .= "...is everything right?";?>
