<div id="location">
<?
echo $lang->menu->datatransfers.'&nbsp;&gt;&nbsp;'.$lang->menu->dimport;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<!-- <script type="text/javascript" src="./js/ajaxfileupload.js"></script> -->
<script type="text/javascript">

$(document).ready(function() {
    set_default();
});

function set_default()
{
	$("#form_edit input:radio[name='ExistData']").filter("input[value='0']").attr('checked', true);
}

function confirm_send()
{
    if ($("#form_edit input:radio[name='ExistData']:checked").val() == 2)
        confirm_dialog("<?=$this->lang->common->before_flush_message?>");
    else
    {
        show_loading(); 
        $('#form_edit').submit();
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
<? if( $this->is_auth(73, 18) != TRUE ) { ?>
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
            
            if (sFileName.substr(sFileName.length - 3, 3).toLowerCase() != "csv") {
                node.value = "";
                alert("<?=$this->lang->user->error_file_extensions?>" + ": .csv");
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
	<div class='buttons'>
		<button type="button" onclick="submit_confirm(this.form); $.modal.close();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div id="edit_section">
    <h2>:: <?=$lang->menu->dimport?></h2>
    <div class="box01">
    <form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=import" enctype="multipart/form-data" target="_self">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->menu->filetype?></th>
            <td width="1">:</td>
            <td>
                <input type="radio" name="sel" value="csv" onclick="$('#file_layer').show()" checked /> <?=$lang->menu->csv?> &nbsp; &nbsp; &nbsp;
            </td>
        </tr>
        <tr>
            <th width="150"><?=$lang->addmsg->exist_data?></th>
            <td width="1">:</td>
            <td><?=Form::radio('ExistData', '', EnumTable::$attrDataExists, '&nbsp;&nbsp;')?></td>
        </tr>
        <tr id="file_layer" style="display:;">
            <th width="150"><?=$lang->menu->file?></th>
            <td width="1">:</td>
            <td>
                <input type="file" id="upload_file" name="upload_file"  size="60" accept=".csv" onchange="checkUpdateFile('upload_file', 1000 * 1024 * 15)" />
            </td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(73, 18) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->menu->import?></button>
<? } else { ?>
			<button type="button" onclick="confirm_send();"><?=$lang->menu->import?></button>
<? } ?>        
            <!-- Change By SUN07 : <button type="button" onclick="show_loading(); $('#form_edit').submit()"><?=$lang->menu->import?></button> -->
        </div>
    </form>
    </div>
</div>