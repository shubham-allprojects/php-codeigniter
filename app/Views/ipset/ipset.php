<div id="location">
<?php
echo $lang->menu->networks.'&nbsp;&gt;&nbsp;'.$lang->menu->ipaddress;
?>
	<button class="btn_help" onclick="openHelp('ipset', '<?=$lang->_lang?>')">Help</button>
</div>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=ipset&m=_exe" onsubmit="submit_send(); $.modal.close(); return false;">
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

<div id="edit_section" class="hide">
    <h2>:: <?=$lang->menu->ipaddress?></h2>
    <div class="box01">
        <form id="form_edit1" method="post" action="/?c=ipset&m=update1">
		<input type="hidden" name="confirm_pw" value="">
            <?=Form::hidden("No")?>
            <h3><?=$lang->menu->basic?></h3>
            <table class="tbl_view">
            <tr>
                <th width="150"><?=$lang->network->IPType?> *</th>
                <td width="1">:</td>
                <td><?=Form::radio('IPType', '', $baseController->arr_ip_type, '&nbsp;&nbsp;', array('onclick' => 'enable_form_edit1()'))?></td>
            </tr>
            <tr>
                <th><?=$lang->network->IPAddress?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('IPAddress')?></td>
            </tr>
            <tr>
                <th><?=$lang->network->Subnet?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('Subnet')?></td>
            </tr>
            <tr>
                <th><?=$lang->network->Gateway?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('Gateway')?></td>
            </tr>
            <tr>
                <th><?=$lang->network->DNS1?></th>
                <td width="1">:</td>
                <td><?=Form::input('DNS1')?> (<?=$lang->menu->optional?>)</td>
            </tr>
            <tr>
                <th><?=$lang->network->DNS2?></th>
                <td width="1">:</td>
                <td><?=Form::input('DNS2')?> (<?=$lang->menu->optional?>)</td>
            </tr>
            <tr>
                <th><?=$lang->network->HTTPPort?></th>
                <td width="1">:</td>
                <td><?=Form::inputnum('HTTPPort', '', array("MAXLENGTH" => "5"))?> (<?=$lang->menu->default80?>)</td>
            </tr>
            <tr>
                <th><?=$lang->network->HTTPS?></th>
                <td width="1">:</td>
				<td><?=Form::checkbox('HTTPS', '', false, '1')?> (<?=$lang->network->required_for_rmc?>)</td>
            </tr>
            <tr>
                <th><?=$lang->network->HTTPSPort?></th>
                <td width="1">:</td>
                <td><?=Form::inputnum('HTTPSPORT', '', array("MAXLENGTH" => "5"))?> (<?=$lang->menu->default443?>)</td>
            </tr>
            </table>

            <div class="button_set">
                <button type="button" onclick="confirm_send()" class="btn_large4"><?=$lang->button->savereboot?></button>&nbsp;&nbsp;
                <button type="button" onclick="open_edit(_seq)"><?=$lang->button->reset?></button>&nbsp;&nbsp;
                <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>&nbsp;&nbsp;
                <button type="button" onclick="open_cert()" class="btn_large4"><?=$lang->button->update_cert?></button>
            </div>
        </form>
        </div>
</div>

<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->ipaddress?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->IPType?> *</th>
            <td width="1">:</td>
            <td id="view_IPTypeStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->IPAddress?> *</th>
            <td width="1">:</td>
            <td id="view_IPAddress"></td>
        </tr>
        <tr>
            <th><?=$lang->network->Subnet?> *</th>
            <td width="1">:</td>
            <td id="view_Subnet"></td>
        </tr>
        <tr>
            <th><?=$lang->network->Gateway?> *</th>
            <td width="1">:</td>
            <td id="view_Gateway"></td>
        </tr>
        <tr>
            <th><?=$lang->network->DNS1?></th>
            <td width="1">:</td>
            <td id="view_DNS1"></td>
        </tr>
        <tr>
            <th><?=$lang->network->DNS2?></th>
            <td width="1">:</td>
            <td id="view_DNS2"></td>
        </tr>
        <tr>
            <th><?=$lang->network->HTTPPort?></th>
            <td width="1">:</td>
            <td id="view_HTTPPort"></td>
        </tr>
        <tr>
            <th><?=$lang->network->HTTPS?></th>
            <td width="1">:</td>
            <td id="view_HTTPSStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->HTTPSPort?></th>
            <td width="1">:</td>
            <td id="view_HTTPSPORT"></td>
        </tr>
        </table>
        <div class="button_set">
<? if( $this->is_auth(96, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>
<? } ?>
        </div>
    </div>
</div>

<?PHP echo view('common/js'); ?>
<script type="text/javascript">
function create_list()
{
    open_view(0);
}

$(document).ready(function() {
    load_list();
});

// edit data ä���
function set_edit()
{
    set_edit_network('form_edit1');
    set_edit_network('form_edit2');
    set_edit_network('form_edit3');
    set_edit_network('form_edit4');
    set_edit_network('form_edit5');
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

function submit_send()
{
	$('#form_edit1').find('input[name="confirm_pw"]').val($('#form_send').find('input[name="confirm_pw"]').val());
	$('#form_edit1').submit();
}

function confirm_send()
{
	$('#form_send').find('input[name="confirm_pw"]').val('');
	$('#form_edit1').find('input[name="confirm_pw"]').val('');
	confirm_dialog("<?=$lang->common->savereboot?>");
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

function open_cert()
{
    window.open('/?c=ipset&m=cert', 'cert-win', 'scrollbars=yes,toolbar=no,resizable=no,width=800,height=600');
}
</script>