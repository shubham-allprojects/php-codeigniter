
<script type="text/javascript">
show_loading();
</script>

<div id="location">
<?
echo ' :: '. $lang->report->muster;
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
                <th><?=$lang->report->date?></th>
				<th><?=$lang->report->musterregion?></th>
				<th><?=$lang->report->prevregion?></th>
				<th><?=$lang->report->cardholder?></th>
				<th><?=$lang->report->cardnumber?></th>
			</tr>
		</tbody>
		<tbody class="tbl_body">
			<?
            while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
                if( $row['PrRegionNo'] == '' || $row['PrRegionNo'] == '0' ) {
                    $row['PrevRegionName'] = $this->lang->door->uncontrolled_space;
                }
            ?>
			<tr class="ov">
                <td><?=date('m-d-Y H:i:s', $row['MuTagDate'])?></td>
				<td><?=$row['MusterRegionName']?></td>
				<td><?=$row['PrevRegionName']?></td>
				<td><?=$row['FirstName']?> <?=$row['LastName']?></td>
                <td><?=($row['CardNumber'] != NULL ? $row['CardNumber']."(".$row['FacilityCode'].")" : '')?></td>
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
