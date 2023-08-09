<div id="location">
<?
echo $lang->menu->clog.'&nbsp;&gt;&nbsp;'.$lang->menu->logviewer;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>


<script type="text/javascript">
function enable_condition()
{
    var flag = ! $("#form_search input[name='ConditionCheck']").attr("checked");
    $("#form_search input[name='ConditionText']").attr('disabled', flag);
}

function enable_condition_cardholder()
{
    var flag = ! $("#form_search input[name='ConditionCheck_CardHolder']").attr("checked");
    $("#form_search input[name='ConditionText_CardHolder']").attr('disabled', flag);
}

function enable_eventcode()
{
    var flag = ! $("#form_search input[name='EventCodeCheck']").attr("checked");
    $("#form_search select[name='EventCodeText']").attr('disabled', flag);
}

function output_item_change()
{
	if( $("#form_search").find("input[name='Item_1']").attr("checked") )	$(".tbl_list").find(".item_date").show();
	else																	$(".tbl_list").find(".item_date").hide();

	if( $("#form_search").find("input[name='Item_2']").attr("checked") )	$(".tbl_list").find(".item_datetime").show();
	else																	$(".tbl_list").find(".item_datetime").hide();

	if( $("#form_search").find("input[name='Item_3']").attr("checked") )	$(".tbl_list").find(".item_time").show();
	else																	$(".tbl_list").find(".item_time").hide();

	if( $("#form_search").find("input[name='Item_4']").attr("checked") )	$(".tbl_list").find(".item_event").show();
	else																	$(".tbl_list").find(".item_event").hide();

	if( $("#form_search").find("input[name='Item_5']").attr("checked") )	$(".tbl_list").find(".item_user").show();
	else																	$(".tbl_list").find(".item_user").hide();

	if( $("#form_search").find("input[name='Item_6']").attr("checked") )	$(".tbl_list").find(".item_user_field").show();
	else																	$(".tbl_list").find(".item_user_field").hide();

	if( $("#form_search").find("input[name='Item_7']").attr("checked") )	$(".tbl_list").find(".item_card_no").show();
	else																	$(".tbl_list").find(".item_card_no").hide();

	if( $("#form_search").find("input[name='Item_8']").attr("checked") )	$(".tbl_list").find(".item_message").show();
	else																	$(".tbl_list").find(".item_message").hide();

	if( $("#form_search").find("input[name='Item_9']").attr("checked") )	$(".tbl_list").find(".item_devicename").show();
	else																	$(".tbl_list").find(".item_devicename").hide();

	if( $("#form_search").find("input[name='Item_10']").attr("checked") )	$(".tbl_list").find(".item_type").show();
	else																	$(".tbl_list").find(".item_type").hide();

	if( $("#form_search").find("input[name='Item_11']").attr("checked") )	$(".tbl_list").find(".item_port").show();
	else																	$(".tbl_list").find(".item_port").hide();

	if( $("#form_search").find("input[name='Item_12']").attr("checked") )	$(".tbl_list").find(".item_localtime").show();
	else																	$(".tbl_list").find(".item_localtime").hide();

	if( $("#form_search").find("input[name='Item_13']").attr("checked") )	$(".tbl_list").find(".item_ack").show();
	else																	$(".tbl_list").find(".item_ack").hide();

	if( $("#form_search").find("input[name='Item_14']").attr("checked") )	$(".tbl_list").find(".item_ack_message").show();
	else																	$(".tbl_list").find(".item_ack_message").hide();

	if( $("#form_search").find("input[name='Item_15']").attr("checked") )	$(".tbl_list").find(".item_reader_type").show();
	else																	$(".tbl_list").find(".item_reader_type").hide();
    
    if( $("#form_search").find("input[name='Item_16']").attr("checked") )	$(".tbl_list").find(".item_site_name").show();
	else																	$(".tbl_list").find(".item_site_name").hide();
    
    if( $("#form_search").find("input[name='Item_17']").attr("checked") )	$(".tbl_list").find(".item_floor_name").show();
	else																	$(".tbl_list").find(".item_floor_name").hide();
}

function change_select_db()
{
	var val = $("input[type='radio'][name='sel']:checked").val();
	$("input[name='logdb']").val(val);
	$("input[name='sd_file']").val('');

	$("#file_layer").hide();
	$("#file_select").hide();

	if( val == "pc" ) {
		$("#file_layer").show();
	} else if( val == "sd" ) {
		$("#file_select").show();
		load_file_list();
	//} else if( val == "current+sd" ) {
	//	$("#file_select").show();
	//	load_file_list();
	} else {
	}

	$("#list_body").empty();
	$("#pagination").empty();

	$.getJSON("/?c=<?=$this->class?>&m=clear_receive_file", function(data, textStatus, jqXHR) {
	});
}

function load_file_list()
{
	$("#file_name").empty();
	$.getJSON("/?c=<?=$this->class?>&m=file_list", function(data, textStatus, jqXHR) {
		if( check_error(data) ) {
			$.each(data, function(i, val) {
				$("#file_name").append('<option value="'+val+'">'+val+'</option>');
			});
			copy_sd_file();
		}
	});

	$('#file_select').show();
}

function copy_sd_file()
{
	var file_name = $("#file_name").val();
	$("input[name='sd_file']").val(file_name);
	/*
	var file_name = $("#file_name").val();
	if (file_name != null) {
		$.getJSON("/?c=<?=$this->class?>&m=sd_receive", { file_name:file_name }, function(data, textStatus, jqXHR) {
		});
	}
	*/
}

function checkUpdateFile(byId, maxSize)
{   
    var node = document.getElementById(byId);
    
    if (node.type == "file") {
        var sFileName = node.value;
        if (sFileName.length > 0) {
            
            if (sFileName.substr(sFileName.length - 3, 3).toLowerCase() != ".db") {
                node.value = "";
                alert("<?=$this->lang->user->error_file_extensions?>" + ": .db");
                return false;
            }
        }
    }
    
    var sFileName = node.value;
    var iFileSize = node.files[0].size;
    if( iFileSize > maxSize ){
        node.value = "";
        alert("<?=$this->lang->user->error_file_size?> " + iFileSize + " (<?=$lang->menu->max?> : " + maxSize +")");
        return false;
    }
}
</script>

<div id="search_section">
	<h3>DB</h3>
	<div class="box02">
	<form id="form_select_db" method="post" action="/?c=<?=$this->class?>&m=upload_db" enctype="multipart/form-data" target="frame_exe">
	<?=Form::hidden('page', '1')?>
		<table class="tbl_view">
		<tr>
			<th width="120"><?=$lang->addmsg->select_db?></th>
			<td width="1">:</td>
			<td>
				<input type="radio" name="sel" value="current" checked onclick="change_select_db()" /> <?=$lang->addmsg->current_db?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="pc" onclick="change_select_db()" /> <?=$lang->menu->userpc?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="sd" onclick="change_select_db()" /> <?=$lang->menu->sdcard?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="current+sd" onclick="change_select_db()" /> <?=$lang->menu->current_sd?> &nbsp; &nbsp; &nbsp;
			</td>
		</tr>
		<tr id="file_layer" class="hide">
			<th><?=$lang->menu->file?></th>
			<td width="1">:</td>
			<td>
				<input type="file"  id="upload_file" name="upload_file"  accept=".db" size="45" onchange="checkUpdateFile('upload_file', 1000 * 1024 * 100)" />
                <button type="button" onclick="$('#form_select_db').submit();"><?=$lang->button->upload?></button>
			</td>
		</tr>
		<tr id="file_select" class="hide">
			<th><?=$lang->menu->file?></th>
			<td width="1">:</td>
			<td>
				<select name="file_name" id="file_name" onchange="copy_sd_file();"></select>
			</td>
		</tr>
		</table>

	</form>
	</div>
	<br>


	<h3>Search</h3>
	<div class="box02">
	<form id="form_search" method="post" target="frame_exe" onkeypress="return event.keyCode != 13;">
	<?=Form::hidden('logdb', 'current')?>
	<?=Form::hidden('sd_file', '')?>
		<table class="tbl_view">
		<tr>
			<th width="120"><?=$lang->addmsg->log_date?></th>
			<td width="1">:</td>
			<td>
				<?=Form::input('StartDate', '', array('class'=>'date', 'size'=>'14', 'readonly'=>'readonly'))?> ~ <?=Form::input('EndDate', '', array('class'=>'date', 'size'=>'14', 'readonly'=>'readonly'))?>
			</td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->log_time?></th>
			<td width="1">:</td>
			<td>
				<?=Form::inputnum('StartTimeHH', '', array('class'=>'time_h', "MAXLENGTH"=>"2"))?>:<?=Form::inputnum('StartTimeMM', '', array('class'=>'time_m', "MAXLENGTH"=>"2"))?> ~
				<?=Form::inputnum('EndTimeHH', '', array('class'=>'time_h', "MAXLENGTH"=>"2"))?>:<?=Form::inputnum('EndTimeMM', '', array('class'=>'time_m', "MAXLENGTH"=>"2"))?>
			</td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->log_type?></th>
			<td width="1">:</td>
			<td>
				<div style="width:130px; float:left;"><?=Form::checkbox('TypeWeb', $lang->addmsg->log_web, FALSE, '1')?></div>
				<div style="width:130px; float:left;"><?=Form::checkbox('TypeReader', $lang->addmsg->log_readerin, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeContact', $lang->addmsg->log_contact, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeLook', $lang->addmsg->log_doorlock, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeRex', $lang->addmsg->log_rex, FALSE, '1')?></div>
				<? if( $_SESSION['spider_model'] != ConstTable::MODEL_ESSENTIAL &&
				       $_SESSION['spider_model'] != ConstTable::MODEL_TE_STANDALONE &&
					   $_SESSION['spider_model'] != ConstTable::MODEL_TE_SERVER) { ?>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeElevator', $lang->addmsg->log_elevator, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeElevatorOut', $lang->addmsg->log_elevatorout, FALSE, '1')?></div>
				<? } ?>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeAuxOut', $lang->addmsg->log_auxoutput, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeAuxIn', $lang->addmsg->log_auxinput, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeSystem', $lang->addmsg->log_system, FALSE, '1')?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('TypeNetwork', $lang->addmsg->log_network, FALSE, '1')?></div>
            </td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->item_devicename?></th>
			<td width="1">:</td>
			<td>
				<?=Form::checkbox('ConditionCheck', '', FALSE, '1', array('onclick'=>'enable_condition()'))?>&nbsp;
				<?=Form::input('ConditionText', "", array('disabled'=>'true',"size"=>"20"))?>
			</td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->item_cardholdername?></th>
			<td width="1">:</td>
			<td>
				<?=Form::checkbox('ConditionCheck_CardHolder', '', FALSE, '1', array('onclick'=>'enable_condition_cardholder()'))?>&nbsp;
				<?=Form::input('ConditionText_CardHolder', "", array('disabled'=>'true',"size"=>"20"))?>
			</td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->eventname?></th>
			<td width="1">:</td>
			<td>
				<?=Form::checkbox('EventCodeCheck', '', FALSE, '1', array('onclick'=>'enable_eventcode()'))?>&nbsp;
				<?
				$eventcode = $lang->eventcode->messages;
				natsort($eventcode);
				echo Form::select('EventCodeText', '', $eventcode, array('onclick'=>'output_item_change()', 'disabled'=>'true'));
				?>
			</td>
		</tr>
		<tr>
			<th width="120"><?=$lang->addmsg->output_item?></th>
			<td width="1">:</td>
			<td>
				<div style="width:130px; float:left;"><?=Form::checkbox('Item_1', $lang->addmsg->item_date, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_2', $lang->addmsg->item_datetime, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_3', $lang->addmsg->item_time, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
				<? if( $_SESSION['spider_model'] != ConstTable::MODEL_ESSENTIAL ) { ?>
                    <div style="width:130px; float:left;"><?=Form::checkbox('Item_12', $lang->addmsg->item_localtime, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
				<? } ?>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_4', $lang->addmsg->item_event, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_5', $lang->addmsg->item_user, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_6', $lang->addmsg->item_user_field, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_7', $lang->addmsg->item_card_no, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_8', $lang->addmsg->item_message, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_9', $lang->addmsg->item_devicename, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_10', $lang->addmsg->item_type, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_11', $lang->addmsg->item_port, TRUE, '1', array('onclick'=>'output_item_change()'))?></div>
				<!--
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_13', $lang->addmsg->item_ack, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_14', $lang->addmsg->item_ack_message, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
				-->
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_15', $lang->addmsg->item_reader_type, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <? if($this->is_option(ConstTable::OPTION_PARTITION)) { ?>
                    <div style="width:130px; float:left;"><?=Form::checkbox('Item_16', $lang->addmsg->item_site_name, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
                <? } ?>    
				<!--
                <div style="width:130px; float:left;"><?=Form::checkbox('Item_17', $lang->addmsg->item_floor_name, FALSE, '1', array('onclick'=>'output_item_change()'))?></div>
				-->
			</td>
		</tr>
		
		</table>

		<table class="list_button_set">
		<tr>
			<td height="30" align="center" valign="middle">
				<button type="button" onclick="load_search('true')"><?=$lang->button->search?></button>
			</td>
		</tr>
		</table>
	</form>
	</div>

	<div id="list_box" class="box02">
	</div>
</div>

<script type="text/javascript">

function load_search(cp, page)
{
	page = page || 1;
	var url = '/?c='+ _class + '&m=select&action=search&cp='+ cp +'&page='+ page +'&'+ $("#form_search").serialize();

	show_loading();
	$('#list_box').empty().show().load(url, function() { output_item_change(); hide_loading(); });
}

function open_preview()
{
	var url = '/?c='+ _class + '&m=select&action=preview&'+ $("#form_search").serialize();
	window.open(url, "ReportPrintPrivew", "width=800, height=800, directories=no, location=no, menubar=no, status=no, toolbar=no, scrollbars=yes");
}

function download()
{
	var url = '/?c='+ _class + '&m=select&action=download&'+ $("#form_search").serialize();
	$('#form_search').attr('action', url).submit();
}

$(document).ready(function() {
	$("#form_search input[name='StartDate']").DatePicker({
		format			: "m-d-Y",
		date			: "<?=date("m-d-Y")?>",
		current			: "<?=date("m-d-Y")?>",
		starts			: 0,
		position		: "bottom",
		onBeforeShow	: function() {
			var element	= $("#form_search input[name='StartDate']");
			if( element.val() == "" )
				element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
			else
				element.DatePickerSetDate(element.val(), true);
		},
		onChange		: function(formated, dates){
            var element	= $("#form_search input[name='StartDate']");
            if( element.val() != formated ){
            	$("#form_search input[name='StartDate']").val(formated);
                $("#form_search input[name='StartDate']").DatePickerHide();
            }
		}
	});
	
	$("#form_search input[name='EndDate']").DatePicker({
		format			: "m-d-Y",
		date			: "<?=date("m-d-Y")?>",
		current			: "<?=date("m-d-Y")?>",
		starts			: 0,
		position		: "bottom",
		onBeforeShow	: function() {
			var element	= $("#form_search input[name='EndDate']");
			if( element.val() == "" )
				element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
			else
				element.DatePickerSetDate(element.val(), true);
		},
		onChange		: function(formated, dates){
            var element	= $("#form_search input[name='EndDate']");
            if( element.val() != formated ){
                $("#form_search input[name='EndDate']").val(formated);
                $("#form_search input[name='EndDate']").DatePickerHide();
            }
		}
	});

	output_item_change();
});
</script>