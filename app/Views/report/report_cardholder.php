
<table class="tbl_list">
<tbody class="tbl_header">
	<tr>
		<th><?=$lang->report->no?></th>
		<th><?=$lang->report->id?></th>
		<th><?=$lang->report->lastname?></th>
		<th><?=$lang->report->firstname?></th>
		<th><?=$lang->report->cardnumber?></th>
		<th><?=$lang->report->accesslevels?></th>
		<th><?=$lang->report->cardstatus?></th>
		<th><?=$lang->report->photo?></th>
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
		<td><?=($row['SelectType'] == '2' ? $this->get_groupaccess_str($row['CardID']) : $this->get_access_str($row['CardID']))?></td>
		<td><?=EnumTable::$attrCardStatus[$row['CardStatus']]?></td>
		<td><?=(empty($row['ImageFile']) ? '' : '<img src="/user_img/'.$row['ImageFile'].'" width="110" height="130">')?></td>
	</tr>
	<? } // end while ?>
</tbody>
</table>

<table class="list_button_set">
<tr>
	<td align="center">
		<button type="button" onclick="open_preview('cardholder')"><?=$lang->addmsg->print?></button>
		<button type="button" onclick="download($('#form_search select[name=table]').val());"><?=$lang->common->csv?></button>
	</td>
</tr>
</table>

<div id="pagination" class="pagination">
<?
echo '[&nbsp;';

if(array_key_exists('prev', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('cardholder', '{$pages['prev']}')\">&nbsp;<img src='/img/menu/button_grey_list_prev.png'>&nbsp;</a>";
	echo '&nbsp;';
}

foreach($pages as $key => $val) {
	if($key == 'prev' || $key == 'next')	continue;

	if ($val == $page)	echo "<a href=\"javascript:void(0)\" style=\"text-decoration: none; color:#c4c4c4\">{$val}</a>";
	else				echo "<a href=\"javascript:void(0)\" onclick=\"load_search('cardholder', '{$val}')\">{$val}</a>";

	echo '&nbsp;';
}

if(array_key_exists('next', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('cardholder', '{$pages['next']}')\">&nbsp;<img src='/img/menu/button_grey_list_next.png'>&nbsp;</a>";
	echo '&nbsp;';
}

echo ']';
?>
</div>
