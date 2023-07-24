<style>
/*
#contents { width:771px; float:left; border:0; margin:0 0 16px 0; background:#16971e; }
*/
/* ADD CJMOON 2017.04.24 */
#contents { width:771px; float:left; border:0; margin:0 0 16px 0; background:#16971e; margin-top:100px;}

</style>

<? if( $Input::get('wizard') != '1' ) { ?>
<div style="height:30px; padding-left:690px;">
	<a href="<?=$_SERVER['HTTP_REFERER']?>" class="license-close"></a>
</div>
<? } ?>

<table>
<? if( $Input::get('wizard') != '1' ) { ?>
<tr>
	<td width="6" height="3" background="/img/menu/quick_window_top_left.png"></td>
	<td bgcolor="#ffffff"></td>
	<td width="6" height="3" background="/img/menu/quick_window_top_right.png"></td>
</tr>
<? } ?>
<tr>
	<td bgcolor="#ffffff"></td>
	<td height="500" bgcolor="#ffffff" style="padding-left:<?=($Input::get('wizard') == '1' ? '0' : '30px')?>;">
		<? if( $Input::get('wizard') != '1' ) { ?>
		<div class="license-location">
			<img src="/img/menu/icon_info.png" valign="absmiddle">
			<span id="license-location-text"><?=$lang->menu->license?></span>
		</div>
		<? } ?>
		
        <div id="view_section">
            <h2>:: <?=$lang->menu->license?></h2>
            <div class="box01">
                <h3><?=$lang->menu->basic?></h3>
                <table class="tbl_view">
                <tr style="display:none;">
                    <td id="view_No"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th width="150"><?=$lang->license->Model?></th>
                    <td width="1">:</td>
                    <td id="view_ModelStr"></td>
                </tr>
                <tr>
                    <th width="150"><?=$lang->menu->version?></th>
                    <td width="1">:</td>
                    <td id="view_VersionStr"></td>
                </tr>
                <tr>
                    <th width="150"><?=$lang->license->Type?></th>
                    <td width="1">:</td>
                    <td id="view_TypeStr"></td>
                </tr>
                <tr>
                    <th width="150"><?=$lang->addmsg->mac_address?></th>
                    <td width="1">:</td>
                    <td id="view_MACAddress"></td>
                </tr>
                <tr>
                    <th width="150"><?=$lang->license->license?></th>
                    <td width="1">:</td>
                    <td id="view_LicenseKey"></td>
                </tr>
                </table>
                <div class="button_set">
                    <button type="button" onclick="edit_start();"><?=$lang->button->edit?></button>&nbsp;&nbsp;
					<button type="button" onclick="window.print();"><?=$lang->button->print?></button>
                </div>
            </div>
        </div>
        
        <div id="edit_section" class="hide">
            <h2>:: <?=$lang->menu->license?></h2>
            <div class="box01">
                <form id="form_edit" method="post" action="<?=base_url()?>license-update">
                    <?=Form::hidden("No")?>
                    <h3><?=$lang->menu->basic?></h3>
                    <table class="tbl_view">
                    <tr>
                        <th width="150"><?=$lang->license->license?> *</th>
                        <td width="1">:</td>
                        <td><?=Form::input('LicenseKey', "", array("SIZE"=>"80", "MAXLENGTH"=>"64"))?></td>
                    </tr>
                    </table>
            
                    <div class="button_set">
                        <button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
                        <button type="button" onclick="edit_start()"><?=$lang->button->reset?></button>&nbsp;&nbsp;
                        <button type="button" onclick="edit_end()"><?=$lang->button->cancel?></button>
                    </div>
                </form>
                </div>
        </div>

		<? if( $Input::get('wizard') != '1' && 
		      ($_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL || 
			   $_SESSION['spider_model'] == ConstTable::MODEL_ELITE || 
			   $_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE ||
			   $_SESSION['spider_model'] == ConstTable::MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == ConstTable::MODEL_TE_SERVER) ) { ?>
		<br>
		<br>
		<form id="form_request" method="post" action="<?=base_url()?>license-send-order">
		<input type="hidden" name="request_model" value="">
		<input type="hidden" name="request_type" value="">
		<?
		$spec = $lang->licensespec->getMessages();
		//var_dump($spec);
		?>
		<table width="100%" class="tbl_view" style="table-layout:fixed;">
			<tr style="background:black;">
				<th style="color:white;">
					<button type="button" id="btn_license_spec_show" class="btn_char" onclick="$('#license_spec').toggle(); if($('#license_spec').is(':hidden')) { $(this).text('+'); } else { $(this).text('-'); }" style="">+</button>
					<?=$lang->license->msg_sys_info?>
				</th>
			</tr>
		</table>
		<div id="license_spec" class="hide">
			<table style="table-layout:fixed;">
			<tr>
				<td style="padding-right:5px;">
					<h3><?=$lang->license->current_system?></h3>
					<table width="100%" class="tbl_view" style="table-layout:fixed;">
					<col width="190"></col>
					<col></col>
					<tr>
						<th class="bold"><?=$lang->license->system?></th>
						<td class="bold" align="center"><?=EnumTable::$attrModel[$_SESSION['spider_model']]?></td>
					</tr>
					<tr>
						<th><?=$lang->license->readers_per_system?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][0])?></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->doors_per_system?></th>
						<td class="bold" align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][1])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->users_per_system?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][2])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->access_levels_per_person?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][3])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->access_cards?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][4])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->cards_per_person?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][5])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->card_formats?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][6])?></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->expansion_modules?></th>
						<td class="bold" align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][7])?></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->alarm_input_points?></th>
						<td class="bold" align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][8])?></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->output_points?></th>
						<td class="bold" align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][9])?></td>
					</tr>
					<tr>
						<th><?=$lang->license->online_event_history_log?></th>
						<td align="center"><?=number_format(EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][10])?> <?=$lang->license->transaction?></td>
					</tr>
					</table>
				</td>
				<td style="padding-left:5px;">
					<h3><?=$lang->license->upgrade_system?></h3>
					<table width="100%" class="tbl_view" style="table-layout:fixed;">
					<col width="190"></col>
					<col></col>
					<tr>
						<th class="bold"><?=$lang->license->system?></th>
						<td class="bold" align="center"><?=Form::select('request_model', $_SESSION['spider_model'], EnumTable::toArrayUpgradeModel($_SESSION['spider_model']), array('onchange'=>'changeModel(this)'))?></td>
					</tr>
					<tr>
						<th><?=$lang->license->readers_per_system?></th>
						<td align="center"><span name="upgrade_readers"></span></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->doors_per_system?></th>
						<td class="bold" align="center"><?=Form::select('request_type', $_SESSION['spider_kind'], EnumTable::toArrayUpgradeType($_SESSION['spider_model'], $_SESSION['spider_kind']), array('onchange'=>'changeType(this)'))?></td>
					</tr>
					<tr>
						<th><?=$lang->license->users_per_system?></th>
						<td align="center"><span name="upgrade_users"></span></td>
					</tr>
					<tr>
						<th><?=$lang->license->access_levels_per_person?></th>
						<td align="center"><span name="upgrade_access_levels"></span></td>
					</tr>
					<tr>
						<th><?=$lang->license->access_cards?></th>
						<td align="center"><span name="upgrade_access_cards"></span></td>
					</tr>
					<tr>
						<th><?=$lang->license->cards_per_person?></th>
						<td align="center"><span name="upgrade_cards"></span></td>
					</tr>
					<tr>
						<th><?=$lang->license->card_formats?></th>
						<td align="center"><span name="upgrade_card_formats"></span></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->expansion_modules?></th>
						<td class="bold" align="center"><span name="upgrade_modules"></span></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->alarm_input_points?></th>
						<td class="bold" align="center"><span name="upgrade_input_points"></span></td>
					</tr>
					<tr>
						<th class="bold"><?=$lang->license->output_points?></th>
						<td class="bold" align="center"><span name="upgrade_output_points"></span></td>
					</tr>
					<tr>
						<th><?=$lang->license->online_event_history_log?></th>
						<td align="center"><span name="upgrade_event_logs"></span> <?=$lang->license->transaction?></td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding-right:5px; padding-top:10px;">
					<h3 class="hide"><?=$lang->license->current_option?></h3>
					<table width="100%" class="tbl_view hide" style="table-layout:fixed;">
					<col width="190"></col>
					<col></col>
					<? foreach( EnumTable::$attrModelOption[$_SESSION['spider_model']] as $label=>$items ) { ?>
					<tr>
						<th><?=$label?></th>
						<td><?=$system_option_to_str($items)?></td>
					</tr>
					<? } ?>
					</table>
				</td>
				<td style="padding-left:5px; padding-top:10px;">
					<h3 class="hide"><?=$lang->license->upgrade_option?></h3>
					<div id="edit_option_panel"></div>
				</td>
			</tr>
			</table>
			<br>
            <div class="box01" style="text-align:center;">
				<button type="button" onclick="$('#form_request').attr('action', '<?=base_url()?>license-valid-send-order').submit();" class="btn_large4"><?=$lang->button->request_upgrade?></button>
				<button type="button" onclick="$('#btn_license_spec_show').trigger('click');"><?=$lang->button->cancel?></button>
			</div>
		</div>
		<br>
		<br>
		
		</form>
		<? } ?>

		<br><br>
		<br>
	</td>
	<td bgcolor="#ffffff"></td>
</tr>
<tr>
	<td width="6" height="3" background="/img/menu/quick_window_bottom_left.png"></td>
	<td bgcolor="#ffffff"></td>
	<td width="6" height="3" background="/img/menu/quick_window_bottom_right.png"></td>
</tr>
</table>

<?PHP echo view('common/js.php'); ?>

<script type="text/javascript">
function create_list()
{
    open_view(0);

	var model = _data.list[0].Model;
	var type = _data.list[0].Type;

	$("input[name='model-type']", $("#license_spec")).each(function() {
		var val = $(this).val();
		var split_val = val.split("-");

		if( val == model+"-"+type ) {
			$(this).attr({ "checked":"checked" });
			$("td.type_item_"+type+".model_item_"+model, $("#license_spec")).css({ "background":"#eaeaea" });
			$(this).parent().find("label").text("<?=$lang->license->msg_current_system?>");
		}

		if( split_val[0] < model || (split_val[0] == model && split_val[1] < type) ) {
			$(this).attr({ "disabled":"disabled" });
		}
	});

	if( model < "<?=ConstTable::MODEL_ELITE?>" && type < "<?=ConstTable::CONTROLLER_TYPE_ESSENTIAL_4?>" ) {
		$("input[name='model-type'][value='<?=ConstTable::MODEL_ELITE?>-<?=ConstTable::CONTROLLER_TYPE_ELITE_36?>']", $("#license_spec")).attr({ "disabled":"disabled" });
	}

	$("input[name='model-type']", $("#license_spec")).trigger("change");
}

$(document).ready(function() {
	$("input[name='model-type']", $("#form_request"))
		.change(function(event) {
				if( $(this).is(":checked") ) {
					var val = $(this).val();
					var split_val = val.split("-");
					$("input[name='request_model']", $("#form_request")).val(split_val[0]);
					$("input[name='request_type']", $("#form_request")).val(split_val[1]);
				}
			});

    load_list();
	$("select[name='request_type']").trigger("change");
	$("#edit_option_panel").load("<?=base_url()?>license-edit-option/?model=<?=$_SESSION['spider_model']?>");
});

function edit_start()
{
    $("#view_section").hide();
    $("#edit_section").show();
    
    set_edit();
    
    $("#form_edit input[name='No']").val("1");
    $("#form_edit input[name='No']").val("1");
}

function edit_end()
{
    $("#view_section").show();
    $("#edit_section").hide();
}

function clearForm()
{
	$("#license_contact").find("input[type='text']").each(function() {
		$(this).val("");
	});
}

function confirm_sendorder()
{
	if( confirm("<?=$lang->license->sendorder_confirm?>") ) {
		$("#form_request").attr("action", "<?=base_url()?>license-send-order").submit();
	}
}

function confirm_sendregister()
{
	if( confirm("<?=$lang->license->sendregister_confirm?>") ) {
		$("#form_request").attr("action", "<?=base_url()?>license-send-register").submit();
	}
}
/*
function sendorder()
{
	var modelNo = $("input[type='radio'][name='model']:checked", $("#license_spec")).val();
	var email = $("input[type='text'][name='email']", $("#license_spec")).val();
	var phone = $("input[type='text'][name='phone']", $("#license_spec")).val();

	$.getScript("/?c=license&m=sendorder&model="+modelNo+"&email="+email+"&phone="+phone, function(data, textStatus, jqXHR) {
	});
}
*/

var currentMode		= parseInt("<?=$_SESSION['spider_model']?>", 10);
var currentType		= parseInt("<?=$_SESSION['spider_kind']?>", 10);
var attrModelSpec	= <?=json_encode(EnumTable::$attrModelSpec)?>;

function changeModel()
{
	var model	= $("select[name='request_model']").val();

	resetTypeItem();
	$("#edit_option_panel").load("<?=base_url()?>license-edit-option/?model="+model);
}

function resetTypeItem()
{
	var model	= $("select[name='request_model']").val();
	var spec	= attrModelSpec[model];

	$("select[name='request_type'] > option").remove();
	$.each(spec, function(key, val) {
		if( parseInt(model, 10) == currentMode ) {
			if( parseInt(key, 10) < currentType ) {
				return;
			}
		}

		$("<option></option>")
			.val(key)
			.text(key)
			.appendTo($("select[name='request_type']"));
	});
	$("select[name='request_type'] > option:first").attr("selected", "selected");
	$("select[name='request_type']").trigger("change");
}

function changeType()
{
	var model	= $("select[name='request_model']").val();
	var type	= $("select[name='request_type']").val();
	var spec	= attrModelSpec[model][type];

	$("span[name='upgrade_readers']").text(addCommas(spec[0]));
	$("span[name='upgrade_users']").text(addCommas(spec[2]));
	$("span[name='upgrade_access_levels']").text(addCommas(spec[3]));
	$("span[name='upgrade_access_cards']").text(addCommas(spec[4]));
	$("span[name='upgrade_cards']").text(addCommas(spec[5]));
	$("span[name='upgrade_card_formats']").text(addCommas(spec[6]));
	$("span[name='upgrade_modules']").text(addCommas(spec[7]));
	$("span[name='upgrade_input_points']").text(addCommas(spec[8]));
	$("span[name='upgrade_output_points']").text(addCommas(spec[9]));
	$("span[name='upgrade_event_logs']").text(addCommas(spec[10]));
}
</script>
