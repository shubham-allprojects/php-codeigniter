<div id="location">
<?php
use App\Libraries\EnumTable;
echo $lang->menu->networks . '&nbsp;&gt;&nbsp;' . $lang->menu->api;
?>
	<button class="btn_help" onclick="openHelp('api', '<?=$lang->_lang?>')">Help</button>
</div>

<!-- modal content -->
<div id='confirm-dialog' class="confirm-dialog-force-sync">
	<div class='header'><span></span></div>
	<div class='message message-confirm-dialog-force-sync'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="force_sync_confirm_pw" id="force_sync_confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" id="force_sync_modal_button" onclick="dialog_submit('force_sync_confirm_pw');show_loading();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
</div>

<!-- modal content -->
<div id='confirm-dialog' class="confirm-dialog-save-api">
	<div class='header'><span></span></div>
	<div class='message message-confirm-dialog-save-api'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="save_api_confirm_pw" id="save_api_confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" id="save_api_modal_button" onclick="dialog_submit('save_api_confirm_pw');show_loading();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
</div>

<!-- modal content -->
<div id='confirm-dialog' class="confirm-dialog-delete-api">
	<div class='header'><span></span></div>
	<div class='message message-confirm-dialog-delete-api'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="delete_api_confirm_pw" id="delete_api_confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" id="delete_api_modal_button" onclick="dialog_submit('delete_api_confirm_pw');show_loading();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
</div>

<div id="edit_section" class="">
    <h2>:: <?=$lang->menu->ipaddress?></h2>
    <div class="box01">
        <form id="form_edit1" method="post" action="">
            <h3><?=$lang->menu->basic?></h3>
            <table class="tbl_view">
            <tr>
                <th>URL *</th>
                <td width="1">:</td>
				<td><?php echo Form::input('api_url'); ?></td>
            </tr>
            <tr>
                <th>Username *</th>
                <td width="1">:</td>
                <td><?php echo Form::input('api_username'); ?></td>
            </tr>
            <tr>
                <th>Password *</th>
                <td width="1">:</td>
                <td><input type="password" name="api_password" value=""></td>
            </tr>
			<tr>
                <th>Property ID *</th>
                <td width="1">:</td>
				<td>
					<?php echo Form::input('property_id', "", array("id" => "property_id")); ?>
					<span id="property_id_msg"></span>
				</td>
            </tr>
			<tr>
                <th>Property Name</th>
                <td width="1">:</td>
                <td><?php echo Form::input('property_name', "", array("id" => "property_name", "readonly" => "readonly", "style" => "background-color: lightgrey")); ?></td>
            </tr>
			<tr>
                <th>Property Address</th>
                <td width="1">:</td>
				<td><?php echo Form::input('property_address', "", array("id" => "property_address", "readonly" => "readonly", "style" => "background-color: lightgrey;width: 260px;")); ?></td>

            </tr>
			<tr>
                <th>Property Website</th>
                <td width="1">:</td>
                <td><?php echo Form::input('property_website', "", array("id" => "property_website", "readonly" => "readonly", "style" => "background-color: lightgrey;width: 260px;")); ?></td>
            </tr>
			<tr class="data_exists_row">
				<th width="150"><?php echo $lang->addmsg->exist_data; ?></th>
				<td width="1">:</td>
				<td><?php echo Form::radio('ExistData', '', EnumTable::$attrDataExistsAPI, '&nbsp;&nbsp;'); ?></td>
			</tr>
            <tr>
                <th>Schedule Time 1 *</th>
                <td width="1">:</td>
				<td><input type="checkbox" name="schedule_time_1" id="schedule_time_1" value="1" checked onclick="return false"/>&nbsp;&nbsp;
					<?php echo Form::select('schedule_time_1_h', '00', EnumTable::$apiattrHourList00, array('id' => 'schedule_time_1_h')); ?>
					<?php echo Form::select('schedule_time_1_m', '00', EnumTable::$apiattrMinList00, array('id' => 'schedule_time_1_m')); ?>
				</td>

            </tr>
            <tr>
                <th>Schedule Time 2</th>
                <td width="1">:</td>
				<td><input type="checkbox" name="schedule_time_2" id="schedule_time_2" value="1"/>&nbsp;&nbsp;
					<?php echo Form::select('schedule_time_2_h', '00', EnumTable::$apiattrHourList00, array('id' => 'schedule_time_2_h', 'disabled' => 'disabled')); ?>
					<?php echo Form::select('schedule_time_2_m', '00', EnumTable::$apiattrMinList00, array('id' => 'schedule_time_2_m', 'disabled' => 'disabled')); ?>
				</td>
            </tr>
            <tr>
                <th>Schedule Time 3</th>
                <td width="1">:</td>
				<td><input type="checkbox" name="schedule_time_3" id="schedule_time_3" value="1"/>&nbsp;&nbsp;
					<?php echo Form::select('schedule_time_3_h', '00', EnumTable::$apiattrHourList00, array('id' => 'schedule_time_3_h', 'disabled' => 'disabled')); ?>
					<?php echo Form::select('schedule_time_3_m', '00', EnumTable::$apiattrMinList00, array('id' => 'schedule_time_3_m', 'disabled' => 'disabled')); ?>
				</td>
            </tr>
            <tr>
                <th>Schedule Time 4</th>
                <td width="1">:</td>
				<td><input type="checkbox" name="schedule_time_4" id="schedule_time_4" value="1"/>&nbsp;&nbsp;
					<?php echo Form::select('schedule_time_4_h', '00', EnumTable::$apiattrHourList00, array('id' => 'schedule_time_4_h', 'disabled' => 'disabled')); ?>
					<?php echo Form::select('schedule_time_4_m', '00', EnumTable::$apiattrMinList00, array('id' => 'schedule_time_4_m', 'disabled' => 'disabled')); ?>
				</td>
            </tr>

            </table>

            <div class="button_set">
                <button type="button" onclick="test_connection()" class="btn_large4">Test Connection</button>&nbsp;&nbsp;
				<button type="button" id="save_click"><?=$lang->button->save?></button>&nbsp;&nbsp;
				<button type="button" onclick="open_edit(_seq);init_edit()"><?=$lang->button->reset?></button>&nbsp;&nbsp;
				<button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
            </div>
        </form>
        </div>
</div>

<div id="view_section" class="">
    <h2>:: <?=$lang->menu->ipaddress?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th>URL *</th>
            <td width="1">:</td>
            <td id="view_URL"></td>
        </tr>
        <tr>
            <th>Username *</th>
            <td width="1">:</td>
            <td id="view_UserName"></td>
        </tr>
        <tr>
            <th>Property ID *</th>
            <td width="1">:</td>
            <td id="view_PropertyID"></td>
        </tr>
        <tr>
            <th>Property Name</th>
            <td width="1">:</td>
            <td id="view_MarketingName"></td>
        </tr>
        <tr>
            <th>Property Address</th>
            <td width="1">:</td>
            <td id="view_Address"></td>
        </tr>
        <tr>
            <th>Property Website</th>
            <td width="1">:</td>
            <td id="view_Website"></td>
        </tr>
		<tr>
            <th>Data Schedule Time</th>
            <td width="1">:</td>
            <td id="view_Schedule"></td>
        </tr>
        </table>
        <div class="button_set">
<?php if ($baseController->is_auth(96, 3) != true): ?>
            <button type="button" onclick="alert('<?=$lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<?php else: ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>
			<button type="button" id="force_sync" style="display:none;">Force Sync</button>
			<button type="button" id="delete_api_data" style="width: 117px;"><?php echo $lang->button->delete_api ?></button>
<?php endif;?>

        </div>
    </div>
</div>

<?PHP echo view('common/js'); ?>
<script type="text/javascript">
function create_list()
{
    open_view(0);
	load_api();
}

$(document).ready(function() {
    load_list();

	var encrypt_url = "/?c=<?php echo `api`; ?>&m=encrypt_data";
	$.ajax({
		url: encrypt_url,
        success: function(response) {
			console.log(response);
		}
	});

	var schedule_url = "/?c=<?php echo `api`; ?>&m=fetch_schedule_data";
	var schedule_html = '';

	$.ajax({
		url: schedule_url,
        success: function(response) {
			response = $.parseJSON(response);
			cvalue = 1;
			for(var i = 0; i<response.length; i++){
				no = response[i].No;
				ScheduleTime = response[i].ScheduleTime;

				if(ScheduleTime == "24:00"){
					Schedulehr = "NA";
					Schedulemin = "NA";
					if(cvalue != 1)
						$('#schedule_time_'+cvalue).attr('checked', false);
					else
						$('#schedule_time_'+cvalue).attr('checked', true);
				}else{
					scheduleArray = ScheduleTime.split(":");
					Schedulehr = scheduleArray[0];
					Schedulemin = scheduleArray[1];
					$('#schedule_time_'+cvalue).attr('checked', true);

					$("#schedule_time_"+cvalue+"_h").attr("disabled", false);
					$("#schedule_time_"+cvalue+"_m").attr("disabled", false);
				}

				$("#schedule_time_"+cvalue+"_h").val(Schedulehr);
				$("#schedule_time_"+cvalue+"_m").val(Schedulemin);

				if(ScheduleTime != "24:00")
					schedule_html += 'Schedule '+cvalue+' - '+Schedulehr+':'+Schedulemin+'<br>';

				cvalue++;
			}

			$('#view_Schedule').html(schedule_html);
        }
	});

	$('#schedule_time_2').change(function(){

		if (this.checked) {
			$("#schedule_time_2_h").removeAttr("disabled");
			$("#schedule_time_2_m").removeAttr("disabled");
			$("#schedule_time_2_h").val("00");
			$("#schedule_time_2_m").val("00");
		} else {
			$("#schedule_time_2_h").attr("disabled", true);
			$("#schedule_time_2_m").attr("disabled", true);
			$("#schedule_time_2_h").val("NA");
			$("#schedule_time_2_m").val("NA");
		}
	});

	$('#schedule_time_3').change(function(){

		if (this.checked) {
			$("#schedule_time_3_h").removeAttr("disabled");
			$("#schedule_time_3_m").removeAttr("disabled");
			$("#schedule_time_3_h").val("00");
			$("#schedule_time_3_m").val("00");
		} else {
			$("#schedule_time_3_h").attr("disabled", true);
			$("#schedule_time_3_m").attr("disabled", true);
			$("#schedule_time_3_h").val("NA");
			$("#schedule_time_3_m").val("NA");
		}
	});

	$('#schedule_time_4').change(function(){

		if (this.checked) {
			$("#schedule_time_4_h").removeAttr("disabled");
			$("#schedule_time_4_m").removeAttr("disabled");
			$("#schedule_time_4_h").val("00");
			$("#schedule_time_4_m").val("00");
		} else {
			$("#schedule_time_4_h").attr("disabled", true);
			$("#schedule_time_4_m").attr("disabled", true);
			$("#schedule_time_4_h").val("NA");
			$("#schedule_time_4_m").val("NA");
		}
	});

	$("input:radio[name=ExistData][disabled=false]:first").attr('checked', true);

	$('#force_sync').click(function(){
		if (!$('input[name="ExistData"]').is(':checked')) {
			alert('Error: Data Exists field is required');
		}
		else {
			confirm_dialog("Are you sure you want to force sync the data?","confirm-dialog-force-sync");
		}
	});

	$('#delete_api_data').click(function(){
		//submit_delete_api_data();
		confirm_dialog("Are you sure you want to delete data?","confirm-dialog-delete-api");
	});

	$('#save_click').click(function(){
		confirm_dialog("Are you sure you want to save?","confirm-dialog-save-api");
	});

	$('#save_api_confirm_pw').bind('focus keypress', function(event){
        if(event.keyCode == 13){
            $('#save_api_modal_button').click();
        }
    });

	$('#force_sync_confirm_pw').bind('focus keypress', function(event){
        if(event.keyCode == 13){
            $('#force_sync_modal_button').click();
        }
    });

	$('#delete_api_confirm_pw').bind('focus keypress', function(event){
        if(event.keyCode == 13){
            $('#delete_api_modal_button').click();
        }
    });


});

// edit data äࠬҢ
function set_edit()
{
    set_edit_network('form_edit1');
    set_edit_network('form_edit2');
    set_edit_network('form_edit3');
    set_edit_network('form_edit4');
    set_edit_network('form_edit5');

	if($("#property_id").val().length == 0){
		$("#force_sync").hide();
		$(".data_exists_row").show();
	}else{
		$(".data_exists_row").hide();
		$("#property_id").attr("readonly","readonly"); // disable property id field readonly
		$("#property_id").attr("style","background-color: lightgrey");
		$("#property_id_msg").html("");
		$("#property_id_msg").append("</br>To update Property ID, please delete all API data.");
	}
}

function set_edit_network(form)
{
    $.each(_current, function(name, value) {
        var element = $("#"+ form +" input[name='"+ name +"']");
        if( element.attr("type") == "checkbox" ){
            if( value == "1"){
                element.attr("checked", true);
            }else{
                element.attr("checked", false);
            }
        }else if( element.attr("type") == "radio" ){
            $("#"+ form +" input:radio[name='"+ name +"']").filter("input[value='"+ value +"']").attr('checked', true);
        }else{
            element.val(value);
        }

        if( $("#"+ form +" select[name='"+ name +"']").attr("multiple") == true )
            $("#"+ form +" select[name='"+ name +"']").val(eval(value));
        else
            $("#"+ form +" select[name='"+ name +"']").val(value);

    });

    enable_form_edit1();
    enable_form_edit2();
    enable_form_edit3();
    enable_form_edit4();
}

function enable_form_edit1()
{
    if( $("#form_edit1 input:radio[name='IPType']:checked").val() == '1' ) {
		flag = false;
		$("#form_edit1 input[name='Gateway']").val(_current['Gateway']);
	} else {
		flag = true;
		$("#form_edit1 input[name='Gateway']").val('192.168.1.1');
	}

    $("#form_edit1 input[name='IPAddress']").attr("disabled", flag);
    $("#form_edit1 input[name='Subnet']").attr("disabled", flag);
    $("#form_edit1 input[name='Gateway']").attr("disabled", flag);
}

function enable_form_edit2()
{
    if( $("#form_edit2 input[name='DDNSEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit2 select[name='DDNSServer']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSServer']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSID']").attr("disabled", flag);
    $("#form_edit2 input[name='DDNSPassword']").attr("disabled", flag);
}

function enable_form_edit3()
{
    if( $("#form_edit3 input[name='FTPEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit3 input[name='FTPAddress']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPort']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPID']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPassword']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPPassive']").attr("disabled", flag);
    $("#form_edit3 input[name='FTPDir']").attr("disabled", flag);
}

function enable_form_edit4()
{
    if( $("#form_edit4 input[name='SMTPEnable']").attr("checked") )
            flag = false;
    else    flag = true;

    $("#form_edit4 input[name='SMTPServer']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPID']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPPassword']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPNumber']").attr("disabled", flag);
}

function init_edit(){
	/*$("input[type='text']").val("");
	$('#property_address').val('');
	$("input[type='password']").val("");*/
	$('input:checkbox').removeAttr('checked');
	$('#schedule_time_1').attr("checked",true);
	$("[name=ExistData]").filter("[value='0']").attr("checked","checked");

	$('#schedule_time_2_h').attr("disabled",true);
	$('#schedule_time_2_m').attr("disabled",true);
	$('#schedule_time_3_h').attr("disabled",true);
	$('#schedule_time_3_m').attr("disabled",true);
	$('#schedule_time_4_h').attr("disabled",true);
	$('#schedule_time_4_m').attr("disabled",true);
}

function submit_force_sync()
{
	var force_sync_url = "/?c=<?php echo `api`; ?>&m=force_sync";

	show_loading()
	$.ajax({
		type: "POST",
		url: force_sync_url,
        success: function(response) {
			hide_loading()
			response = $.parseJSON(response)

			if(response.error == 1){
				alert(response.message);
			}else{
				alert(response.message);
			}

        }
	});
}

function load_api(){
	$.ajax({
		type: "POST",
		data: $('#form_edit1').serialize(),
		url: "/?c=<?php echo `api`; ?>&m=is_apiData_present",
        success: function(response) {
			if(response == 0){
				$("#force_sync").hide();
			}else{
				$("#force_sync").show();
			}

        }
	});
}

function test_connection()
{
	var test_connection_url = "/?c=<?php echo `api`; ?>&m=test_api_connection";

	show_loading()
	$.ajax({
		type: "POST",
		data: $('#form_edit1').serialize(),
		url: test_connection_url,
        success: function(response) {
			hide_loading()
			response = $.parseJSON(response)

			if(response.error == 1){
				$('#property_name').val('');
				$('#property_address').val('');
				$('#property_website').val('');
				alert(response.message);
			}else{

				apiResponse = $.parseJSON(response.message.message)
				property_address = apiResponse.Address + ',' + apiResponse.City + ',' + apiResponse.State + ',' + apiResponse.PostalCode;
				property_website = apiResponse.webSite;
				marketing_name = apiResponse.MarketingName;

				$('#property_name').val(marketing_name);
				$('#property_address').val(property_address);
				$('#property_website').val(property_website);
				alert("Connected successfully");
			}

        }
	});
}

function submit_save_api_data()
{
	var save_data_url = "/?c=<?php echo `api`; ?>&m=save_api_data";

	show_loading()
	$.ajax({
		type: "POST",
		data: $('#form_edit1').serialize(),
		url: save_data_url,
        success: function(response) {
			hide_loading()
			response = $.parseJSON(response);
			if(response.error == 1){
				$('#property_name').val('');
				$('#property_address').val('');
				$('#property_website').val('');
				alert(response.message);
			}else{
				apiResponse = $.parseJSON(response.api_data);
				property_address = apiResponse.Address + ',' + apiResponse.City + ',' + apiResponse.State + ',' + apiResponse.PostalCode;
				property_website = apiResponse.webSite;
				marketing_name = apiResponse.MarketingName;

				$('#property_name').val(marketing_name);
				$('#property_address').val(property_address);
				$('#property_website').val(property_website);
				alert(response.message);
				window.location.href = "/?c=api";
			}

        }
	});
}

function submit_delete_api_data()
{
	var delete_api_url = "/?c=<?php echo `api`; ?>&m=delete_api_data";

	show_loading()
	$.ajax({
		type: "POST",
		url: delete_api_url,
        success: function(response) {
			hide_loading()
			response = $.parseJSON(response)
			//console.log(response);

			if(response.error == 1){
				alert(response.message.message);
			}else{
				alert(response.message.message);
				window.location.href = "/?c=api";
			}

        }
	});
}

function dialog_submit(type){
	var admin_pwd_api_url = "/?c=<?php echo `api`; ?>&m=check_admin_pwd";

	pwd = $('#'+type).val();
	show_loading()
	if(pwd != ''){
		$.ajax({
			type: "POST",
			data: 'confirm_pw='+pwd,
			url: admin_pwd_api_url,
			success: function(response) {
				hide_loading()
				response = $.parseJSON(response)
				if(response.error == 1){
					alert(response.message);
				}else{
					if(type == 'force_sync_confirm_pw')
						submit_force_sync();
					else if(type == 'save_api_confirm_pw')
						submit_save_api_data();
					else if(type == 'delete_api_confirm_pw')
						submit_delete_api_data();
				}
			}
		});
	}else{
		alert("Please enter password")
	}
}

function confirm_dialog(message, callback) {
	$('.'+callback).modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container',
		onShow: function (dialog) {
			var modal = this;

			$('.message-'+callback, dialog.data[0]).append(message);
		}
	});
}
</script>