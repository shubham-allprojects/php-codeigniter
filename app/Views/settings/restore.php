<div id="location">
<?
echo $lang->menu->systems.'&nbsp;&gt;&nbsp;'.$lang->menu->restore;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">
function load_file_list(type)
{
	$.getJSON("/?c=restore&m=file_list", function(data, textStatus, jqXHR) {
		$("#file_name").empty();
		if( check_error(data) ) {
			$.each(data, function(i, val) {
				$("#file_name").append('<option value="'+val+'">'+val+'</option>');
			});
		}
	});

	$('#file_select').show();
}

function confirm_send()
{
    var selected_option = $("input[name='sel']:checked").val();
    var error = false;
    if(selected_option == 'pc'){
        if(!$("#upload_file").val()){
            error = true;
            alert("Please select file");            
        }else{
            error = false;
        }
    }else if(selected_option == 'sd'){
        if(!$("#file_name").val()){
            error = true;
            alert("Please select file");            
        }else{
            error = false;
        }
    }
    if(!error){
        confirm_dialog("<?=$this->lang->common->before_restore_message?>");
    }    
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
<? if( $this->is_auth(93, 3) != TRUE ) { ?>
function confirm_send()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

function submit_confirm(form)
{
    var form    = $(form);
    $.ajax({
        type        : form.attr('method'),
        url         : form.attr('action'),
        data        : form.serialize(),
        success     : function(response) { try{ eval(response); } catch(e) { alert("The connection to the server has a problem. Please try again later."); hide_loading(); } },
        beforeSend  : function() { show_loading(); },
        complete    : function() {  }
        //error       : function() { alert("The connection to the server has a problem. Please try again later."); }
    });
}

function checkUpdateFile(byId, maxSize)
{   
    var node = document.getElementById(byId);
    
    if (node.type == "file") {
        var sFileName = node.value;
        if (sFileName.length > 0) {
            
            if (sFileName.substr(sFileName.length - 3, 3).toLowerCase() != "enc") {
                node.value = "";
                alert("<?=$this->lang->user->error_file_extensions?>" + ": .enc");
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


<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=confirm_pw" target="_self" onsubmit="submit_confirm(this); $.modal.close(); return false;">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="submit_confirm(this.form); $.modal.close();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div id="edit_section">
	<h2>:: <?=$lang->menu->restore?></h2>
	<div class="box01">
	<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=_exec" enctype="multipart/form-data" target="_self">
		<h3><?=$lang->menu->basic?></h3>
		<table class="tbl_view">
		<tr>
			<th width="150"><?=$lang->menu->restoretype?></th>
			<td width="1">:</td>
			<td>
				<input type="radio" name="sel" value="pc" checked onclick="$('#file_layer').show(); $('#file_select').hide();" /> <?=$lang->menu->userpc?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="sd"  onclick="$('#file_layer').hide(); load_file_list('sd');" /> <?=$lang->menu->sdcard?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="ftp" onclick="$('#file_layer').hide(); $('#file_select').hide();" /> <?=$lang->menu->ftpserver?> &nbsp; &nbsp; &nbsp;
			</td>
		</tr>
		<tr id="file_layer">
			<th><?=$lang->menu->file?></th>
			<td width="1">:</td>
			<td>
				<input type="file" id="upload_file" name="upload_file" accept=".enc" size="45" onchange="checkUpdateFile('upload_file', 1000 * 1024 * 15)" />
			</td>
		</tr>
		<tr id="file_select" class="hide">
			<th><?=$lang->menu->file?></th>
			<td width="1">:</td>
			<td>
				<select name="file_name" id="file_name"></select>
			</td>
		</tr>
		</table>

		<div class="button_set">
<? if( $this->is_auth(93, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->menu->restore?></button>
<? } else { ?>
			<button type="button" onclick="confirm_send();"><?=$lang->menu->restore?></button>
			<!-- <button type="button" onclick="$('#form_edit').submit()"><?=$lang->menu->restore?></button> -->
<? } ?>
		</div>
	</form>
	</div>
</div>
