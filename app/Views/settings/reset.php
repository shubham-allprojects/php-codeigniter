<div id="location">
<?
echo $lang->menu->systems.'&nbsp;&gt;&nbsp;'.$lang->menu->reboot;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">
var save_type = <?=save_type ?>;

function confirm_send(type)
{
	if ( type == 'reboot' )
	{
		$("input[name='save_type']").val('reboot');
		confirm_dialog("<?=$this->lang->common->reboot?>");
	}
	else
	{
        $("input[name='save_type']").val('backup');
		confirm_dialog("<?=$this->lang->common->savedata?>");
	}
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
/*
			// if the user clicks "yes"
			$('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				modal.close(); // or $.modal.close();
			});
*/
		}
	});
    
}

</script>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=_exe" onsubmit="submit_form(this); $.modal.close();  show_loading(); return false;">
	<input type="hidden" name="save_type" value="">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="submit_form(this.form); $.modal.close();  show_loading();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div id="edit_section">
    <h2>:: <?=$lang->menu->reboot?></h2>
    <div class="box01">
        <h3><?php echo $lang->menu->save_reboot;?></h3>
        <table class="tbl_view">

        </table>
        <div style="margin:10px;text-align:center">
<? if( $this->is_auth(94, 3) != TRUE) { ?>
			<!--<button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');" class="btn_large4"><?=$lang->button->savedata?></button>-->
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');" class="btn_large2"><?=$lang->button->reboot?></button>
<? } else { ?>
			<!--<button onclick="confirm_send('backup')" class="btn_large4"><?=$lang->button->savedata?></button>-->
			<button onclick="confirm_send('reboot')" class="btn_large2"><?=$lang->button->reboot?></button>
<? } ?>
        </div>
        <div style="margin:10px;text-align:center;display:none" id="down"></div>
    </div>
</div>