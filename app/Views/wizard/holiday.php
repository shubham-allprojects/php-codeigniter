<div id="wrap">
<div id="location">
	<?=$lang->menu->schedule.'&nbsp;&gt;&nbsp;'.$lang->menu->holiday?>
	<button class="btn_help" onclick="openHelp('help', 'holiday')">Help</button>
</div>

<div id="new_section" class="hide">
	<form id="form_new" method="post" action="<?=base_url()?>holiday-add">
		<h2>:: <?=$lang->menu->holiday?></h2>
		<div class="box01">

			<h3><?=$lang->menu->basic?></h3>
			<table class="tbl_view">
				<tr>
					<th width="150"><?=$lang->holiday->Name?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('Name', "", array("MAXLENGTH"=>max_name_char, "SIZE"=>"30"))?></td>
				</tr>
				<tr>
					<th><?=$lang->holiday->StartTime?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('StartTimeName', '', array('class'=>'date', 'readonly'=>'readonly'))?></td>
				</tr>
				<tr>
					<th><?=$lang->holiday->EndTime?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('EndTimeName', '', array('class'=>'date', 'readonly'=>'readonly'))?></td>
				</tr>
			</table>

			<table class="tbl_view">
				<tr>
					<th width="100"></th>
					<td colspan="2">
						<?=Form::checkbox('Holiday1', $lang->holiday->Holiday1)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday2', $lang->holiday->Holiday2)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday3', $lang->holiday->Holiday3)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday4', $lang->holiday->Holiday4)?>
					</td>
				</tr>
			</table>

			<div class="button_set">
				<button type="button" onclick="$('#form_new').submit()"><?=$lang->button->add?></button>&nbsp;&nbsp;
				<button type="button" onclick="open_new()"><?=$lang->button->reset?></button>&nbsp;&nbsp;
				<button type="button" onclick="close_new()"><?=$lang->button->cancel?></button>
			</div>
		</div>
	</form>
</div>

<div id="edit_section" class="hide">
	<form id="form_edit" method="post" action="<?=base_url()?>holiday-update">
		<?=Form::hidden("No")?>
		<h2>:: <?=$lang->menu->holiday?></h2>
		<div class="box01">

			<h3><?=$lang->menu->basic?></h3>
			<table class="tbl_view">
				<tr>
					<th width="150"><?=$lang->holiday->Name?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('Name', "", array("MAXLENGTH"=>max_name_char, "SIZE"=>"30"))?></td>
				</tr>
				<tr>
					<th><?=$lang->holiday->StartTime?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('StartTimeName', '', array('class'=>'date', 'readonly'=>'readonly'))?></td>
				</tr>
				<tr>
					<th><?=$lang->holiday->EndTime?> *</th>
					<td width="1">:</td>
					<td><?=Form::input('EndTimeName', '', array('class'=>'date', 'readonly'=>'readonly'))?></td>
				</tr>
			</table>

			<table class="tbl_view">
				<tr>
					<th width="100"></th>
					<td colspan="2">
						<?=Form::checkbox('Holiday1', $lang->holiday->Holiday1)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday2', $lang->holiday->Holiday2)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday3', $lang->holiday->Holiday3)?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=Form::checkbox('Holiday4', $lang->holiday->Holiday4)?>
					</td>
				</tr>
			</table>

			<div class="button_set">
				<button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
				<button type="button" onclick="open_edit(_seq)"><?=$lang->button->reset?></button>&nbsp;&nbsp;
				<button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
			</div>
		</div>
	</form>
</div>

<div id="view_section" class="hide">
	<h2>:: <?=$lang->menu->holiday?></h2>
	<div class="box01">

		<h3><?=$lang->menu->basic?></h3>
		<table class="tbl_view">
			<tr style="display:none">
				<th></th>
				<td id="view_No"></td>
			</tr>
			<tr>
				<th width="150"><?=$lang->holiday->Name?> *</th>
				<td width="1">:</td>
				<td id="view_Name"></td>
			</tr>
			<tr>
				<th><?=$lang->holiday->StartTime?></th>
				<td width="1">:</td>
				<td id="view_StartTimeName"></td>
			</tr>
			<tr>
				<th><?=$lang->holiday->EndTime?></th>
				<td width="1">:</td>
				<td id="view_EndTimeName"></td>
			</tr>
		</table>

		<table class="tbl_view">
			<tr>
				<th width="100"></th>
				<td colspan="2">
					<?=$lang->holiday->Holiday1?> : <span id="view_HolidayName1"></span>&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$lang->holiday->Holiday2?> : <span id="view_HolidayName2"></span>&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$lang->holiday->Holiday3?> : <span id="view_HolidayName3"></span>&nbsp;&nbsp;&nbsp;&nbsp;
					<?=$lang->holiday->Holiday4?> : <span id="view_HolidayName4"></span>
				</td>
			</tr>
		</table>

		<div class="button_set">
			<button type="button" onclick="open_edit(_seq)"><?=$lang->button->edit?></button>&nbsp;&nbsp;
			<button type="button" onclick="delete_form()"><?=$lang->button->delete?></button>&nbsp;&nbsp;
			<button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>
		</div>
	</div>
</div>

<div id="list_section">
	<h2>:: <?=$lang->menu->list?></h2>
	<div class="box01">
		<div>
			<span style="color:white"><?=$lang->holiday->year?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span>
			<?=Form::select('year', date('Y'), $year_list, array('onchange'=>"load_list();"))?>
		</div>
		<div class="space" style="height:10px"></div>

		<table class="tbl_list">
			<tr>
				<th><?=$lang->holiday->No?></th>
				<th><?=$lang->holiday->Name?></th>
				<th><?=$lang->holiday->StartTime?></th>
				<th><?=$lang->holiday->EndTime?></th>
				<th><?=$lang->holiday->HolidayGroup?></th>
			</tr>
			<tbody id="list_body">
			</tbody>
		</table>

		<table class="list_button_set">
			<tr>
				<td width="100"><button type="button" onclick="open_new()"><?=$lang->button->new?></button></td>
				<td align="center">
					<form id="form_search" method="post" action="<?=base_url()?>holiday-get"
						onsubmit="load_list_search(); return false;" target="_self">
						<?=Form::select('field', '', array('name'=>$lang->holiday->name))?>
						<?=Form::input('word', '')?>
						<button type="button" onclick="load_list_search()"><?=$lang->button->search?></button>
					</form>
				</td>
				<td width="100" align="right"><button type="button"
						onclick="load_list('<?=base_url()?>holiday-get')"><?=$lang->button->list?></button></td>
			</tr>
		</table>

		<div id="pagination" class="pagination">[ 1 ]</div>
	</div>
</div>
</div>

<?PHP echo view('common/js'); ?>


<script type="text/javascript">
	function create_list() {
		$("#list_body").html("");
		for (var i = 0; i < _data.list.length; i++) {
			$("#list_body").append(
				'<tr id="list_' + i + '" onclick="open_view(' + i + ')" class="ov">' +
				'	<td>' + _data.list[i].No + '</td>' +
				'	<td>' + _data.list[i].Name + '</td>' +
				'	<td>' + _data.list[i].StartTimeName + '</td>' +
				'	<td>' + _data.list[i].EndTimeName + '</td>' +
				'	<td>' + _data.list[i].HolidayGroupStr + '</td>' +
				'</tr>'
			);
		}
		create_pagination();
	}
	$(document).ready(function() {
		load_list();
		$("#form_new input[name='StartTimeName']").datepicker({
			format: "m/d/Y",
			date: "<?=date(" m / d / Y ")?>",
			current: "<?=date(" m / d / Y ")?>",
			starts: 0,
			position: "bottom",
			onBeforeShow: function() {
				var element = $("#form_new input[name='StartTimeName']");
				if (element.val() == "")
					element.DatePickerSetDate("<?=date(" m / d / Y ")?>", true);
				else
					element.DatePickerSetDate(element.val(), true);
			},
			onChange: function(formated, dates) {
				var element = $("#form_new input[name='StartTimeName']");
				if (element.val() != formated) {
					$("#form_new input[name='StartTimeName']").val(formated);
					$("#form_new input[name='StartTimeName']").DatePickerHide();
				}
			}
		});
		$("#form_new input[name='EndTimeName']").datepicker({
			format: "m/d/Y",
			date: "<?=date(" m / d / Y ")?>",
			current: "<?=date(" m / d / Y ")?>",
			starts: 0,
			position: "bottom",
			onBeforeShow: function() {
				var element = $("#form_new input[name='EndTimeName']");
				if (element.val() == "")
					element.DatePickerSetDate("<?=date(" m / d / Y ")?>", true);
				else
					element.DatePickerSetDate(element.val(), true);
			},
			onChange: function(formated, dates) {
				var element = $("#form_new input[name='EndTimeName']");
				if (element.val() != formated) {
					$("#form_new input[name='EndTimeName']").val(formated);
					$("#form_new input[name='EndTimeName']").DatePickerHide();
				}
			}
		});
		$("#form_edit input[name='StartTimeName']").datepicker({
			format: "m/d/Y",
			date: "<?=date(" m / d / Y ")?>",
			current: "<?=date(" m / d / Y ")?>",
			starts: 0,
			position: "bottom",
			onBeforeShow: function() {
				var element = $("#form_edit input[name='StartTimeName']");
				if (element.val() == "")
					element.DatePickerSetDate("<?=date("m / d / Y ")?>", true);
				else
					element.DatePickerSetDate(element.val(), true);
			},
			onChange: function(formated, dates) {
				var element = $("#form_edit input[name='StartTimeName']");
				if (element.val() != formated) {
					$("#form_edit input[name='StartTimeName']").val(formated);
					$("#form_edit input[name='StartTimeName']").DatePickerHide();
				}
			}
		});
		$("#form_edit input[name='EndTimeName']").datepicker({
			format: "m/d/Y",
			date: "<?=date("m / d / Y ")?>",
			current: "<?=date("m / d / Y ")?>",
			starts: 0,
			position: "bottom",
			onBeforeShow: function() {
				var element = $("#form_edit input[name='EndTimeName']");
				if (element.val() == "")
					element.DatePickerSetDate("<?=date("m / d / Y ")?>", true);
				else
					element.DatePickerSetDate(element.val(), true);
			},
			onChange: function(formated, dates) {
				var element = $("#form_edit input[name='EndTimeName']");
				if (element.val() != formated) {
					$("#form_edit input[name='EndTimeName']").val(formated);
					$("#form_edit input[name='EndTimeName']").DatePickerHide();
				}
			}
		});
	});

	function delete_form() {
		if (confirm("<?=$lang->addmsg->confirm_delete?>")) {
			var No = $("#view_No").html();
			$.getJSON("<?=base_url()?>delete/" + No, function() {
				load_list();
			});
		}
	}
	// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
	<?PHP
	if ($sharedMethods->is_auth(8, 1) != TRUE) { ?>
		function open_new() {
			alert("<?=$lang->user->error_not_permission?>");
		} 
		<?PHP } ?>
	<?PHP
	if ($sharedMethods->is_auth(8, 2) != TRUE && $sharedMethods->is_auth(8, 3) != TRUE) { ?>
		function open_edit() {
			alert("<?=$lang->user->error_not_permission?>");
		} <?PHP } ?>
	<?PHP
	if ($sharedMethods->is_auth(8, 2) != TRUE) {
		?>
		function delete_form() {
			alert("<?=$lang->user->error_not_permission?>");
		} <?PHP
	} ?>
	// list data ȣ��
	function load_list(page, field, word, view) {
		if (typeof page == "undefined") page = "";
		if (typeof field == "undefined") field = "";
		if (typeof word == "undefined") word = "";
		if (typeof view == "undefined") view = "";
		year = $("select[name='year']").val();
		$.getJSON(
			"<?=base_url()?>holiday-get/?year=" + year + "&p=" + page + "&f=" + field + "&w=" + word + "&v=" + view,
			function(data) {
				_data = data;
				close_all();
				if (check_error(data)) {
					create_list();
				}
				if (typeof _data.view != "undefined" && _data.view != "") {
					open_view(find_seq(_data.view));
				}
			}
		);
	}
	// searh list data ȣ��
	function load_list_search() {
		year = $("select[name='year']").val();
		$.getJSON(
			"<?=base_url()?>holiday-get/?year=" + year + "&f=" + $("#form_search select[name='field']").val() + "&w=" +
			$("#form_search input[name='word']").val(),
			function(data) {
				_data = data;
				close_all();
				if (check_error(data)) {
					create_list();
				}
			}
		);
	}
</script>