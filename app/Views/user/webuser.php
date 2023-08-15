<div id="location">
<?
echo $lang->menu->userset.'&nbsp;&gt;&nbsp;'.$lang->menu->webuser;
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
            '<tr id="list_'+ i +'" onclick="open_view('+ i +')" class="ov">' +
            '   <td>'+ _data.list[i].No +'</td>' +
            '   <td>'+ _data.list[i].ID +'</td>' +
            '   <td>'+ _data.list[i].Name +'</td>' +
            //'   <td>'+ _data.list[i].TypeStr +'</td>' +
            '   <td>'+ _data.list[i].UserRoleStr +'</td>' +
            '</tr>'
        );
    }

    create_pagination();
}

$(document).ready(function() {
    load_list();
});

function del_data(){
    if (confirm("<?=$this->lang->addmsg->confirm_delete?>")) {
        var no = $("#view_No").html();
        $.getScript("/?c=<?=$this->class?>&m=check_dependency&no="+no);
    }
}

function del_data_prepass() {
	var no = $("#view_No").html();
	$.getScript("/?c=<?=$this->class?>&m=delete&no="+no,function(){
		//update_list();
	});
}

function init_form_data(form)
{
	$("#form_" + form +" select").each(function() {
		dropdownEmptyToFirst($(this));
	});
}

function before_open_new()
{
	$.ajax({
		cache: false,
		dataType: 'script',
		url: '/?c=webuser&m=check_max_count'
	});
}


// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
<? if( $this->is_auth(57, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(57, 2) != TRUE && $this->is_auth(57, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(57, 2) != TRUE) { ?>
function del_data()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

function confirm_dependency()
{
	if( confirm("<?=$this->lang->addmsg->confirm_data_delete?>") )
	{
		del_data_prepass();
	}
}

</script>

<div id="new_section" class="hide">
<form id="form_new" method="post" action="/?c=<?=$this->class?>&m=insert">
<?=Form::hidden("No")?>
    <h2>:: <?=$lang->menu->webuser?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
            <tr>
                <th><?=$lang->webuser->ID?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('ID', "", array("MAXLENGTH"=>ConstTable::max_admin_id_char))?></td>
            </tr>
            <tr>
                <th><?=$lang->webuser->Password?> *</th>
                <td width="1">:</td>
                <td><?=Form::password('Password', "", array("MAXLENGTH"=>ConstTable::max_password_char))?></td>
            </tr>
            <tr>
                <th width="150"><?=$lang->webuser->Name?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
            </tr>
            <!-- <tr>
                <th><?=$lang->webuser->Type?></th>
                <td width="1">:</td>
                <td><?=Form::select('Type', '', EnumTable::$attrUserType)?></td>
            </tr> -->
            <tr>
                <th><?=$lang->webuser->UserRole?></th>
                <td width="1">:</td>
                <td><?=Form::select('UserRole', '', $this->attrUserRole)?></td>
            </tr>
			<tr>
				<th><?=$lang->webuser->language?></th>
				<td width="1">:</td>
                <td><?=Form::select('Language', '', EnumTable::$attrLanguage)?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultpage?></th>
				<td width="1">:</td>
                <td><?=Form::select('DefaultPage', '', EnumTable::$attrDefaultPage)?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultfloor?></th>
				<td width="1">:</td>
                <td><?=Form::select('DefaultFloorNo', '', $this->to_array_floor())?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultfloorstate?></th>
				<td width="1">:</td>
				<td><?=Form::select('DefaultFloorState', '', array_reverse(EnumTable::$attrYesNo, TRUE))?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->AutoDisconnectTime?></th>
				<td width="1">:</td>
				<td><?=Form::select('AutoDisconnectTime', '', EnumTable::$attrAutoDisconnectTime)?></td>
			</tr>
        </table>
        
        <div class="button_set">
            <button type="button" onclick="$('#form_new').submit()"><?=$lang->button->add?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_new(); init_form_data('new');"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_new()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>

<div id="edit_section" class="hide">
<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update">
<?=Form::hidden("No")?>
<?=Form::hidden("ID")?>
    <h2>:: <?=$lang->menu->webuser?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
            <tr>
                <th><?=$lang->webuser->ID?> *</th>
                <td width="1">:</td>
                <td id="view_ID2"></td>
            </tr>
            <tr>
                <th><?=$lang->webuser->Password?> *</th>
                <td width="1">:</td>
                <td><?=Form::password('Password', "", array("MAXLENGTH"=>ConstTable::max_password_char))?></td>
            </tr>
            <tr>
                <th width="150"><?=$lang->webuser->Name?> *</th>
                <td width="1">:</td>
                <td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
            </tr>
            <!-- <tr>
                <th><?=$lang->webuser->Type?></th>
                <td width="1">:</td>
                <td><?=Form::select('Type', '', EnumTable::$attrUserType)?></td>
            </tr> -->
            <tr>
                <th><?=$lang->webuser->UserRole?></th>
                <td width="1">:</td>
                <td><?=Form::select('UserRole', '', $this->attrUserRole)?></td>
            </tr>
			<tr>
				<th><?=$lang->webuser->language?></th>
				<td width="1">:</td>
                <td><?=Form::select('Language', '', EnumTable::$attrLanguage)?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultpage?></th>
				<td width="1">:</td>
                <td><?=Form::select('DefaultPage', '', EnumTable::$attrDefaultPage)?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultfloor?></th>
				<td width="1">:</td>
                <td><?=Form::select('DefaultFloorNo', '', $this->to_array_floor())?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->defaultfloorstate?></th>
				<td width="1">:</td>
				<td><?=Form::select('DefaultFloorState', '', array_reverse(EnumTable::$attrYesNo, TRUE))?></td>
			</tr>
			<tr>
				<th><?=$lang->webuser->AutoDisconnectTime?></th>
				<td width="1">:</td>
				<td><?=Form::select('AutoDisconnectTime', '', EnumTable::$attrAutoDisconnectTime)?></td>
			</tr>
        </table>
        
        <div class="button_set">
            <button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq); init_form_data('edit');"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>

<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->webuser?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr style="display:none;">
            <td id="view_No"></td>
            <td></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->ID?></th>
            <td width="1">:</td>
            <td id="view_ID"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->webuser->Name?></th>
            <td width="1">:</td>
            <td id="view_Name"></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->webuser->Type?></th>
            <td width="1">:</td>
            <td id="view_TypeStr"></td>
        </tr> -->
        <tr>
            <th><?=$lang->webuser->UserRole?></th>
            <td width="1">:</td>
            <td id="view_UserRoleStr"></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->language?></th>
            <td width="1">:</td>
            <td id="view_LanguageStr"></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->defaultpage?></th>
            <td width="1">:</td>
            <td id="view_DefaultPageStr"></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->defaultfloor?></th>
            <td width="1">:</td>
            <td id="view_DefaultFloorStr"></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->defaultfloorstate?></th>
            <td width="1">:</td>
            <td id="view_DefaultFloorSateStr"></td>
        </tr>
        <tr>
            <th><?=$lang->webuser->AutoDisconnectTime?></th>
            <td width="1">:</td>
            <td id="view_AutoDisconnectTimeStr"></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="open_edit(_seq); init_form_data('edit');"><?=$lang->button->edit?></button>&nbsp;&nbsp;
            <button type="button" onclick="del_data();"><?=$lang->button->Delete?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</div>


<div id="list_section">
    <h2>:: <?=$lang->menu->list?></h2>
    <div class="box01">
        <table class="tbl_list">
        <tr>
            <th><?=$lang->webuser->No?></th>
            <th><?=$lang->webuser->ID?></th>
            <th><?=$lang->webuser->Name?></th>
            <!-- <th><?=$lang->webuser->Type?></th> -->
            <th><?=$lang->webuser->UserRole?></th>
        </tr>
        <tbody id="list_body">
        </tbody>
        </table>

        <table class="list_button_set">
        <tr>
            <td width="100"><button type="button" onclick="before_open_new();"><?=$lang->button->new?></button></td>
            <td align="center">
            <form id="form_search" method="post" action="/?c=<?=$this->class?>&m=select" onsubmit="load_list_search(); return false;" target="_self">
            <?=Form::select('field', '', array('ID'=>$lang->webuser->ID, 'Name'=>$lang->webuser->Name))?>
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
