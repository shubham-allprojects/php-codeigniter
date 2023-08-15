<div id="location">
<?
echo $lang->menu->systems.'&nbsp;&gt;&nbsp;'.$lang->menu->update;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>


<script>
var fileSize = 0;
var logStart = 0;

function getTxt()
{
	if (logStart == 0)
		return;
		
	$.ajax({
		url:'upgrade.log',
		success: function (data) {
			//parse your data here//you can split into lines using data.split('\n')//an use regex functions to effectively parse it
			//$("#logSelect").append(data);
			$("#dateTime").html(Date());
			var rows=data.split("\n");
			if (fileSize==0) {// when file open for first time
				fileSize=rows;
				jQuery.each(rows, function(i,val) {
					$("#logSelect").append("</br>"+val);
				});
			}
			else
			{ // file already opened
				if(fileSize <rows) {
					//$("#logSelect").html(data);
					$("#logSelect").html("");
					jQuery.each(rows, function(i,val) {
						$("#logSelect").append("</br>"+val);
					});
					fileSize=rows;
				}
				else
				{
					$("#logSelect").append(" * ");
				}
			}
		},
		complete:function() {
			setTimeout(getTxt,1000);
		}
	});
}

function submit_send()
{
	$('#form_edit').find('input[name="confirm_pw"]').val($('#form_send').find('input[name="confirm_pw"]').val());
	$.modal.close();
	show_loading();
	$('#form_edit').submit();
}

function confirm_send()
{
	$('#form_send').find('input[name="confirm_pw"]').val('');
	$('#form_edit').find('input[name="confirm_pw"]').val('');
	confirm_dialog("<?=$this->lang->common->update_warning_message?>");
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

// 권한 체크해서 등록 페이지 호출을 재정의 함.
<? if( $this->is_auth(91, 3) != TRUE ) { ?>
function confirm_send()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}

<? } ?>

</script>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=_exe" onsubmit="show_loading(); submit_send(); return false;">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="logStart=1; getTxt(); submit_send();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div id="edit_section">
	<h2>:: <?=$lang->menu->update?></h2>
	<div class="box01">
	<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=_exec" enctype="multipart/form-data" target="_self">
		<input type="hidden" name="confirm_pw" value="">
		<h3><?=$lang->menu->basic?></h3>
		<table class="tbl_view">
		<tr>
			<th width="150"><?=$lang->menu->version?></th>
			<td width="1">:</td>
            <td><?= $VersionStr ?></td>
		</tr>
		<tr>
			<th width="150"><?=$lang->menu->updatetype?></th>
			<td width="1">:</td>
			<td>
				<input type="radio" name="sel" value="pc" onclick="$('#file_layer').show()" /> <?=$lang->menu->userpc?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="sd" onclick="$('#file_layer').hide()" /> <?=$lang->menu->sdcard?> &nbsp; &nbsp; &nbsp;
				<input type="radio" name="sel" value="ftp" checked onclick="$('#file_layer').hide()" /> <?=$lang->menu->updatesev?> (<?=$lang->common->last_version?> : <?= $LastVersionStr ?>)&nbsp; &nbsp; &nbsp;
			</td>
		</tr>
		<tr id="file_layer" class="hide">
			<th width="150"><?=$lang->menu->file?></th>
			<td width="1">:</td>
			<td>
				<input type="file" name="upload_file" size="65" />
			</td>
		</tr>

<!--
		<tr>
			<td><?=Form::checkbox('alarm_Map')?> Auto Update </td>
			<td><?=Form::select('term', '', array('1'=>'01:00','2'=>'02:00','3'=>'03:00','4'=>'04:00','5'=>'05:00','6'=>'06:00'), 'disabled')?></td>
		</tr>
-->
		</table>

		<div class="button_set">
<? if( $this->is_auth(91, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->update?></button>
<? } else { ?>
			<button type="button" onclick="confirm_send();"><?=$lang->menu->update?></button>
<? } ?>
		</div>
		
		
	</form>
	</div>
</div>

<div >
	<!--<body onload="getTxt();">-->
		<h2><span id="dateTime"></span></h2>
		<div style="width:90%;margin:0 auto;background:#f6f6ff;" id="logSelect"disabled >
			<?php
				//***************
				// Please Specify path of log file here
				// Please Specify path of log file here
				//$logFile="upgrade.log";
				// Please Specify path of log file here
				// Please Specify path of log file here
				//***************
				/*$myfile = fopen($logFile, "r") or die("Unable to open file!");
				echo fread($myfile,filesize($logFile));
				fclose($myfile);
				$lines=file($logFile);
				foreach($lines as $line_num=>$line){
				echo "$line";
				echo "<br>";
				}*/
			?>
		</div>
	<!--</body>-->
</div>
<div>
	<tr>&nbsp;</tr>
</div>

