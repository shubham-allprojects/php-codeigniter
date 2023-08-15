<div id="location">
<?
echo $lang->menu->user_setting.'&nbsp;&gt;&nbsp;'.$lang->menu->user_role;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>


<script type="text/javascript">
function create_list()
{
	$("#list_body").html("");
	for(var i=0; i<_data.list.length; i++)
	{
		$("#list_body").append(
			'<tr id="list_'+ i +'" class="ov" onclick="open_view('+ _data.list[i].No +')">' +
			'	<td>'+ _data.list[i].No +'</td>' +
			'	<td>'+ _data.list[i].Name +'</td>' +
			'</tr>'
		);
	}

	create_pagination();
}

$(document).ready(function() {
	load_list();
});

function open_view(no, def)
{
	if( arguments.length < 2 ) def = "";
	$("#new_section").empty().hide();
	$("#edit_section").empty().hide();
	$("#view_section").load("/?c=<?=$this->class?>&m=view&No="+no).show();
}

function open_edit(no, def)
{
	if( arguments.length < 2 ) def = "";
    $("#view_section").empty().hide();
	$("#new_section").empty().hide();
	$("#edit_section").load("/?c=<?=$this->class?>&m=edit&No="+no+"&default="+def, function() { push_dvr(); push_nvr(); push_camera(); push_report(); change_device(1); }).show();
}

function open_new(def)
{
	if(!check_user_count()) {
		alert("<?=$lang->menu->error_too_many_userrole?>");
		return;
	}

	if( arguments.length < 1 ) def = "";
    $("#view_section").empty().hide();
	$("#edit_section").empty().hide();
	$("#new_section").load("/?c=<?=$this->class?>&m=newrow&default="+def, function() { push_dvr(); push_nvr(); push_camera(); push_report(); change_device(1); }).show();
}

function check_user_count()
{
	if(_data.count >= <?=EnumTable::$attrModelSpec[$_SESSION['spider_model']][$_SESSION['spider_kind']][13]?>) {
		return false;
	}

	return true;
}

function toggle_check(el)
{
	var val = $(el).val();
	var values = val.split(",");

	$.each(values, function(index, value) {
		$("input:checkbox[value='"+value+"']").attr("checked", $(el).attr("checked"));
	});

	if($(el).is(':checked')) {
		$.each(values, function(index, value) {
			$("input:checkbox[value='"+value+"']").triggerHandler("click");
		});
	}
}

function dependency_check(el, val, message)
{
	if(! $(el).is(':checked')) {
		return;
	}

	var values = val.split(",");
	var isConfirm = null;

	$.each(values, function(index, value) {
		if(! $("input:checkbox[value='"+value+"']").is(':checked')) {
			if(isConfirm == null) {
				if(confirm(message)) {
					isConfirm = true;
				} else {
					isConfirm = false;
					$(el).attr("checked", false);
				}
			}

			if(isConfirm) {
				$("input:checkbox[value='"+value+"']").attr("checked", true);
			}
		}
	});
}

function move_select_option(src, dest)
{
	var options = src.find("option:selected") || [];
	$.each( options, function(index, value) { $(value).appendTo(dest); });
}

function push_camera()	{ move_select_option($("select[name='camera_list']"), $("select[name='userrolecameras[]']")); }
function pop_camera()	{ move_select_option($("select[name='userrolecameras[]']"), $("select[name='camera_list']")); }
function push_dvr()	    { move_select_option($("select[name='dvr_list']"), $("select[name='userroledvrs[]']")); }
function pop_dvr()	    { move_select_option($("select[name='userroledvrs[]']"), $("select[name='dvr_list']")); }
function push_nvr()	    { move_select_option($("select[name='nvr_list']"), $("select[name='userrolenvrs[]']")); }
function pop_nvr()	    { move_select_option($("select[name='userrolenvrs[]']"), $("select[name='nvr_list']")); }
function push_report()	{ move_select_option($("select[name='report_list']"), $("select[name='userrolereports[]']")); }
function pop_report()	{ move_select_option($("select[name='userrolereports[]']"), $("select[name='report_list']")); }
function push_door()	{ move_select_option($("select[name='door_list']"), $("select[name='userroledoors[]']")); }
function pop_door()		{ move_select_option($("select[name='userroledoors[]']"), $("select[name='door_list']")); }
function push_elevator(){ move_select_option($("select[name='elevator_list']"), $("select[name='userroleelevators[]']")); }
function pop_elevator()	{ move_select_option($("select[name='userroleelevators[]']"), $("select[name='elevator_list']")); }
function push_auxin()	{ move_select_option($("select[name='auxin_list']"), $("select[name='userroleauxins[]']")); }
function pop_auxin()	{ move_select_option($("select[name='userroleauxins[]']"), $("select[name='auxin_list']")); }
function push_auxout()	{ move_select_option($("select[name='auxout_list']"), $("select[name='userroleauxouts[]']")); }
function pop_auxout()	{ move_select_option($("select[name='userroleauxouts[]']"), $("select[name='auxout_list']")); }

function change_device(device)
{
	$(".device_section").hide();
	$("#device_section_"+device).show();
}

function disable_device(device, disabled)
{
	$("#device_section_"+device).find("input[type!='checkbox'], select").each(function(index, value) 
	{
		$(value).attr("disabled", !disabled);
	});
}

function clear_device(device)
{
	switch( device ) {
		case '1': $("select[name='userroledoors[]']").find("option").remove(); 		break;
		case '2': $("select[name='userroleelevators[]']").find("option").remove(); 	break;
		case '3': $("select[name='userroleauxins[]']").find("option").remove(); 	break;
		case '4': $("select[name='userroleauxouts[]']").find("option").remove(); 	break;
	}
}

function search_device(device)
{
	var keyword, dest_list, seltype, url;

	switch( device ) {
		case '1':
			keyword		= $("input[name=DoorSearch]").val();
			dest_list	= $("select[name=door_list]");
			seltype		= $("input:radio[name='DoorSelType']:checked").val();
			break;
		case '2':
			keyword		= $("input[name=ElevatorSearch]").val();
			dest_list	= $("select[name=elevator_list]");
			seltype		= $("input:radio[name='ElevatorSelType']:checked").val();
			break;
		case '3':
			keyword		= $("input[name=AuxInSearch]").val();
			dest_list	= $("select[name=auxin_list]");
			seltype		= $("input:radio[name='AuxInSelType']:checked").val();
			break;
		case '4':
			keyword		= $("input[name=AuxOutSearch]").val();
			dest_list	= $("select[name=auxout_list]");
			seltype		= $("input:radio[name='AuxOutSelType']:checked").val();
			break;
	}

	dest_list.find("option").remove();

	$.getJSON("/?c=userrole&m=search_device&device="+ device +"&seltype="+ seltype +"&keyword="+ keyword, function(data, textStatus, jqXHR) {
		$.each(data, function(i, item) {
			$("<option></option>")
				.val(item.No)
				.text(item.Name)
				.appendTo(dest_list);
		});
	});
}

function pre_submit()
{
	var options;

	options = $("select[name='userrolecameras[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userroledvrs[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });
    
    options = $("select[name='userrolenvrs[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userrolereports[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userroledoors[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userroleelevators[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userroleauxins[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });

	options = $("select[name='userroleauxouts[]']").find("option") || [];
	$.each( options, function(index, value) { $(value).attr("selected", "true"); });
}

<? if( $this->is_auth(56, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(56, 2) != TRUE && $this->is_auth(56, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

function delete_data(no)
{
	if( confirm("<?=$this->lang->addmsg->confirm_delete?>") )
	{
		$.getScript("/?c=<?=$this->class?>&m=check_dependency&no="+no);
	}
}

function del_data_prepass(no) {
	$.getScript("/?c=<?=$this->class?>&m=delete&no="+no,function(){
		load_list();
	});
}

function confirm_dependency(no)
{
	if( confirm("<?=$this->lang->addmsg->confirm_data_delete?>") )
	{
		del_data_prepass(no);
	}
}

<? if( $this->is_auth(56, 2) != TRUE) { ?>
function delete_data()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

</script>

<div id="new_section" class="hide"></div>
<div id="edit_section" class="hide"></div>
<div id="view_section" class="hide"></div>

<div id="list_section">
	<h3>:: LIST</h3>
	<div class="box02">
		<table class="tbl_list">
		<tr>
			<th width="80"><?=$lang->userrole->No?></th>
			<th><?=$lang->userrole->name?></th>
		</tr>
		<tbody id="list_body">
		</tbody>
		</table>

		<table class="list_button_set">
		<tr>
			<td width="100"><button type="button" onclick="open_new();"><?=$lang->button->new?></button></td>
			<td align="center">
			<form id="form_search" method="post" action="/?c=<?=$this->class?>&m=select" onsubmit="load_list_search(); return false;" target="_self">
			<?=Form::select('field', '', array('name'=>$lang->userrole->name))?>
			<?=Form::input('word', '')?>
			<button type="button" onclick="load_list_search()"><?=$lang->button->search?></button>
			</form>
			</td>
			<td width="100" align="right"><button type="button" onclick="load_list()"><?=$lang->button->list?></button></td>
		</tr>
		</table>

		<div id="pagination" class="pagination">[ 1 ]</div>
	</div>
</div>
