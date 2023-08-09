
<script type="text/javascript">
show_loading();
</script>

<div id="location">
<?
echo ' :: '. $lang->report->card;
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
				<th><?=$lang->report->cardnumber?></th>
				<th><?=$lang->report->cardformat?></th>
				<th><?=$lang->report->cardstatus?></th>
                <th><?=$lang->report->cardtype?></th>
				<th><?=$lang->report->id?></th>
				<th><?=$lang->report->lastname?></th>
				<th><?=$lang->report->firstname?></th>
				<th><?=$lang->report->phonenumber?></th>
			</tr>
		</tbody>
		<tbody class="tbl_body">
        <?
        $today = date('Y-m-d');

        while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
            $CardStatus = EnumTable::$attrCardStatus[$row['CardStatus']];
            if($row['NeverExpire'] == 0) {
                if($row['ActDate'] != '0' && $row['ActDate'] != '' && $today < date('Y-m-d', $row['ActDate'])) {
                    $CardStatus .= ' ('. $this->lang->card->inactive .')';
                }
                if($row['ExpDate'] != '0' && $row['ExpDate'] != '' && $today > date('Y-m-d', $row['ExpDate'])) {
                    $CardStatus .= ' ('. $this->lang->card->expired .')';
                }
            }
        ?>
			<tr class="ov">
				<td><?=$rownum++?></td>
                <td><?=($row['CardNumber'] != NULL ? $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")" : '')?></td>
				<td><?=$row['CardFormatName']?></td>
				<td><?=$CardStatus?></td>
                <td><?=EnumTable::$attrCardType[$row['CardType']]?></td>
				<td><?=$row['No']?></td>
				<td><?=$row['LastName']?></td>
				<td><?=$row['FirstName']?></td>
				<td><?=$row['Phone']?></td>
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
