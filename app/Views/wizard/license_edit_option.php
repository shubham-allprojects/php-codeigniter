<?
$device_model	= $Input::get('model');
switch( $device_model ) {
	case '1' :		// Essential
?>

<table width="100%" class="tbl_view hide" style="table-layout:fixed;">
	<col width="190">
	</col>
	<col>
	</col>
	<? foreach( EnumTable::$attrModelOption[$device_model] as $label=>$items ) { ?>
	<tr>
		<th><?=$label?></th>
		<td>
			<?
	if( !is_array($items) || empty($items) ) {
		echo 'N/A';
		continue;
	}

	foreach( $items as $idx=>$label ) {
	?>
			<input type="checkbox" name="request_options[<?=$idx?>]" value="1"> <label><?=$label?></label><br>
			<? } ?>
		</td>
	</tr>
	<? } ?>
</table>

<?
	break;
	case '2' :		// Elite
	case '3' :		// Enterprise
?>

<table width="100%" class="tbl_view hide" style="table-layout:fixed;">
	<col width="190">
	</col>
	<col>
	</col>
	<? foreach( EnumTable::$attrModelOption[$device_model] as $label=>$items ) { ?>
	<tr>
		<th><?=$label?></th>
		<td>
			<?
	if( !is_array($items) || empty($items) ) {
		echo 'N/A';
		continue;
	}

	foreach( $items as $idx=>$label ) {
		if( $idx == '1' ) {
	?>
			<input type="checkbox" name="request_options[<?=$idx?>]" value="1" checked="checked" disabled="disabled">
			<label><?=$label?></label><br>
			<?
		} else {
	?>
			<input type="checkbox" name="request_options[<?=$idx?>]" value="1"> <label><?=$label?></label><br>
			<?
		}
	}
	?>
		</td>
	</tr>
	<? } ?>
</table>

<?
	break;
}
?>

<textarea name="comment" rows="5" style="width:350px;"></textarea>