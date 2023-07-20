<div id="wrap">
<div id="location">
<?
echo lang('Message.menu.admin').'&nbsp;&gt;&nbsp;'.lang('Message.menu.card_format');

?>
	<button class="btn_help" onclick="openHelp('help', 'card_format')">Help</button>
</div>

<div id="decoder_section" class="hide">
<form id="form_decoder" method="post" action="add-card-format">
	<h2>:: <?=lang('Message.menu.card_format')?></h2>
	<div class="box01">

		<h3><?=lang('Message.menu.basic')?></h3>
		<table class="tbl_view">
		<tr>
			<th><?=lang('Message.card.ReaderNo')?></th>
			<td width="1">:</td>
			<td><?=Form::select('door', '', $array_door)?></td>
			<th colspan="3"></th>
		</tr>
        <tr>
            <td colspan="6"><span id="card_btn"><button type="button" class="btn_large4" onclick="card_scan(0)"><?=lang('Message.button.card_scan')?></button></span> <span id="card-rowdata"></span></td>
        </tr>
		<tr>
			<th><?=lang('Message.card_format.card_format_default')?></th>
			<td width="1">:</td>
			<td><?=Form::select('card_format_default', '', $arr_card_format_default, array('onchange'=>"change_format('form_decoder');"), 'Custom')?><?=Form::hidden('BitValue')?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Name')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<th><?=lang('Message.card_format.Mean')?></th>
			<td width="1">:</td>
			<td><?=Form::input('Mean', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
		</tr>
		<tr>
			<th><font color="red"><?=lang('Message.card_format.FacilityStartBit')?></font> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityStartBit', "", array("MAXLENGTH"=>ConstTable::max_facility_code_start_bit_char, 'onchange'=>"$('#card-rowdata').html(parseRowdata());"))?></td>
			<th><font color="red"><?=lang('Message.card_format.FacilityBitLength')?></font> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityBitLength', "", array("MAXLENGTH"=>ConstTable::max_faciltity_code_length_char, 'onchange'=>"$('#card-rowdata').html(parseRowdata());"))?></td>
		</tr>
		<tr>
			<th><font color="blue"><?=lang('Message.card_format.CardNumberStartBit')?></font> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberStartBit', "", array("MAXLENGTH"=>ConstTable::max_card_number_start_bit_char, 'onchange'=>"$('#card-rowdata').html(parseRowdata());"))?></td>
			<th><font color="blue"><?=lang('Message.card_format.CardNumberLength')?></font> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberLength', "", array("MAXLENGTH"=>ConstTable::max_card_number_length_char, 'onchange'=>"$('#card-rowdata').html(parseRowdata());"))?></td>
		</tr>
		
		<tr>
			<th><?=lang('Message.card_format.FacilityCode')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityCode', "", array("MAXLENGTH"=>ConstTable::max_faciltity_code_char,'onkeypress'=>"if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"))?><?=Form::hidden('TotalBitLength')?></td>
			<th><?=lang('Message.card.CardNo')?></th>
			<td width="1">:</td>
			<td><?=Form::input('CardNumber', "")?></td>
		</tr>
		</table>

		<div class="button_set">
			<button type="button" onclick="$('#form_decoder').submit()"><?=lang('Message.button.add')?></button>&nbsp;&nbsp;
			<button type="button" onclick="clear_card_scan(); open_decoder(); change_format_custom('form_decoder');"><?=lang('Message.button.reset')?></button>&nbsp;&nbsp;
			<button type="button" onclick="clear_card_scan(); $('#decoder_section').hide();"><?=lang('Message.button.cancel')?></button>
		</div>
	</div>
</form>
</div>


<div id="new_section" class="hide">
<form id="form_new" method="post" action="<?=base_url().'add-card-format'?>">
	<h2>:: <?=lang('Message.menu.card_format')?></h2>
	<div class="box01">

		<h3><?=lang('Message.menu.basic')?></h3>
		<table class="tbl_view">
		<tr>
			<th><?=lang('Message.card_format.card_format_default')?></th>
			<td width="1">:</td>
			<td><?=Form::select('card_format_default', '', $arr_card_format_default, array('onchange'=>"change_format('form_new');"), 'Custom')?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Name')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Mean')?></th>
			<td width="1">:</td>
			<td><?=Form::input('Mean', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.TotalBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('TotalBitLength', "", array("MAXLENGTH"=>ConstTable::max_total_bit_length_char))?></td>
			<th><?=lang('Message.card_format.FacilityCode')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityCode', "", array("MAXLENGTH"=>ConstTable::max_faciltity_code_char,'onkeypress'=>"if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.FacilityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityStartBit', "", array("MAXLENGTH"=>ConstTable::max_facility_code_start_bit_char))?></td>
			<th><?=lang('Message.card_format.FacilityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityBitLength', "", array("MAXLENGTH"=>ConstTable::max_faciltity_code_length_char))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.CardNumberStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberStartBit', "", array("MAXLENGTH"=>ConstTable::max_card_number_start_bit_char))?></td>
			<th><?=lang('Message.card_format.CardNumberLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberLength', "", array("MAXLENGTH"=>ConstTable::max_card_number_length_char))?></td>
		</tr>
		<!-- <tr>
			<th><?=lang('Message.card_format.EvenParityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('EvenParityStartBit', "", array("MAXLENGTH"=>"3"))?></td>
			<th><?=lang('Message.card_format.EvenParityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('EvenParityBitLength', "", array("MAXLENGTH"=>"3"))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.OddParityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('OddParityStartBit', "", array("MAXLENGTH"=>"3"))?></td>
			<th><?=lang('Message.card_format.OddParityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('OddParityBitLength', "", array("MAXLENGTH"=>"3"))?></td>
		</tr> -->
		</table>

		<div class="button_set">
			<button type="button" onclick="$('#form_new').submit()"><?=lang('Message.button.add')?></button>&nbsp;&nbsp;
			<button type="button" onclick="open_new(); change_format_custom('form_new');"><?=lang('Message.button.reset')?></button>&nbsp;&nbsp;
			<button type="button" onclick="close_new()"><?=lang('Message.button.cancel')?></button>
		</div>
	</div>
</form>
</div>


<div id="edit_section" class="hide">
<form id="form_edit" method="post" action="<?=base_url()?>update-card-format">
<?=Form::hidden("No")?>
	<h2>:: <?=lang('Message.menu.card_format')?></h2>
	<div class="box01">

		<h3><?=lang('Message.menu.basic')?></h3>
		<table class="tbl_view">
		<tr>
			<th><?=lang('Message.card_format.card_format_default')?></th>
			<td width="1">:</td>
			<td><?=Form::select('card_format_default', '', $arr_card_format_default, array('onchange'=>"change_format('form_edit');"), 'Custom')?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Name')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Mean')?></th>
			<td width="1">:</td>
			<td><?=Form::input('Mean', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.TotalBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('TotalBitLength', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th><?=lang('Message.card_format.FacilityCode')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityCode', "", array("MAXLENGTH"=>ConstTable::max_faciltity_code_char,'onkeypress'=>"if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.FacilityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityStartBit', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th><?=lang('Message.card_format.FacilityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('FacilityBitLength', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.CardNumberStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberStartBit', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
			<th><?=lang('Message.card_format.CardNumberLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::inputnum('CardNumberLength', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
		</tr>
		<!-- <tr>
			<th><?=lang('Message.card_format.EvenParityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('EvenParityStartBit')?></td>
			<th><?=lang('Message.card_format.EvenParityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('EvenParityBitLength', "", array("MAXLENGTH"=>"3"))?></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.OddParityStartBit')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('OddParityStartBit', "", array("MAXLENGTH"=>"3"))?></td>
			<th><?=lang('Message.card_format.OddParityBitLength')?> *</th>
			<td width="1">:</td>
			<td><?=Form::input('OddParityBitLength')?></td>
		</tr> -->
		</table>

		<div class="button_set">
			<button type="button" onclick="$('#form_edit').submit()"><?=lang('Message.button.save')?></button>&nbsp;&nbsp;
			<button type="button" onclick="open_edit(_seq); change_format_custom('open_edit');"><?=lang('Message.button.reset')?></button>&nbsp;&nbsp;
			<button type="button" onclick="close_edit()"><?=lang('Message.button.cancel')?></button>
		</div>
	</div>
</form>
</div>


<div id="view_section" class="hide">
	<h2>:: <?=lang('Message.menucard_format')?></h2>
	<div class="box01">

		<h3><?=lang('Message.menu.basic')?></h3>
		<table class="tbl_view">
		<tr style="display:none">
            <th></th>
            <td id="view_No"></td>
        </tr>
		<tr>
			<th width="150"><?=lang('Message.card_format.Name')?> *</th>
			<td width="1">:</td>
			<td id="view_Name"></td>
			<th colspan="3"></th>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.Mean')?></th>
			<td width="1">:</td>
			<td id="view_Mean"></td>
			<th colspan="3"></th>
		</tr>
		</table>
		<table class="tbl_view">
		<tr>
			<th width="150"><?=lang('Message.card_format.TotalBitLength')?></th>
			<td width="1">:</td>
			<td width="160" id="view_TotalBitLength"></td>
			<th><?=lang('Message.card_format.FacilityCode')?></th>
			<td width="1">:</td>
			<td width="160" id="view_FacilityCode"></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.FacilityStartBit')?></th>
			<td width="1">:</td>
			<td id="view_FacilityStartBit"></td>
			<th width="150"><?=lang('Message.card_format.FacilityBitLength')?></th>
			<td width="1">:</td>
			<td id="view_FacilityBitLength"></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.CardNumberStartBit')?></th>
			<td width="1">:</td>
			<td id="view_CardNumberStartBit"></td>
			<th><?=lang('Message.card_format.CardNumberLength')?></th>
			<td width="1">:</td>
			<td id="view_CardNumberLength"></td>
		</tr>
		<!-- <tr>
			<th><?=lang('Message.card_format.EvenParityStartBit')?></th>
			<td width="1">:</td>
			<td id="view_EvenParityStartBit"></td>
			<th><?=lang('Message.card_format.EvenParityBitLength')?></th>
			<td width="1">:</td>
			<td id="view_EvenParityBitLength"></td>
		</tr>
		<tr>
			<th><?=lang('Message.card_format.OddParityStartBit')?></th>
			<td width="1">:</td>
			<td id="view_OddParityStartBit"></td>
			<th><?=lang('Message.card_format.OddParityBitLength')?></th>
			<td width="1">:</td>
			<td id="view_OddParityBitLength"></td>
		</tr> -->
		</table>

		<div class="button_set">
			<button type="button" onclick="open_edit(_seq); change_format_custom('form_edit');"><?=lang('Message.button.edit')?></button>&nbsp;&nbsp;
            <button type="button" onclick="delete_form();"><?=lang('Message.button.delete')?></button>&nbsp;&nbsp;
			<button type="button" onclick="close_view()"><?=lang('Message.button.cancel')?></button>
		</div>
	</div>
</div>


<div id="list_section">
	<h2>:: <?=lang('Message.menu.list')?></h2>
	<div class="box01">
		<table class="tbl_list">
		<tr>
			<th><?=lang('Message.card_format.No')?></th>
			<th><?=lang('Message.card_format.Name')?></th>
			<th><?=lang('Message.card_format.Mean')?></th>
			<th><?=lang('Message.card_format.FacilityCode')?></th>
			<th><?=lang('Message.card_format.TotalBitLength')?></th>
			<th><?=lang('Message.card_format.IsDefault')?></th>
		</tr>
        <tbody id="list_body">
        </tbody>
		</table>

		<table class="list_button_set">
		<tr>
			<td width="160">
				<button type="button" onclick="open_new(); change_format_custom('form_new');"><?=lang('Message.button.new')?></button>
				<button type="button" onclick="open_decoder(); change_format_custom('form_decoder');"><?=lang('Message.button.decoder')?></button>
			</td>
			<td align="center">
			<form id="form_search" method="post" action="<?=base_url()?>get-card-format" onsubmit="load_list_search('<?=base_url()?>get-card-format'); return false;" target="_self">
				<?=Form::select('field', '', array('Name'=>lang('Message.card_format.name'), 'Mean'=>lang('Message.card_format.Mean')))?>
				<?=Form::input('word', '')?>
				<button type="button" onclick="load_list_search('<?=base_url()?>get-card-format')"><?=lang('Message.button.search')?></button>
			</form>
			</td>
			<td width="150" align="right"><button type="button" onclick="load_list('<?=base_url()?>get-card-format')"><?=lang('Message.button.list')?></button></td>
		</tr>
		</table>

		<div id="pagination" class="pagination">[ 1 ]</div>
	</div>
</div>
</div>
<?PHP echo view('common/js'); ?>
<script type="text/javascript">
function create_list()
{
	$("#list_body").html("");
	var offset = (parseInt(_data.page) - 1) * parseInt(_data.rowsize);
	var isDefaultChecked;
	for(var i=0; i<_data.list.length; i++)
	{
		isDefaultChecked	= _data.list[i].IsDefault == '1' ? 'checked="checked"' : '';
		$("#list_body").append(
			'<tr id="list_'+ i +'" onclick="open_view('+ i +')" class="ov">' +
			'	<td>'+ _data.list[i].No +'</td>' +
			'	<td>'+ _data.list[i].Name +'</td>' +
			'	<td>'+ _data.list[i].Mean +'</td>' +
			'	<td>'+ _data.list[i].FacilityCode +'</td>' +
			'	<td>'+ _data.list[i].TotalBitLength +'</td>' +
			'	<td><input type="radio" name="default" onclick="saveIsDefault(event, \''+ _data.list[i].No +'\');" '+ isDefaultChecked +'></td>' +
			'</tr>'
		);
	}

	create_pagination();
}

$(document).ready(function() {
	load_list('<?=base_url()?>get-card-format');
});

var _format_default = <?=json_encode($card_format_default)?>;
function change_format_custom(form_name)
{
	$("#"+form_name+" select[name='CardFormatDefault']").val("");
	change_format(form_name);
}
function change_format(form_name)
{
	var format = $("#"+form_name+" select[name='card_format_default']").val();

	if( format == "" )
	{
		//$("#"+form_name+" input[name='Name']").attr("readonly", false);
		//$("#"+form_name+" input[name='Mean']").attr("readonly", false);
		//$("#"+form_name+" input[name='FacilityCode']").attr("readonly", false);
		$("#"+form_name+" input[name='TotalBitLength']").attr("readonly", false);
		$("#"+form_name+" input[name='FacilityBitLength']").attr("readonly", false);
		//$("#"+form_name+" input[name='EvenParityBitLength']").attr("readonly", false);
		$("#"+form_name+" input[name='FacilityStartBit']").attr("readonly", false);
		//$("#"+form_name+" input[name='EvenParityStartBit']").attr("readonly", false);
		$("#"+form_name+" input[name='CardNumberLength']").attr("readonly", false);
		//$("#"+form_name+" input[name='OddParityBitLength']").attr("readonly", false);
		$("#"+form_name+" input[name='CardNumberStartBit']").attr("readonly", false);
		//$("#"+form_name+" input[name='OddParityStartBit']").attr("readonly", false);
	}
	else
	{
		var row;
		for( var i=0; i<_format_default.length; i++ )
		{
			row = _format_default[i];

			if(row.No == format)
			{
				//$("#"+form_name+" input[name='Name']").val(row.Name);
				//$("#"+form_name+" input[name='Mean']").val(row.Mean);
				//$("#"+form_name+" input[name='FacilityCode']").val(row.FacilityCode);
				$("#"+form_name+" input[name='TotalBitLength']").val(row.TotalBitLength);
				$("#"+form_name+" input[name='FacilityBitLength']").val(row.FacilityBitLength);
				//$("#"+form_name+" input[name='EvenParityBitLength']").val(row.EvenParityBitLength);
				$("#"+form_name+" input[name='FacilityStartBit']").val(row.FacilityStartBit);
				//$("#"+form_name+" input[name='EvenParityStartBit']").val(row.EvenParityStartBit);
				$("#"+form_name+" input[name='CardNumberLength']").val(row.CardNumberLength);
				//$("#"+form_name+" input[name='OddParityBitLength']").val(row.OddParityBitLength);
				$("#"+form_name+" input[name='CardNumberStartBit']").val(row.CardNumberStartBit);
				//$("#"+form_name+" input[name='OddParityStartBit']").val(row.OddParityStartBit);
			}
		}
		//$("#"+form_name+" input[name='Name']").attr("readonly", true);
		//$("#"+form_name+" input[name='Mean']").attr("readonly", true);
		//$("#"+form_name+" input[name='FacilityCode']").attr("readonly", true);
		if (form_name != 'form_decoder')
		{
			$("#"+form_name+" input[name='TotalBitLength']").attr("readonly", true);
			$("#"+form_name+" input[name='FacilityBitLength']").attr("readonly", true);
			//$("#"+form_name+" input[name='EvenParityBitLength']").attr("readonly", true);
			$("#"+form_name+" input[name='FacilityStartBit']").attr("readonly", true);
			//$("#"+form_name+" input[name='EvenParityStartBit']").attr("readonly", true);
			$("#"+form_name+" input[name='CardNumberLength']").attr("readonly", true);
			//$("#"+form_name+" input[name='OddParityBitLength']").attr("readonly", true);
			$("#"+form_name+" input[name='CardNumberStartBit']").attr("readonly", true);
			//$("#"+form_name+" input[name='OddParityStartBit']").attr("readonly", true);
		}
	}

	if( form_name == "form_decoder" ) {
		$("#card-rowdata").html(parseRowdata());
	}
}

function delete_form()
{
    if (confirm("<?=lang('Message.addmsg.confirm_delete')?>")) 
    {
        var no = $("#view_No").html();
    	$.getScript("/cardformat/check_dependency/?no="+no);
    }
}

function del_data_prepass() {
	var no = $("#view_No").html();
	$.getScript("/cardformat/delete/?no="+no,function(){
		load_list('<?=base_url()?>get-card-format');
		$('select[name=card_format_default] option[value='+ no +']').remove();
	});
}

function confirm_dependency()
{
	if( confirm("<?=lang('Message.addmsg.confirm_data_delete')?>") )
	{
		del_data_prepass();
	}
}

function open_decoder()
{
    set_current();

    $("#form_decoder input").val("");
    $("#form_decoder select").val("");
	$("#card-rowdata").html("");

	dropdownEmptyToFirst($("#form_decoder select[name='door']"));

    close_all();
    $("#decoder_section").show();
}

// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
<? if( $baseControllerMethods->is_auth(5, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=lang('Message.user.error_not_permission')?>");
}
function open_decoder()
{
	alert("<?=lang('Message.user.error_not_permission')?>");
}
<? } ?>

<? if( $baseControllerMethods->is_auth(5, 2) != TRUE && $baseControllerMethods->is_auth(5, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$baseControllerMethods->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $baseControllerMethods->is_auth(5, 2) != TRUE) { ?>
function delete_form()
{
	alert("<?=$baseControllerMethods->lang->user->error_not_permission?>");
}
<? } ?>

var handle_card_scan = null;
function card_scan(ScanCount)
{
    var door = $("#form_decoder").find("select[name='door']").val();

	$("#card-rowdata").html("");
	$("#card_btn").html(ScanCount);
    
    $("#form_decoder input").val("");
    $("#form_decoder").find("select[name='card_format_default']", $("#form_decoder")).val("");
	$("#card-rowdata").html("");

    $.getJSON('./card_scan_decoder.php',{No:ScanCount,door:door},function(data)
    {
		if( !data.total_bit || !data.data ) {
			if( ScanCount < 30 ) {
				ScanCount++;
				handle_card_scan = setTimeout('card_scan('+ScanCount+')',1000);
			} else {
				$("#card_btn").html('<button type="button" class="btn_large4" onclick="card_scan(0)"><?=lang('Message.button.card_scan')?></button>');
				showCardFormatDefault("");
				alert("<?=lang('Message.addmsg.not_read_scan')?>");			//alert("Not Read Card Scan");
			}
		} else {
			$("#card_btn").html('<button type="button" class="btn_large4" onclick="card_scan(0)"><?=lang('Message.button.card_scan')?></button>');
			$("input[name='BitValue']", $("#form_decoder")).val(data.data);
			$("input[name='TotalBitLength']", $("#form_decoder")).val(data.total_bit);
			//$("#card-rowdata").html(parseRowdata());
			showCardFormatDefault(data.card_format_default);
		}
    });
}

function clear_card_scan()
{
	if( handle_card_scan != null )
	{
		clearTimeout(handle_card_scan);
		handle_card_scan = null;
	}
	$("#card_btn").html('<button type="button" class="btn_large4" onclick="card_scan(0)"><?=lang('Message.button.card_scan')?></button>');
	showCardFormatDefault("");
}

function showCardFormatDefault(val)
{
	if( val == "" ) {
		$("select[name='card_format_default'] option", $("#form_decoder")).show();
		return; 
	}

	$("select[name='card_format_default'] option", $("#form_decoder")).hide();
    
	var vals = val.split(":");
	$.each(vals, function(key, val) {
		$("select[name='card_format_default'] option[value='"+val+"']", $("#form_decoder")).show();
	});
    
	$("select[name='card_format_default']", $("#form_decoder")).val(vals[0]);
	change_format('form_decoder');
}

function parseRowdata()
{
	var data = $("input[name='BitValue']", $("#form_decoder")).val();
	var FacilityStartBit = parseInt($("input[name='FacilityStartBit']", $("#form_decoder")).val());
	var FacilityBitLength = parseInt($("input[name='FacilityBitLength']", $("#form_decoder")).val());
	var CardNumberStartBit = parseInt($("input[name='CardNumberStartBit']", $("#form_decoder")).val());
	var CardNumberLength = parseInt($("input[name='CardNumberLength']", $("#form_decoder")).val());
	//var EvenParityStartBit = parseInt($("input[name='EvenParityStartBit']", $("#form_decoder")).val());
	//var EvenParityBitLength = parseInt($("input[name='EvenParityBitLength']", $("#form_decoder")).val());
	//var OddParityStartBit = parseInt($("input[name='OddParityStartBit']", $("#form_decoder")).val());
	//var OddParityBitLength = parseInt($("input[name='OddParityBitLength']", $("#form_decoder")).val());

	var new_data = "";
	if( $("input[name='TotalBitLength']", $("#form_decoder")).val() != "") {
		new_data = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=lang('Message.addmsg.TotalBitTitle')?>" + $("input[name='TotalBitLength']", $("#form_decoder")).val() + " Bit :&nbsp;&nbsp;";
	}

	var facilityCode = "";
	var cardNumber = "";

	for( var i=0; i<data.length; i++ ) {
		offset = i + 1;
		if( offset >= FacilityStartBit && offset <= (FacilityStartBit + FacilityBitLength - 1) )			{ new_data += '<font color=red>'+data[i]+'</font>'; facilityCode += data[i]; }
		else if( offset >= CardNumberStartBit && offset <= (CardNumberStartBit + CardNumberLength - 1) )	{ new_data += '<font color=blue>'+data[i]+'</font>'; cardNumber += data[i]; }
		//else if( offset >= EvenParityStartBit && offset <= (EvenParityStartBit + EvenParityBitLength - 1) )	{ new_data += '<font color=orange>'+data[i]+'</font>'; }
		//else if( offset >= OddParityStartBit && offset <= (OddParityStartBit + OddParityBitLength - 1) )	{ new_data += '<font color=green>'+data[i]+'</font>'; }
		else																								{ new_data += data[i]; }
	}

	facilityCode	= parseInt(facilityCode, 2).toString(10);
	cardNumber		= parseInt(cardNumber, 2).toString(10);

	facilityCode	= isNaN(facilityCode) ? "" : facilityCode;
	cardNumber		= isNaN(cardNumber) ? "" : cardNumber;

	$("input[name='FacilityCode']", $("#form_decoder")).val(facilityCode);
	$("input[name='CardNumber']", $("#form_decoder")).val(cardNumber);

	return new_data;
}

function calculate()
{
	var BitValue = $("input[name='BitValue']", $("#form_decoder")).val();
	var TotalBitLength = $("input[name='TotalBitLength']", $("#form_decoder")).val();
	var FacilityStartBit = $("input[name='FacilityStartBit']", $("#form_decoder")).val();
	var FacilityBitLength = $("input[name='FacilityBitLength']", $("#form_decoder")).val();
	var CardNumberStartBit = $("input[name='CardNumberStartBit']", $("#form_decoder")).val();
	var CardNumberLength = $("input[name='CardNumberLength']", $("#form_decoder")).val();
	//var EvenParityStartBit = $("input[name='EvenParityStartBit']", $("#form_decoder")).val();
	//var EvenParityBitLength = $("input[name='EvenParityBitLength']", $("#form_decoder")).val();
	//var OddParityStartBit = $("input[name='OddParityStartBit']", $("#form_decoder")).val();
	//var OddParityBitLength = $("input[name='OddParityBitLength']", $("#form_decoder")).val();

	$.getJSON('<?=base_url().'card-format-calculate'?>',{
		BitValue: BitValue,
		TotalBitLength: TotalBitLength,
		FacilityStartBit: FacilityStartBit,
		FacilityBitLength: FacilityBitLength,
		CardNumberStartBit: CardNumberStartBit,
		CardNumberLength: CardNumberLength
		//EvenParityStartBit: EvenParityStartBit,
		//EvenParityBitLength: EvenParityBitLength,
		//OddParityStartBit: OddParityStartBit,
		//OddParityBitLength: OddParityBitLength
	},function(data)
	{
		if( data.status == 'success' ) {
		  $("input[name='FacilityCode']", $("#form_decoder")).val(data.facility);
			$("input[name='CardNumber']", $("#form_decoder")).val(data.card_number);
		} else {
			alert("<?=lang('Message.addmsg.error_calculate')?>");			//alert('Error');
		}
	});
}

function saveIsDefault(event, no)
{
	$.getScript('<?=base_url()?>card-format-update-default/'+no);
	event.stopPropagation();
}
</script>