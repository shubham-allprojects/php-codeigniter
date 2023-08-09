<div id="location">
<?
echo $lang->menu->client_setting.'&nbsp;&gt;&nbsp;'.$lang->menu->clientreplace;
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
            '   <td>'+ _data.list[i].Name +'</td>' +
            '   <td>'+ _data.list[i].TypeStr +'</td>' +
            '   <td>'+ _data.list[i].IP +'</td>' +
            '   <td>'+ _data.list[i].Mac +'</td>' +
            '</tr>'
        );
    }
    create_pagination();
}

$(document).ready(function() {
    load_list();
});


function permission_denied()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
</script>

<div id="edit_section" class="hide">
<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update">
<?=Form::hidden("No")?>
    <h2>:: <?=$lang->menu->clientreplace?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr style="display:none;">
            <td id="view_No"></td>
            <td></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->menu->name?></th>
            <td width="1">:</td>
            <td id="view_Name"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->type?></th>
            <td width="1">:</td>
            <td id="view_TypeStr"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->ipaddress?></th>
            <td width="1">:</td>
            <td id="view_IP"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->macaddress?> *</th>
            <td width="1">:</td>
            <td><?=Form::Input('Mac', array("MAXLENGTH"=>"17"))?></td>
        </tr>
        </table>

        <div class="button_set">
			<? if( $this->is_auth(51, 3) != TRUE ) { ?>
            <button type="button" onclick="permission_denied();"><?=$lang->button->save?></button>&nbsp;&nbsp;
			<? } else { ?>
            <button type="button" onclick="$('#form_edit').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
			<? } ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->clientreplace?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr style="display:none;">
            <td id="view_No"></td>
            <td></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->menu->name?></th>
            <td width="1">:</td>
            <td id="view_Name2"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->type?></th>
            <td width="1">:</td>
            <td id="view_TypeStr2"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->ipaddress?></th>
            <td width="1">:</td>
            <td id="view_IP2"></td>
        </tr>
        <tr>
            <th><?=$lang->menu->macaddress?> *</th>
            <td width="1">:</td>
            <td id="view_Mac"></td>
        </tr>
        </table>

        <div class="button_set">
			<? if( $this->is_auth(51, 3) != TRUE ) { ?>
            <button type="button" onclick="permission_denied();"><?=$lang->button->edit?></button>&nbsp;&nbsp;
			<? } else { ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>&nbsp;&nbsp;
			<? } ?>
            <button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>&nbsp;&nbsp;
        </div>
    </div>
</div>

<div id="list_section">
    <h2>:: <?=$lang->menu->list?></h2>
    <div class="box01">
        <table class="tbl_list">
        <tr>
            <th><?=$lang->menu->no?></th>
            <th><?=$lang->menu->name?></th>
            <th><?=$lang->menu->type?></th>
            <th><?=$lang->menu->ipaddress?></th>
            <th><?=$lang->menu->macaddress?></th>
        </tr>
        <tbody id="list_body">
        </tbody>
        </table>

        <table class="list_button_set">
        <tr>
            <td align="center">
            <form id="form_search" method="post" action="/?c=<?=$this->class?>&m=select" onsubmit="load_list_search(); return false;">
            <?=Form::select('field', '', array('Name'=>$lang->menu->Name))?>
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
