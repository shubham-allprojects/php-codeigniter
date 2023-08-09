<?
$output_items	= array();
for($i = 1; $i <= 17; $i++)
{
	$output_items[$i] = Input::get('Item_'. $i, false);
}
?>

<script type="text/javascript">
show_loading();
</script>

<div id="location">
<?
echo ' :: '. $lang->menu->logviewer;
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
				<? if($output_items[2])  { ?><th class="item_datetime"><?=$lang->addmsg->item_datetime?></th><? } ?>
				<? if($output_items[1])  { ?><th class="item_date"><?=$lang->addmsg->item_date?></th><? } ?>
				<? if($output_items[3])  { ?><th class="item_time"><?=$lang->addmsg->item_time?></th><? } ?>
				<? if($output_items[12]) { ?><th class="item_localtime"><?=$lang->addmsg->item_localtime?></th><? } ?>
				<? if($output_items[10]) { ?><th class="item_type"><?=$lang->addmsg->item_type?></th><? } ?>
				<? if($output_items[9])  { ?><th class="item_devicename"><?=$lang->addmsg->item_devicename?></th><? } ?>
				<? if($output_items[15]) { ?><th class="item_reader_type"><?=$lang->addmsg->item_reader_type?></th><? } ?>
				<? if($output_items[11]) { ?><th class="item_port"><?=$lang->addmsg->item_port?></th><? } ?>
				<? if($output_items[5])  { ?><th class="item_user"><?=$lang->addmsg->item_user?></th><? } ?>
				<? if($output_items[7])  { ?><th class="item_card_no"><?=$lang->addmsg->item_card_no?></th><? } ?>
				<? if($output_items[6])  { ?><th class="item_user_field"><?=$lang->addmsg->item_user_field?></th><? } ?>
				<? if($output_items[4])  { ?><th class="item_event"><?=$lang->addmsg->item_event?></th><? } ?>
				<? if($output_items[8])  { ?><th class="item_message"><?=$lang->addmsg->item_message?></th><? } ?>
				<? if($output_items[13]) { ?><th class="item_ack"><?=$lang->addmsg->item_ack?></th><? } ?>
				<? if($output_items[14]) { ?><th class="item_ack_message"><?=$lang->addmsg->item_ack_message?></th><? } ?>
                <? if($output_items[16]) { ?><th class="item_site_name"><?=$lang->addmsg->item_site_name?></th><? } ?>
                <? if($output_items[17]) { ?><th class="item_floor_name"><?=$lang->addmsg->item_floor_name?></th><? } ?>
			</tr>
		</tbody>
		<tbody class="tbl_body">
			<?
			while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
			?>
			<tr class="ov">
				<? if($output_items[2])  { ?><td class="item_datetime"><?=date('m-d-Y H:i:s', $row['Date'])?></td><? } ?>
				<? if($output_items[1])  { ?><td class="item_date"><?=date('m-d-Y', $row['Date'])?></td><? } ?>
				<? if($output_items[3])  { ?><td class="item_time"><?=date('H:i:s', $row['Date'])?></td><? } ?>
				<? if($output_items[12]) { ?><td class="item_localtime"><?=date('m-d-Y H:i:s', $row['ClientDate'])?></td><? } ?>
				<? if($output_items[10]) { ?><td class="item_type"><?=EnumTable::$attrLogType[$row['Type']]?></td><? } ?>
				<? if($output_items[9])  { ?><td class="item_devicename"><?=$row['DeviceName']?></td><? } ?>
				<? if($output_items[15]) { ?><td class="item_reader_type"><?=($row['Type'] == 1 ? $this->get_reader_type($row['Port']) : '')?></td><? } ?>
				<? if($output_items[11]) { ?><td class="item_port"><?=$row['Port']?></td><? } ?>
				<? if($output_items[5])  { ?><td class="item_user"><?=$row['UserName']?></td><? } ?>
                <? if($output_items[7])  { ?><td class="item_card_no"><?=($row['CardNumber'] != NULL ? $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")" : '')?></td><? } ?>
				<? if($output_items[6])  { ?><td class="item_user_field"><?=$this->find_user_define($row['UserNo'])?></td><? } ?>
				<? if($output_items[4])  { ?><td class="item_event"><?=$lang->eventcode->{$row['EventCode']}?></td><? } ?>
				<? if($output_items[8])  { ?><td class="item_message"><?=$row['Message']?></td><? } ?>
				<? if($output_items[13]) { ?><td class="item_ack"><?=$row['Ack']?></td><? } ?>
				<? if($output_items[14]) { ?><td class="item_ack_message"><?=$this->get_ack_message($row['No'], $logdb)?></td><? } ?>
                <? if($output_items[16]) { ?><td class="item_site_name"><?=$row['SiteName']?></td><? } ?>
                <? if($output_items[17]) { ?><td class="item_floor_name"><?=$row['FloorName']?></td><? } ?>
			</tr>
			<?
			} // end while
			?>
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
