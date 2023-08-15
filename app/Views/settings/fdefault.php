<div id="location">
<?
echo $lang->menu->systems.'&nbsp;&gt;&nbsp;'.$lang->menu->fdefault;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">
function submit_send()
{
	var form		= $('#form_send');
	var type        = form.attr('method');
	var url         = form.attr('action');
	var data        = form.serialize();

	$.modal.close();
	
    $.ajax({
        type        : type,
        url         : url,
        data        : data,
        success     : function(response) { try{ eval(response); } catch(e) { alert("The connection to the server has a problem. Please try again later."); hide_loading(); } },
        beforeSend  : function() { show_loading(); },
        complete    : function() { hide_loading(); }
        //error       : function() { alert("The connection to the server has a problem. Please try again later."); }
    });
}

function confirm_send()
{
	confirm_dialog("<?=$this->lang->common->fdefault?>");
}

function confirm_dialog(message, callback) {
	$('#confirm-dialog').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		containerCss: {
			height: 230
		},
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


// 권한 체크해서 등록 페이지 호출을 재정의 함.
<? if( $this->is_auth(95, 3) != TRUE ) { ?>
function confirm_send()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>
</script>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=_exe" onsubmit="submit_send(); return false;">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="submit_send();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div id="edit_section">
	<h2>:: <?=$lang->menu->fdefault?></h2>
	<div class="box01">

		<div style="margin:50px;text-align:center">
<? if( $this->is_auth(95, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');" class="btn_large4"><?=$lang->menu->fdefault?></button>
<? } else { ?>
			<button onclick="confirm_send()" class="btn_large4"><?=$lang->menu->fdefault?></button>
<? } ?>		
		</div>		
		<div style="margin:10px;text-align:center;display:none" id="down"></div>
	</div>
</div>

