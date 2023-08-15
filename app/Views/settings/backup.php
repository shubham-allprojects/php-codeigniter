<div id="location">
<?
echo $lang->menu->systems.'&nbsp;&gt;&nbsp;'.$lang->menu->backup;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">
function create_list()
{
    open_view(0);
}

$(document).ready(function() 
{
    load_list();
});

// edit data ä���
function set_edit()
{
	$.each(_current, function(name, value) 
	{
        var element = $("#form_edit input[name='"+ name +"']");
        if( element.attr("type") == "checkbox" )
        {
            if( value == "1")   element.attr("checked", true);
            else                element.attr("checked", false);
        }
        else if( element.attr("type") == "radio" )
        {
            $("#form_edit input:radio[name='"+ name +"']").filter("input[value='"+ value +"']").attr('checked', true);
        }
        else
        {
            element.val(value);
        }
        
        $("#form_edit select[name='"+ name +"']").val(value);
        $("#form_edit input[name='"+ name +"']").val(value);
    });
    
    enable_form_edit();
    set_default();
}

function enable_form_edit()
{
    if( $("#form_edit input[name='Enable']").attr("checked") )  
    {
    	$("#form_edit input[name='Enable']").val(1);
        flag = false;
    }
    else    
    {
    	$("#form_edit input[name='Enable']").val(0);
    	flag = true;
    }

    $("#form_edit input[name='DeviceType']").attr("disabled", flag);
    $("#form_edit select[name='BackupTime']").attr("disabled", flag);
}

function set_default()
{
	$("#form_edit input:radio[name='DeviceType']").filter("input[value='"+ _current['Device'] +"']").attr('checked', true);

	$("#form_edit select").each(function() {
		dropdownEmptyToFirst($(this));
	});
}
</script>

<div id="edit_section" class="hide">
	<h2>:: <?=$lang->menu->backup?></h2>
	<div class="box01">
	<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update" target="_self">
		<h3><?=$lang->addmsg->schedule_backup?></h3>
		<table class="tbl_view">
		<tr>
            <th width="150"><?=$lang->menu->Name?></th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->network->FTPEnable?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('Enable', '', FALSE, '1', array('onclick'=>'enable_form_edit()'))?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->addmsg->backup_device?></th>
            <td width="1">:</td>
            <td><?=Form::radio('DeviceType', '', $this->arr_device, '&nbsp;&nbsp;')?></td>
        </tr>
        <tr>
            <th><?=$lang->addmsg->backuptime?></th>
            <td width="1">:</td>
            <td><?=Form::select('BackupTime', '', EnumTable::$attrTimeList)?>&nbsp;&nbsp;&nbsp;<label><?=$lang->common->msg_every_day?></label></td>
        </tr>
        </table>
		<div class="button_set">
<? if( $this->is_auth(92, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->save?></button>&nbsp;&nbsp;
<? } else { ?>
            <button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
<? } ?>
            <button type="button" onclick="open_edit(0); set_default();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>		
    </form>
	</div>
    
    <div class="space" style="height:5px"></div>
</div>

<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->backup?></h2>
    <div class="box01">
        <h3><?=$lang->addmsg->schedule_backup?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->menu->Name?></th>
            <td width="1">:</td>
            <td id="view_BackupName"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->network->FTPEnable?> </th>
            <td width="1">:</td>
            <td id="view_EnableStr"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->addmsg->backup_device?> </th>
            <td width="1">:</td>
            <td id="view_DeviceStr"></td>
        </tr>
        <tr>
            <th><?=$lang->addmsg->backuptime?></th>
            <td width="1">:</td>
            <td>
				<span id="view_BackupTimeStr"></span>
				&nbsp;&nbsp;&nbsp;
				<label><?=$lang->common->msg_every_day?></label>
			</td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(92, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="open_edit(0); set_default();"><?=$lang->button->edit?></button>
<? } ?>
        </div>
    </div>
        
    <div class="space" style="height:5px"></div>
</div>

<script>
function submit_send()
{
	$('#form_edit3').find('input[name="confirm_pw"]').val($('#form_send').find('input[name="confirm_pw"]').val());
	$('#form_edit3').submit();
}

function confirm_send()
{
	$('#form_send').find('input[name="confirm_pw"]').val('');
	$('#form_edit3').find('input[name="confirm_pw"]').val('');
	confirm_dialog("<?=$this->lang->common->backup_warning_message?>");
}

function confirm_dialog(message, callback) {
	$('#confirm-dialog').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		onShow: function (dialog) {
			var modal = this;

			$('.message', dialog.data[0]).append(message);
		}
	});
}
</script>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=_exe" onsubmit="submit_send(); $.modal.close(); return false;">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="submit_send(); $.modal.close();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div style="padding: 0 10px;">
    <form id="form_edit3" method="post" action="/?c=<?=$this->class?>&m=_exec" target="_self">
	<input type="hidden" name="confirm_pw" value="">
		<div class="space" style="height:5px"></div>
		
		<h3><?=$lang->addmsg->immediate_backup?></h3>
		<table class="tbl_view">
		<tr>
			<th width="150"><?=$lang->menu->backuptype?></th>
			<td width="1">:</td>
			<td>
				<input type="radio" name="sel" value="pc" checked/> <?=$lang->menu->userpc?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="sd"  /> <?=$lang->menu->sdcard?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="ftp" /> <?=$lang->menu->ftpserver?> &nbsp; &nbsp; &nbsp;
			</td>
		</tr>
		</table>

		<div class="button_set">
<? if( $this->is_auth(92, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->menu->backup?></button>
<? } else { ?>
			<button type="button" onclick="confirm_send()"><?=$lang->menu->backup?></button>
<? } ?>		
		</div>
	</form>
</div>