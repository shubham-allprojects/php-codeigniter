<div id="location">
<?
echo $lang->menu->report.'&nbsp;&gt;&nbsp;'.$lang->menu->report;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">

function load_search(table, page)
{
	page = page || 1;
	var url = '/?c='+ _class + '&m='+ table +'&action=search&page='+ page +'&'+ $("#form_search").serialize();

	show_loading();
	$('#list_box').empty().show().load(url, function() { hide_loading(); });
}

function open_preview(table)
{
	var url = '/?c='+ _class + '&m='+ table +'&action=preview&'+ $("#form_search").serialize();
	window.open(url, "ReportPrintPrivew", "width=800, height=800, directories=no, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes");
}

function download(table)
{
	var url = '/?c='+ _class + '&m='+ table +'&action=download';
	$('#form_search').attr('action', url).submit();
}

function table_select(form_name)
{
	var table = $('#form_search select[name=table]').val();
	$('#list_box').hide();
	$('#form_search input[type=text]').val('');
	$('#form_search select[name!=table]').val('');
	$('tbody.filter').hide();
	$('tbody.filter_'+ table).show();
}

</script>


<div id="list_section">
	<h3>:: Search</h3>
	<div class="box02">
	<form id="form_search" method="post" target="frame_exe">
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->report->select_table?></th>
            <td width="1">:</td>
			<td colspan="4">
				<?
				if( $_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL )
					echo Form::select('table', '', $this->report_tables_for_essential, array('onchange'=>"table_select('form_search')"));
				else
					echo Form::select('table', '', $this->report_tables, array('onchange'=>"table_select('form_search')"));
				?>
			</td>
		</tr>
		
		<tbody class="filter filter_door">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_door_no', '', array("MAXLENGTH"=>ConstTable::max_filter_door_no_char))?></td>
			<!-- DELETE CJMOON 2017.03.20  
            <th><?=$lang->report->floor?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_door_floorno', '', $floors, array(), '')?></td>
			DELETE END  -->
			<!-- ADD CJMOON 2017.03.20 -->			
			<th></th>
            <td width="1"></td>
			<td></td>
			<!-- ADD END -->
			<!-- <td><?=Form::input('filter_door_floorname', '', array("MAXLENGTH"=>ConstTable::max_filter_door_floorname_char))?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->name?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_door_name', '', array("MAXLENGTH"=>ConstTable::max_filter_door_name_char))?></td>
            <th><?=$lang->report->description?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_door_mean', '', array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_elevator hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_elevator_no', '')?></td>
            <th><?=$lang->report->floor?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_elevator_floorno', '', $floors, array(), '')?></td>
			<!-- <td><?=Form::input('filter_elevator_floorname', '')?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->name?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_elevator_name', '')?></td>
            <th><?=$lang->report->description?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_elevator_mean', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_auxinput hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxinput_no', '')?></td>
			<!-- DELETE CJMOON 2017.03.20  
            <th><?=$lang->report->floor?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_auxinput_floorno', '', $floors, array(), '')?></td>
			DELETE END  -->
			<!-- ADD CJMOON 2017.03.20 -->			
			<th></th>
            <td width="1"></td>
			<td></td>
			<!-- ADD END -->
			<!-- <td><?=Form::input('filter_auxinput_floorname', '')?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->name?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxinput_name', '')?></td>
            <th><?=$lang->report->description?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxinput_mean', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_auxoutput hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxoutput_no', '')?></td>
			<!-- DELETE CJMOON 2017.03.20  
            <th><?=$lang->report->floor?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_auxoutput_floorno', '', $floors, array(), '')?></td>
			DELETE END  -->
			<!-- ADD CJMOON 2017.03.20 -->			
			<th></th>
            <td width="1"></td>
			<td></td>
			<!-- ADD END -->
			<!-- <td><?=Form::input('filter_auxoutput_floorname', '')?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->name?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxoutput_name', '')?></td>
            <th><?=$lang->report->description?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_auxoutput_mean', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_cardholder hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_no', '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		<tr>
            <th><?=$lang->report->lastname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_lastname', '')?></td>
            <th><?=$lang->report->firstname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_firstname', '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->cardnumber?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_cardnumber', '')?></td>
            <th><?=$lang->report->cardstatus?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_cardholder_cardstatus', '', EnumTable::$attrCardStatus, array(), '')?></td>
			<!-- <td><?=Form::input('filter_cardholder_cardstatus', '')?></td> -->
		</tr>
		</tbody>

		<tbody class="filter filter_card hide">
		<tr>
            <th><?=$lang->report->cardnumber?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_card_cardnumber', '')?></td>
            <th><?=$lang->report->cardstatus?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_card_cardstatus', '', EnumTable::$attrCardStatus, array(), '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->cardformat?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_card_cardformatno', '', $cardformats, array(), '')?></td>
            <th><?=$lang->report->cardtype?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_card_cardtype', '', EnumTable::$attrCardType, array(), '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->lastname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_card_lastname', '')?></td>
            <th><?=$lang->report->firstname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_card_firstname', '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->phonenumber?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_card_phone', '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		</tbody>

		<tbody class="filter filter_cardholder_accesslevel hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_no', '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		<tr>
            <th><?=$lang->report->lastname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_lastname', '')?></td>
            <th><?=$lang->report->firstname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_firstname', '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->cardnumber?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_cardnumber', '')?></td>
            <th><?=$lang->report->accesslevel?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_cardholder_accesslevel_accesslevelno', '', $accesslevels, array(), '')?></td>
			<!-- <td><?=Form::input('filter_cardholder_accesslevel_accesslevelname', '')?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->doorno?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_doorno', '')?></td>
            <th><?=$lang->report->doorname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_cardholder_accesslevel_doorname', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_accesslevel_door hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_accesslevelno', '')?></td>
            <th><?=$lang->report->accesslevel?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_accesslevelname', '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->readerno?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_readerno', '')?></td>
            <th><?=$lang->report->readername?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_readername', '')?></td>
		</tr>
		<tr>
            <th><?=$lang->report->doorno?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_doorno', '')?></td>
            <th><?=$lang->report->doorname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_accesslevel_door_doorname', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_doorgroup hide">
		<tr>
            <th><?=$lang->report->no?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_doorgroup_groupno', '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		<tr>
            <th><?=$lang->report->groupname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_doorgroup_groupname', '')?></td>
            <th><?=$lang->report->accesslevel?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_doorgroup_accesslevelno', '', $accesslevels, array(), '')?></td>
			<!-- <td><?=Form::input('filter_doorgroup_accesslevelname', '')?></td> -->
		</tr>
		<tr>
            <th><?=$lang->report->doorno?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_doorgroup_doorno', '')?></td>
            <th><?=$lang->report->doorname?></th>
            <td width="1">:</td>
			<td><?=Form::input('filter_doorgroup_doorname', '')?></td>
		</tr>
		</tbody>

		<tbody class="filter filter_occupancy hide">
		<tr>
            <th><?=$lang->report->region?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_occupancy_regionno', '', $regions, array(), '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		</tbody>

		<tbody class="filter filter_muster hide">
		<tr>
            <th><?=$lang->report->region?></th>
            <td width="1">:</td>
			<td><?=Form::select('filter_muster_regionno', '', $muster_regions, array(), '')?></td>
            <th></th>
            <td width="1"></td>
			<td></td>
		</tr>
		</tbody>

		</table>

        <div class="button_set">
			<button type="button" onclick="load_search($('#form_search select[name=table]').val())"><?=$lang->button->search?></button>
        </div>
	</form>
	</div>

	<div id="list_box" class="box02">
	</div>
</div>