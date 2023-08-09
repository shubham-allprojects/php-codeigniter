
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

<table class="list_button_set">
<tr>
	<td align="center">
		<button type="button" onclick="open_preview('muster')"><?=$lang->addmsg->print?></button>
		<button type="button" onclick="download($('#form_search select[name=table]').val());"><?=$lang->common->csv?></button>
	</td>
</tr>
</table>

<div id="pagination" class="pagination">

<?
echo '[&nbsp;';

if(array_key_exists('prev', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('muster', '{$pages['prev']}')\">&nbsp;<img src='/img/menu/button_grey_list_prev.png'>&nbsp;</a>";
	echo '&nbsp;';
}

foreach($pages as $key => $val) {
	if($key == 'prev' || $key == 'next')	continue;

	if ($val == $page)	echo "<a href=\"javascript:void(0)\" style=\"text-decoration: none; color:#c4c4c4\">{$val}</a>";
	else				echo "<a href=\"javascript:void(0)\" onclick=\"load_search('muster', '{$val}')\">{$val}</a>";

	echo '&nbsp;';
}

if(array_key_exists('next', $pages)) {
	echo "<a href=\"javascript:void(0)\" style=\"text-decoration:none\" onclick=\"load_search('muster', '{$pages['next']}')\">&nbsp;<img src='/img/menu/button_grey_list_next.png'>&nbsp;</a>";
	echo '&nbsp;';
}

echo ']';
?>

</div>
