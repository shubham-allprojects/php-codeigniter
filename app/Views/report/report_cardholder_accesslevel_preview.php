
<script type="text/javascript">
show_loading();
</script>

<div id="location">
<?
echo ' :: '. $lang->report->cardholder_accesslevel;
?>
	<button class="btn_help" onclick="window.print()">Print</button>
</div>

<div id="list_section">
	<table>
	<tr>
		<td class="datetime" style="color:lightgray;"><?=date('m-d-Y H:i:s')?></td>
		<td class="webuser" style="color:lightgray;" align="right"><?=$_SESSION['spider_name']?> [<?=$_SESSION['spider_id']?>]</td>
	</tr>
	</table>

	<div class="box01" style="padding-bottom: 10px;">
		<table class="tbl_list">
		<tbody class="tbl_header">
			<tr>
				<th><?=$lang->report->no?></th>
				<th><?=$lang->report->id?></th>
				<th><?=$lang->report->lastname?></th>
				<th><?=$lang->report->firstname?></th>
				<th><?=$lang->report->cardnumber?></th>
				<th><?=$lang->report->accesslevel?></th>
				<th><?=$lang->report->doorno?></th>
				<th><?=$lang->report->doorname?></th>
			</tr>
		</tbody>
		<tbody class="tbl_body">
			<? while( $row = $list->fetch(PDO::FETCH_ASSOC) ) { ?>
			<tr class="ov">
				<td><?=$rownum++?></td>
				<td><?=$row['No']?></td>
				<td><?=$row['LastName']?></td>
				<td><?=$row['FirstName']?></td>
                <td><?=($row['CardNumber'] != NULL ? $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")" : '')?></td>
				<td><?=$row['AccessLevelName']?></td>
				<td><?=$row['DoorNo']?></td>
				<td><?=$row['DoorName']?></td>
			</tr>
			<? } // end while ?>
		</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	hide_loading();
	//window.print();
});
</script>
