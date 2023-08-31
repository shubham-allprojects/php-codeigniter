<div id="location">
<?
echo $lang->menu->networks.'&nbsp;&gt;&nbsp;'.$lang->menu->ipaddress.'&nbsp;&gt;&nbsp;'.$lang->menu->ssl_toolbox;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>
<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=_exe" onsubmit="submit_send(); $.modal.close(); show_loading(); return false;">
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


<div id="edit_section" class="">
    <h2>:: <?=$lang->menu->ipaddress?></h2>
    <div class="box01">
        <form id="form_cert" method="post" action="/?c=<?=$this->class?>&m=save_cert">
            <input type="hidden" name="confirm_pw" value="">
            <table class="tbl_view">
            <tr>
                <td>
                    <p><?=$lang->network->enter_private_key?>:</p>
                    <textarea name="privatekey" style="width: 99%; height: 150px;"><?=$privatekey?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <p><?=$lang->network->enter_certificate?>:</p>
                    <textarea name="certificate" style="width: 99%; height: 150px;"><?=$certificate?></textarea>
                </td>
            </tr>
            </table>
<!--
            <table class="tbl_view">
            <tr>
                <th width="150"><?=$lang->network->provide_password?></th>
                <td width="1">:</td>
                <td><?=Form::password('provide_password', '')?></td>
            </tr>
            </table>
 -->
            <div class="button_set">
                <button type="button" onclick="confirm_send();" class="btn_large4"><?=$lang->button->savereboot?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function submit_send()
{
	$('#form_cert').find('input[name="confirm_pw"]').val($('#form_send').find('input[name="confirm_pw"]').val());
	$('#form_cert').submit();
}

function confirm_send()
{
	$('#form_send').find('input[name="confirm_pw"]').val('');
	$('#form_cert').find('input[name="confirm_pw"]').val('');
	confirm_dialog("<?=$this->lang->common->savereboot?>");
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