
<table class="tbl_list">
<tbody class="tbl_header">
	<tr>
		<th class="item_datetime"><?=$lang->addmsg->item_datetime?></th>
		<th class="item_date"><?=$lang->addmsg->item_date?></th>
		<th class="item_time"><?=$lang->addmsg->item_time?></th>
		<th class="item_localtime"><?=$lang->addmsg->item_localtime?></th>
		<th class="item_type"><?=$lang->addmsg->item_type?></th>
		<th class="item_devicename"><?=$lang->addmsg->item_devicename?></th>
		<th class="item_reader_type"><?=$lang->addmsg->item_reader_type?></th>
		<th class="item_port"><?=$lang->addmsg->item_port?></th>
		<th class="item_user"><?=$lang->addmsg->item_user?></th>
		<th class="item_card_no"><?=$lang->addmsg->item_card_no?></th>
		<th class="item_user_field"><?=$lang->addmsg->item_user_field?></th>
		<th class="item_event"><?=$lang->addmsg->item_event?></th>
		<th class="item_message"><?=$lang->addmsg->item_message?></th>
		<th class="item_ack"><?=$lang->addmsg->item_ack?></th>
		<th class="item_ack_message"><?=$lang->addmsg->item_ack_message?></th>
        <? if($this->is_option(ConstTable::OPTION_PARTITION)) { ?>
            <th class="item_site_name"><?=$lang->site->Name?></th>
        <? } ?>        
        <th class="item_floor_name"><?=$lang->cfloor->Name?></th>
	</tr>
</tbody>
<tbody class="tbl_body">
	<?
	while( $row = $list->fetch(PDO::FETCH_ASSOC) ) {
        $event_name = $lang->eventcode->{$row['EventCode']};

        if($row['EventCode'] == '730') {
			if( $row['Res1'] > 0 ) {
				$camera_name = $this->get_camera_name($row['Res1']);
                $camera_type = $this->get_camera_type($row['Res1']);
				$event_name .= "&nbsp;({$camera_name})&nbsp;&nbsp;<a href=\"javascript:openLogViewer('{$row['No']}', '{$row['Res1']}', '{$camera_type}');\"><img src=\"/img/videoclip.png\" /></a>";
			}
        }
	?>
	<tr class="ov">
		<td class="item_datetime"><?=date('m-d-Y H:i:s', $row['Date'])?></td>
		<td class="item_date"><?=date('m-d-Y', $row['Date'])?></td>
		<td class="item_time"><?=date('H:i:s', $row['Date'])?></td>
		<td class="item_localtime"><?=date('m-d-Y H:i:s', $row['ClientDate'])?></td>
		<td class="item_type"><?=EnumTable::$attrLogType[$row['Type']]?></td>
		<td class="item_devicename"><?=$row['DeviceName']?></td>
		<td class="item_reader_type"><?=($row['Type'] == 1 ? $this->get_reader_type($row['Port']) : '')?></td>
		<td class="item_port"><?=$row['Port']?></td>
		<td class="item_user"><?=$row['UserName']?></td>
        <td class="item_card_no"><?=($row['CardNumber'] != NULL ? $row['CardNumber']."(".Util::GetCardFacilityCode($row['CardFormatNo']).")" : '')?></td>
        <td class="item_user_field"><?=$this->find_user_define($row['UserNo'])?></td>
		<td class="item_event"><?=$event_name?></td>
		<td class="item_message"><?=$row['Message']?></td>
		<td class="item_ack"><?=$row['Ack']?></td>
		<td class="item_ack_message"><?=$this->get_ack_message($row['No'], $logdb)?></td>
        <? if($this->is_option(ConstTable::OPTION_PARTITION)) { ?>
            <td class="item_site_name"><?=$row['SiteName']?></td>
        <? } ?>  
        <td class="item_floor_name"><?=$row['FloorName']?></td>
	</tr>
	<?
	} // end while
	?>
</tbody>
</table>

<table class="list_button_set">
<tr>
	<td align="center">
		<button type="button" onclick="open_preview()"><?=$lang->addmsg->print?></button>
		<button type="button" onclick="download();"><?=$lang->common->csv?></button>
	</td>
</tr>
</table>

<div id="pagination" class="pagination">
<?
echo '[&nbsp;';

if(array_key_exists('prev', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('false', '{$pages['prev']}')\">&nbsp;<img src='/img/menu/button_grey_list_prev.png'>&nbsp;</a>";
	echo '&nbsp;';
}

foreach($pages as $key => $val) {
	if($key == 'prev' || $key == 'next')	continue;

	if ($val == $page)	echo "<a href=\"javascript:void(0)\" style=\"text-decoration: none; color:#c4c4c4\">{$val}</a>";
	else				echo "<a href=\"javascript:void(0)\" onclick=\"load_search('false', '{$val}')\">{$val}</a>";

	echo '&nbsp;';
}

if(array_key_exists('next', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('false', '{$pages['next']}')\">&nbsp;<img src='/img/menu/button_grey_list_next.png'>&nbsp;</a>";
	echo '&nbsp;';
}

echo ']';
?>
</div>
